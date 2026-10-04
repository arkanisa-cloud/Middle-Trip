<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExpeditionController extends Controller
{
    /**
     * Menampilkan katalog seluruh produk ekspedisi MiddleTrip.
     */
    public function index(Request $request): View
    {
        $query = Mountain::query()
            ->active()
            ->with(['primaryRoute', 'routes']);

        // Filter tipe trip (all / open / private)
        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'open') {
                $query->where('has_open_trip', true);
            } elseif ($request->type === 'private') {
                $query->where('has_private_trip', true);
            }
        }

        // Filter Grade kesulitan (berdasarkan jalur utama)
        if ($request->filled('grade') && $request->grade !== 'all') {
            $query->whereHas('primaryRoute', function ($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        // Filter pencarian gunung
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        } elseif ($request->filled('gunung')) {
            $query->where('name', 'like', '%'.$request->gunung.'%');
        }

        $mountains = $query->orderBy('name')->get();

        return view('customer.shop', compact('mountains'));
    }

    /**
     * Endpoint API JSON untuk Quick Search Modal di Navbar.
     */
    public function searchApi(Request $request)
    {
        $query = Mountain::query()
            ->active()
            ->with(['primaryRoute', 'routes']);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('province', 'like', '%'.$keyword.'%')
                    ->orWhere('regency', 'like', '%'.$keyword.'%')
                    ->orWhereHas('routes', function ($rq) use ($keyword) {
                        $rq->where('name', 'like', '%'.$keyword.'%');
                    });
            });
        }

        if ($request->filled('grade') && $request->grade !== 'all') {
            $query->whereHas('primaryRoute', function ($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        $mountains = $query->orderBy('name')->limit(12)->get()->map(function ($mountain) {
            return [
                'id' => $mountain->id,
                'name' => $mountain->name,
                'slug' => $mountain->slug,
                'elevation' => $mountain->formatted_elevation,
                'location' => trim(($mountain->regency ? $mountain->regency . ', ' : '') . ($mountain->province ?? '')),
                'cover_image' => $mountain->cover_image,
                'price' => $mountain->formatted_short_price,
                'grade' => $mountain->default_grade?->label() ?? 'Grade A – Pemula',
                'grade_badge' => $mountain->default_grade?->badgeClasses() ?? 'bg-grade-a-bg text-grade-a-text',
                'url' => route('ekspedisi.show', $mountain->slug),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mountains,
        ]);
    }

    /**
     * Menampilkan detail lengkap produk ekspedisi gunung.
     */
    public function show(string $slug): View
    {
        $mountainModel = Mountain::where('slug', $slug)
            ->with(['routes', 'priceTiers', 'meetingPoints', 'expeditions', 'primaryRoute'])
            ->first();

        if ($mountainModel === null) {
            abort(404);
        }

        $expedition = $this->buildExpeditionDataFromMountain($mountainModel);

        $expedition['price_camping_open'] = $mountainModel->base_price;
        $expedition['price_camping_private'] = $mountainModel->price_private ?? (int) round($mountainModel->base_price * 1.5);
        $expedition['price_tektok_open'] = $mountainModel->effective_price_tektok;
        $expedition['price_tektok_private'] = $mountainModel->effective_price_private_tektok;
        $expedition['price_tektok'] = $mountainModel->effective_price_tektok;
        $expedition['price_private_tektok'] = $mountainModel->effective_price_private_tektok;

        $openExpedition = $mountainModel->expeditions->where('type', 'open')->where('status', 'open')->first();
        $privateExpedition = $mountainModel->expeditions->where('type', 'private')->first();

        $addons = Addon::where('is_active', true)->get();

        if ($openExpedition) {
            $expedition['quota_current'] = $openExpedition->quota_booked;
            $expedition['quota_max'] = $openExpedition->quota_max;
            $expedition['departure_date'] = Carbon::parse($openExpedition->departure_date)->format('d/m/Y');
            $expedition['has_open_schedule'] = true;
        } else {
            $expedition['quota_current'] = 0;
            $expedition['quota_max'] = 0;
            $expedition['departure_date'] = 'Belum ada jadwal';
            $expedition['has_open_schedule'] = false;
        }

        $bookingConfig = $this->buildBookingConfig(
            mountain: $mountainModel,
            expeditionData: $expedition,
            openExpedition: $openExpedition,
            privateExpedition: $privateExpedition,
            addons: $addons
        );

        return view('customer.detail', [
            'expedition' => $expedition,
            'mountainModel' => $mountainModel,
            'openExpedition' => $openExpedition,
            'privateExpedition' => $privateExpedition,
            'addons' => $addons,
            'bookingConfig' => $bookingConfig,
        ]);
    }

    /**
     * Menyusun konfigurasi modal pemesanan (window.bookingModalConfig) untuk view secara terpusat.
     *
     * @param  array<string, mixed>  $expeditionData
     * @return array<string, mixed>
     */
    private function buildBookingConfig(
        ?Mountain $mountain,
        array $expeditionData,
        ?Expedition $openExpedition,
        ?Expedition $privateExpedition,
        mixed $addons
    ): array {
        $user = auth()->user();
        $primaryRoute = $mountain?->primaryRoute ?? $mountain?->routes->first();

        $openDepartureDate = $openExpedition?->departure_date ? Carbon::parse($openExpedition->departure_date) : null;
        $openReturnDate = $openExpedition?->return_date
            ? Carbon::parse($openExpedition->return_date)
            : ($openDepartureDate ? $openDepartureDate->copy()->addDays(1) : null);

        $privateDepartureDate = $privateExpedition?->departure_date ? Carbon::parse($privateExpedition->departure_date) : null;
        $privateReturnDate = $privateExpedition?->return_date
            ? Carbon::parse($privateExpedition->return_date)
            : ($privateDepartureDate ? $privateDepartureDate->copy()->addDays(1) : null);

        $hasOpenSchedule = $openExpedition !== null;
        $defaultTripType = $hasOpenSchedule ? 'open' : ($mountain?->has_private_trip ? 'private' : 'open');

        return [
            'isLoggedIn' => $user !== null,
            'loginUrl' => route('login'),
            'defaultTripType' => $defaultTripType,
            'hasOpenSchedule' => $hasOpenSchedule,
            'hasPrivateTrip' => (bool) ($mountain?->has_private_trip ?? false),
            'openExpeditionId' => $openExpedition?->id,
            'privateExpeditionId' => $privateExpedition?->id ?? ($mountain?->expeditions->firstWhere('type', 'private')?->id ?? null),
            'routeId' => $primaryRoute?->id ?? 1,
            'routes' => $mountain?->routes?->toArray() ?? [],
            'priceTiers' => $mountain?->priceTiers?->toArray() ?? [],
            'meetingPoints' => $mountain?->meetingPoints?->toArray() ?? [],
            'addons' => $addons instanceof Collection ? $addons->toArray() : ($addons ?? []),
            'bookingFeePerPax' => $mountain?->booking_fee_per_pax ?? 150000,
            'basePrice' => $primaryRoute?->effective_price_camping_open ?? ($mountain?->base_price ?? ($expeditionData['price'] ?? 500000)),
            'pricePrivate' => $primaryRoute?->effective_price_camping_private ?? ($mountain?->price_private ?? ($expeditionData['price_camping_private'] ?? 750000)),
            'priceTektok' => $primaryRoute?->effective_price_tektok_open ?? ($mountain?->effective_price_tektok ?? ($expeditionData['price_tektok_open'] ?? 400000)),
            'pricePrivateTektok' => $primaryRoute?->effective_price_tektok_private ?? ($mountain?->effective_price_private_tektok ?? ($expeditionData['price_tektok_private'] ?? 650000)),
            'maxQuota' => $openExpedition?->quota_max ?? 0,
            'availableQuota' => $openExpedition ? max(0, (int) $openExpedition->quota_max - (int) $openExpedition->quota_booked) : 0,
            'openQuotaBooked' => $openExpedition?->quota_booked ?? 0,
            'openQuotaMax' => $openExpedition?->quota_max ?? 0,
            'durationDays' => $mountain?->duration_days ?? 2,
            'durationNights' => $mountain?->duration_nights ?? 1,
            'minPrivateDate' => now()->addDays(1)->toDateString(),
            'defaultPrivateDate' => $privateDepartureDate?->toDateString() ?? now()->addDays(7)->toDateString(),
            'departureDateOpenShort' => $openDepartureDate?->format('d/m/Y') ?? 'Belum ada jadwal',
            'departureDateOpenFull' => $openDepartureDate?->translatedFormat('d F Y') ?? 'Belum ada jadwal',
            'returnDateOpenFull' => $openReturnDate?->translatedFormat('d F Y') ?? 'Belum ada jadwal',
            'departureDatePrivateShort' => $privateDepartureDate?->format('d/m/Y') ?? 'Bebas Pilih',
            'departureDatePrivateFull' => $privateDepartureDate?->translatedFormat('d F Y') ?? 'Bebas Pilih Tanggal',
            'returnDatePrivateFull' => $privateReturnDate?->translatedFormat('d F Y') ?? 'Sesuai Durasi Trip',
            'authCustomer' => [
                'name' => $user?->name ?? 'Pendaki MiddleTrip',
                'email' => $user?->email ?? 'pendaki@middletrip.com',
                'phone' => $user?->phone ?? '081234567890',
                'nik' => $user?->nik ?? '3301234567890001',
            ],
        ];
    }

    /**
     * Membangun dataset ekspedisi dinamis dari model Mountain database.
     *
     * @return array<string, mixed>
     */
    private function buildExpeditionDataFromMountain(Mountain $mountain): array
    {
        $primaryRoute = $mountain->primaryRoute ?? $mountain->routes->first();
        $gradeVal = $primaryRoute?->grade?->value ?? ($mountain->default_grade?->value ?? 'Grade A');
        $gradeLabel = match ($gradeVal) {
            'Grade B' => 'Grade B – Menengah',
            'Grade C' => 'Grade C – Ahli',
            default => 'Grade A – Pemula',
        };
        $difficultyBadge = match ($gradeVal) {
            'Grade B' => 'Jalur Menengah',
            'Grade C' => 'Tantangan Ekstrem',
            default => 'Cocok utk Pemula',
        };

        $openExpedition = $mountain->expeditions->where('type', 'open')->where('status', 'open')->first();
        $hasOpenSchedule = $openExpedition !== null;
        $departureDate = $openExpedition?->departure_date
            ? Carbon::parse($openExpedition->departure_date)->format('d/m/Y')
            : 'Belum ada jadwal';

        $coverUrl = $mountain->cover_image ?: 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=1200&auto=format&fit=crop';

        $routesData = [];
        if ($mountain->routes->isNotEmpty()) {
            foreach ($mountain->routes as $index => $r) {
                $rGradeVal = $r->grade?->value ?? $r->grade ?? 'Grade A';
                $waterStat = ! empty($r->elevation_checkpoints['water_note'])
                    ? Str::limit($r->elevation_checkpoints['water_note'], 20)
                    : (! empty($mountain->elevation_checkpoints['water_note']) ? Str::limit($mountain->elevation_checkpoints['water_note'], 20) : 'Pos Air Terakhir');

                $routesData[] = [
                    'id' => $r->id,
                    'slug' => $r->slug ?: 'via-'.$r->id,
                    'name' => $r->name,
                    'badge' => $r->is_primary ? 'Rekomendasi Utama' : $rGradeVal,
                    'grade' => $rGradeVal,
                    'grade_label' => match ($rGradeVal) {
                        'Grade A' => 'Grade A - Jalur Tertata',
                        'Grade B' => 'Grade B - Jalur Sedang',
                        'Grade C' => 'Grade C - Jalur Berat',
                        default => $rGradeVal,
                    },
                    'selected' => $r->is_primary || $index === 0,
                    'price_camping_open' => $r->effective_price_camping_open,
                    'price_tektok_open' => $r->effective_price_tektok_open,
                    'price_camping_private' => $r->effective_price_camping_private,
                    'price_tektok_private' => $r->effective_price_tektok_private,
                    'stats' => [
                        ['label' => 'Jarak Total', 'value' => ($r->distance_km ? $r->distance_km.' km' : '— km'), 'icon' => 'milestone'],
                        ['label' => 'Durasi Waktu', 'value' => ($r->duration_hours ?? '— Jam'), 'icon' => 'clock'],
                        ['label' => 'Suhu Rata-rata', 'value' => '8–16°C', 'icon' => 'thermometer'],
                        ['label' => 'Sumber Air', 'value' => $waterStat, 'icon' => 'droplet'],
                    ],
                    'elevation_profile' => $this->buildElevationProfile($mountain, $r->name, $r->elevation_checkpoints),
                    'itinerary' => $this->buildRouteItinerary($r),
                ];
            }
        }

        $overview = $mountain->overview ?: ($mountain->description ?: ('Gunung '.$mountain->name.' berketinggian '.number_format($mountain->elevation, 0, ',', '.').' mdpl berlokasi di '.$mountain->province.'. Rasakan pengalaman pendakian spektakuler dengan pendampingan guide profesional bersertifikasi MiddleTrip.'));

        $facilitiesIncluded = ! empty($mountain->facilities_included) && is_array($mountain->facilities_included)
            ? $mountain->facilities_included
            : [
                'Akomodasi Camp' => [
                    'Tenda Kapasitas Fleksibel',
                    'Common Area (Flysheet & Camp Lamp)',
                    'Peralatan Masak & Gas (Kompor / Nesting)',
                    'Set Alat Makan & Minum',
                ],
                'Perizinan & Keamanan' => [
                    'Tiket Masuk & SIMAKSI Resmi',
                    'Asuransi Pendakian Resmi',
                ],
                'Perlengkapan Personal' => [
                    'Sleeping Bag / Warm Polar',
                    'Matras Busa',
                    'Jas Hujan & Emergency Blanket',
                    'P3K Standar Pendakian',
                ],
            ];

        $facilitiesExcluded = ! empty($mountain->facilities_excluded) && is_array($mountain->facilities_excluded)
            ? $mountain->facilities_excluded
            : [
                'Kebutuhan & Perlengkapan Pribadi' => [
                    'Pakaian & Sepatu Pendakian Pribadi',
                    'Obat-obatan Pribadi Khusus',
                ],
                'Transportasi & Akses Awal' => [
                    'Transportasi Kota Asal ke Meeting Point',
                ],
            ];

        $galleryItems = [
            ['url' => $coverUrl, 'caption' => 'Cover '.$mountain->name],
        ];

        if (! empty($mountain->gallery) && is_array($mountain->gallery)) {
            foreach ($mountain->gallery as $item) {
                if (! empty($item['url'])) {
                    $galleryItems[] = [
                        'url' => $item['url'],
                        'caption' => ! empty($item['caption']) ? $item['caption'] : 'Pemandangan '.$mountain->name,
                    ];
                }
            }
        }

        return [
            'id' => $mountain->id,
            'slug' => $mountain->slug,
            'title' => $mountain->name.' Expedition',
            'mountain' => $mountain->name,
            'elevation' => number_format($mountain->elevation, 0, ',', '.').' mdpl',
            'difficulty_badge' => $difficultyBadge,
            'location' => $mountain->province,
            'grade' => $gradeVal,
            'grade_label' => $gradeLabel,
            'type' => $mountain->has_open_trip ? 'open' : 'private',
            'type_label' => $mountain->has_open_trip ? 'Open Trip' : 'Private Trip',
            'price' => $primaryRoute?->effective_price_camping_open ?? $mountain->base_price,
            'price_formatted' => 'Rp '.number_format($primaryRoute?->effective_price_camping_open ?? $mountain->base_price, 0, ',', '.'),
            'price_tektok' => $primaryRoute?->effective_price_tektok_open ?? (int) ($mountain->base_price * 0.8),
            'price_private' => $primaryRoute?->effective_price_camping_private ?? ($mountain->price_private ?? (int) ($mountain->base_price * 1.5)),
            'price_private_tektok' => $primaryRoute?->effective_price_tektok_private ?? (int) (($mountain->price_private ?? ($mountain->base_price * 1.5)) * 0.8),
            'quota_current' => $openExpedition?->quota_booked ?? 0,
            'quota_max' => $openExpedition?->quota_max ?? 0,
            'departure_date' => $departureDate,
            'has_open_schedule' => $hasOpenSchedule,
            'image' => $coverUrl,
            'description' => $mountain->description ?: ('Jelajahi keindahan '.$mountain->name.' bersama tim profesional MiddleTrip.'),
            'overview' => $overview,
            'gallery' => $galleryItems,
            'stats' => $routesData[0]['stats'] ?? [],
            'elevation_profile' => $routesData[0]['elevation_profile'] ?? (! empty($mountain->elevation_checkpoints['points']) ? $this->buildElevationProfile($mountain, $mountain->name, $mountain->elevation_checkpoints) : null),
            'routes' => $routesData,
            'itinerary' => $routesData[0]['itinerary'] ?? null,
            'facilities' => [
                'included' => $facilitiesIncluded,
                'excluded' => $facilitiesExcluded,
            ],
        ];
    }

    /**
     * Membangun dataset itinerary per rute.
     *
     * @return array<string, mixed>
     */
    private function buildRouteItinerary(?Route $route, string $defaultName = 'Jalur Utama'): array
    {
        $routeName = $route?->name ?? $defaultName;
        $itinData = $route?->itinerary;

        // 1. Tentukan Itinerary Camping
        if ($itinData && ! empty($itinData['camping']) && is_array($itinData['camping'])) {
            $camping = $itinData['camping'];
        } elseif ($itinData && ! empty($itinData['days']) && is_array($itinData['days'])) {
            $daysCount = count($itinData['days']);
            $durationLabel = $itinData['duration_label'] ?? ($daysCount === 1 ? '1D (Tek-tok)' : "{$daysCount}D".($daysCount - 1).'N');
            $camping = [
                'title' => $itinData['title'] ?? ("Itinerary {$durationLabel} ({$routeName})"),
                'duration_label' => $durationLabel,
                'days_count' => $daysCount,
                'days' => $itinData['days'],
            ];
        } else {
            $camping = [
                'title' => 'Itinerary 2D1N ('.$routeName.')',
                'duration_label' => '2D1N',
                'days_count' => 2,
                'days' => [
                    [
                        'day' => 'Day 1',
                        'title' => 'Day 1: Basecamp ke Camp Area',
                        'description' => 'Mulai pendakian dari Basecamp '.$routeName.' melewati perkebunan dan vegetasi hutan, beristirahat di pos tengah dan mendirikan tenda di camp area.',
                        'timeline' => [
                            ['time' => '08:00', 'activity' => 'Registrasi & Persiapan di Basecamp'],
                            ['time' => '09:00', 'activity' => 'Mulai Trekking Menuju Pos 1 & 2'],
                            ['time' => '12:30', 'activity' => 'Makan Siang & Istirahat di Pos Tengah'],
                            ['time' => '16:00', 'activity' => 'Tiba di Camp Area & Dirikan Tenda'],
                        ],
                    ],
                    [
                        'day' => 'Day 2',
                        'title' => 'Day 2: Summit Push & Turun Kembali',
                        'description' => 'Bangun dini hari untuk summit push menikmati sunrise di puncak tertinggi, sarapan hangat, lalu berkemas turun kembali ke basecamp.',
                        'timeline' => [
                            ['time' => '03:30', 'activity' => 'Summit Push Menuju Puncak'],
                            ['time' => '05:30', 'activity' => 'Sunrise Spektakuler di Puncak'],
                            ['time' => '08:00', 'activity' => 'Kembali ke Camp, Sarapan & Packing'],
                            ['time' => '10:00', 'activity' => 'Perjalanan Turun Menuju Basecamp'],
                            ['time' => '14:00', 'activity' => 'Tiba di Basecamp & Penutupan Trip'],
                        ],
                    ],
                ],
            ];
        }

        // 2. Tentukan Itinerary Tek-tok
        if ($itinData && ! empty($itinData['tektok']) && is_array($itinData['tektok'])) {
            $tektok = $itinData['tektok'];
        } else {
            $tektok = [
                'title' => 'Itinerary 1D Tek-tok ('.$routeName.')',
                'duration_label' => '1D (Tek-tok)',
                'days_count' => 1,
                'days' => [
                    [
                        'day' => 'Day 1',
                        'title' => 'Trekking Tek-tok 1 Hari (Langsung Turun)',
                        'description' => 'Pendakian cepat langsung turun dalam 1 hari tanpa bermalam di tenda. Memerlukan fisik prima dan ritme trekking yang teratur.',
                        'timeline' => [
                            ['time' => '00:00', 'activity' => 'Registrasi, Cek Logistik & Briefing di Basecamp'],
                            ['time' => '01:00', 'activity' => 'Mulai Trekking Dini Hari Menuju Pos 1 & Pos 2'],
                            ['time' => '03:30', 'activity' => 'Istirahat & Rehidrasi di Pos Tengah / Sabana'],
                            ['time' => '05:30', 'activity' => 'Tiba di Puncak, Menikmati Sunrise Spektakuler'],
                            ['time' => '07:30', 'activity' => 'Sesi Dokumentasi & Mulai Perjalanan Turun'],
                            ['time' => '12:00', 'activity' => 'Tiba Kembali di Basecamp & Penutupan Trip'],
                        ],
                    ],
                ],
            ];
        }

        return [
            'title' => $camping['title'],
            'duration_label' => $camping['duration_label'],
            'days_count' => $camping['days_count'],
            'days' => $camping['days'],
            'camping' => $camping,
            'tektok' => $tektok,
        ];
    }

    /**
     * Membangun SVG grafik profil elevasi dan catatan pos berdasarkan checkpoint database.
     *
     * @param  array<string, mixed>|null  $routeCheckpoints
     * @return array<string, mixed>
     */
    private function buildElevationProfile(Mountain $mountain, string $routeName, ?array $routeCheckpoints = null): array
    {
        $data = (! empty($routeCheckpoints) && ! empty($routeCheckpoints['points']))
            ? $routeCheckpoints
            : $mountain->elevation_checkpoints;

        $pointsRaw = ! empty($data['points']) && is_array($data['points']) ? $data['points'] : null;

        if (empty($pointsRaw) || count($pointsRaw) < 2) {
            $baseElev = (int) max(500, $mountain->elevation * 0.45);
            $summitElev = (int) $mountain->elevation;
            $step = ($summitElev - $baseElev) / 4;

            $pointsRaw = [
                ['name' => 'Basecamp', 'elevation' => $baseElev],
                ['name' => 'Pos 1', 'elevation' => (int) round($baseElev + ($step * 1))],
                ['name' => 'Pos 2', 'elevation' => (int) round($baseElev + ($step * 2))],
                ['name' => 'Pos 3', 'elevation' => (int) round($baseElev + ($step * 3))],
                ['name' => 'Puncak', 'elevation' => $summitElev],
            ];
        }

        $waterNote = $data['water_note'] ?? 'Pos Tengah (Sumber Air Terakhir)';
        $windNote = $data['wind_note'] ?? 'Waspada terpaan angin kencang di punggungan';
        $signalNote = $data['signal_note'] ?? 'Stabil di Basecamp & Pos 1';

        $count = count($pointsRaw);
        $elevations = array_map(fn ($p) => (int) $p['elevation'], $pointsRaw);
        $minElev = min($elevations);
        $maxElev = max($elevations);
        $elevRange = max(1, $maxElev - $minElev);

        $points = [];
        $svgPoints = [];
        foreach ($pointsRaw as $i => $pt) {
            $x = (int) round(40 + ($i * (610 / max(1, $count - 1))));
            $norm = ((int) $pt['elevation'] - $minElev) / $elevRange;
            $y = (int) round(190 - ($norm * 145)); // 190 di bawah, 45 di atas
            $points[] = [
                'name' => $pt['name'],
                'elevation' => number_format((int) $pt['elevation'], 0, ',', '.').'m',
                'x' => $x,
                'y' => $y,
            ];
            $svgPoints[] = "{$x} {$y}";
        }

        $path = 'M '.implode(' L ', $svgPoints);
        $lastX = $points[$count - 1]['x'];
        $firstX = $points[0]['x'];
        $area = $path." L {$lastX} 210 L {$firstX} 210 Z";

        return [
            'title' => 'Elevasi & Rute Pendakian ('.$routeName.')',
            'points' => $points,
            'path' => $path,
            'area' => $area,
            'notes' => [
                [
                    'title' => 'Titik Air:',
                    'desc' => $waterNote,
                    'type' => 'water',
                    'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                    'icon_class' => 'bg-blue-50 text-blue-600',
                ],
                [
                    'title' => 'Zona Angin:',
                    'desc' => $windNote,
                    'type' => 'wind',
                    'badge_class' => 'bg-amber-50/50 border-amber-100 text-amber-900',
                    'icon_class' => 'bg-amber-100 text-amber-600',
                ],
                [
                    'title' => 'Sinyal Seluler:',
                    'desc' => $signalNote,
                    'type' => 'signal',
                    'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                    'icon_class' => 'bg-emerald-100 text-emerald-600',
                ],
            ],
        ];
    }
}
