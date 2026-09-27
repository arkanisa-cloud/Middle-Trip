<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Mountain;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
     * Menampilkan detail lengkap produk ekspedisi gunung.
     */
    public function show(string $slug): View
    {
        $expedition = $this->findExpeditionBySlug($slug);

        if ($expedition === null) {
            abort(404);
        }

        $mountainModel = Mountain::where('slug', $slug)
            ->with(['routes', 'priceTiers', 'meetingPoints', 'expeditions'])
            ->first();

        $addons = Addon::where('is_active', true)->get();

        return view('customer.detail', [
            'expedition' => $expedition,
            'mountainModel' => $mountainModel,
            'addons' => $addons,
        ]);
    }

    /**
     * Mendapatkan daftar ringkas ekspedisi untuk halaman katalog dan beranda.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getExpeditionList(): array
    {
        return array_map(function (array $item): array {
            return [
                'id' => $item['id'],
                'slug' => $item['slug'],
                'title' => $item['title'],
                'mountain' => $item['mountain'],
                'elevation' => $item['elevation'],
                'grade' => $item['grade'],
                'grade_label' => $item['grade_label'],
                'type' => $item['type'],
                'type_label' => $item['type_label'],
                'price' => $item['price'],
                'price_formatted' => $item['price_formatted'],
                'image' => $item['image'],
                'description' => $item['description'],
            ];
        }, $this->getAllExpeditions());
    }

    /**
     * Cari detail ekspedisi berdasarkan slug.
     *
     * @return array<string, mixed>|null
     */
    private function findExpeditionBySlug(string $slug): ?array
    {
        $all = $this->getAllExpeditions();

        return $all[$slug] ?? null;
    }

    /**
     * Dataset komprehensif seluruh ekspedisi gunung MiddleTrip.
     *
     * @return array<string, array<string, mixed>>
     */
    private function getAllExpeditions(): array
    {
        return [
            'mt-merbabu' => [
                'id' => 1,
                'slug' => 'mt-merbabu',
                'title' => 'Mt. Merbabu Expedition',
                'mountain' => 'Mt. Merbabu',
                'elevation' => '3.142 mdpl',
                'difficulty_badge' => 'Cocok utk Pemula',
                'location' => 'Jawa Tengah',
                'grade' => 'Grade A',
                'grade_label' => 'Grade A – Pemula',
                'type' => 'open',
                'type_label' => 'Open Trip',
                'price' => 500000,
                'price_formatted' => 'Rp 500.000',
                'price_tektok' => 400000,
                'price_private' => 750000,
                'price_private_tektok' => 600000,
                'quota_current' => 5,
                'quota_max' => 10,
                'departure_date' => '15/06/2026',
                'image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Jalur Selo yang ramah pemula, padang sabana nan luas, dan panorama matahari terbit berlatar megahnya Gunung Merapi.',
                'overview' => 'Gunung Merbabu adalah gunung api tipe strato dengan ketinggian 3.142 mdpl. Secara administratif gunung ini berada di wilayah Kabupaten Magelang di lereng sebelah barat, Kabupaten Boyolali di lereng sebelah timur dan selatan, serta Kabupaten Semarang di lereng sebelah utara. Pendakian ini menawarkan panorama sabana yang luas dan pemandangan Gunung Merapi yang megah.',
                'gallery' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=1200&auto=format&fit=crop',
                        'caption' => 'Sabana Merbabu Ridge',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'View of Mt Merapi',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Pendaki di Padang Rumput',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Sabana Camp',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Puncak Kenteng Songo',
                    ],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '13.8 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '6–7 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '8–15°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Pos 3', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Selo)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '1.800m', 'x' => 40, 'y' => 185],
                        ['name' => 'Pos 1', 'elevation' => '2.100m', 'x' => 140, 'y' => 160],
                        ['name' => 'Pos 2', 'elevation' => '2.400m', 'x' => 250, 'y' => 145],
                        ['name' => 'Pos 3', 'elevation' => '2.600m', 'x' => 360, 'y' => 130],
                        ['name' => 'Sabana 1', 'elevation' => '2.800m', 'x' => 470, 'y' => 95],
                        ['name' => 'Sabana 2', 'elevation' => '2.950m', 'x' => 560, 'y' => 80],
                        ['name' => 'Puncak', 'elevation' => '3.142m', 'x' => 650, 'y' => 45],
                    ],
                    'path' => 'M 40 185 L 140 160 L 250 145 L 360 130 L 470 95 L 560 80 L 650 45',
                    'area' => 'M 40 185 L 140 160 L 250 145 L 360 130 L 470 95 L 560 80 L 650 45 L 650 210 L 40 210 Z',
                    'notes' => [
                        [
                            'title' => 'Titik Air Terakhir:',
                            'desc' => 'Pos 3 (Pastikan isi botol)',
                            'type' => 'water',
                            'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                            'icon_class' => 'bg-blue-50 text-blue-600',
                        ],
                        [
                            'title' => 'Zona Terpaan Angin:',
                            'desc' => 'Kencang Sabana 2',
                            'type' => 'wind',
                            'badge_class' => 'bg-red-50/50 border-red-100 text-red-900',
                            'icon_class' => 'bg-red-100 text-red-600',
                        ],
                        [
                            'title' => 'Sinyal Seluler:',
                            'desc' => '4G di BC & Pos 1-2',
                            'type' => 'signal',
                            'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                            'icon_class' => 'bg-emerald-100 text-emerald-600',
                        ],
                    ],
                ],
                'routes' => [
                    [
                        'id' => 'selo',
                        'name' => 'Via Selo',
                        'badge' => 'Rekomendasi Utama',
                        'selected' => true,
                        'stats' => [
                            ['label' => 'Jarak Total', 'value' => '13.8 km', 'icon' => 'milestone'],
                            ['label' => 'Durasi Waktu', 'value' => '6–7 Jam', 'icon' => 'clock'],
                            ['label' => 'Suhu Rata-rata', 'value' => '8–15°C', 'icon' => 'thermometer'],
                            ['label' => 'Sumber Air', 'value' => 'Pos 3', 'icon' => 'droplet'],
                        ],
                        'elevation_profile' => [
                            'title' => 'Elevasi & Rute Pendakian (Via Selo)',
                            'points' => [
                                ['name' => 'Basecamp', 'elevation' => '1.800m', 'x' => 40, 'y' => 185],
                                ['name' => 'Pos 1', 'elevation' => '2.100m', 'x' => 140, 'y' => 160],
                                ['name' => 'Pos 2', 'elevation' => '2.400m', 'x' => 250, 'y' => 145],
                                ['name' => 'Pos 3', 'elevation' => '2.600m', 'x' => 360, 'y' => 130],
                                ['name' => 'Sabana 1', 'elevation' => '2.800m', 'x' => 470, 'y' => 95],
                                ['name' => 'Sabana 2', 'elevation' => '2.950m', 'x' => 560, 'y' => 80],
                                ['name' => 'Puncak', 'elevation' => '3.142m', 'x' => 650, 'y' => 45],
                            ],
                            'path' => 'M 40 185 L 140 160 L 250 145 L 360 130 L 470 95 L 560 80 L 650 45',
                            'area' => 'M 40 185 L 140 160 L 250 145 L 360 130 L 470 95 L 560 80 L 650 45 L 650 210 L 40 210 Z',
                            'notes' => [
                                [
                                    'title' => 'Titik Air Terakhir:',
                                    'desc' => 'Pos 3 (Pastikan isi botol)',
                                    'type' => 'water',
                                    'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                                    'icon_class' => 'bg-blue-50 text-blue-600',
                                ],
                                [
                                    'title' => 'Zona Terpaan Angin:',
                                    'desc' => 'Kencang Sabana 2',
                                    'type' => 'wind',
                                    'badge_class' => 'bg-red-50/50 border-red-100 text-red-900',
                                    'icon_class' => 'bg-red-100 text-red-600',
                                ],
                                [
                                    'title' => 'Sinyal Seluler:',
                                    'desc' => '4G di BC & Pos 1-2',
                                    'type' => 'signal',
                                    'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                                    'icon_class' => 'bg-emerald-100 text-emerald-600',
                                ],
                            ],
                        ],
                        'itinerary' => [
                            'title' => 'Itinerary 2D1N (Via Selo)',
                            'days' => [
                                [
                                    'day' => 'Day 1',
                                    'title' => 'Day 1: Basecamp ke Sabana (Camp)',
                                    'description' => 'Memulai pendakian dari Basecamp Selo melewati hutan pinus dan lamtoro. Istirahat dan makan siang di Pos 2 sebelum melanjutkan ke Sabana untuk mendirikan tenda.',
                                    'timeline' => [
                                        ['time' => '08:00', 'activity' => 'Registrasi & Persiapan di Basecamp Selo'],
                                        ['time' => '09:00', 'activity' => 'Mulai Trekking Menuju Pos 1 & 2'],
                                        ['time' => '12:30', 'activity' => 'Makan Siang di Pos 2'],
                                        ['time' => '16:00', 'activity' => 'Tiba di Sabana 1, Dirikan Tenda & Sunset'],
                                    ],
                                ],
                                [
                                    'day' => 'Day 2',
                                    'title' => 'Day 2: Summit Push & Descent',
                                    'description' => 'Bangun dini hari untuk muncak dan menikmati sunrise di Puncak Kenteng Songo. Setelah sarapan, turun kembali ke basecamp.',
                                    'timeline' => [
                                        ['time' => '03:30', 'activity' => 'Persiapan Summit Attack Menuju Puncak'],
                                        ['time' => '05:30', 'activity' => 'Sunrise Spektakuler di Puncak Kenteng Songo'],
                                        ['time' => '08:00', 'activity' => 'Kembali ke Camp, Sarapan & Packing'],
                                        ['time' => '10:00', 'activity' => 'Perjalanan Turun Menuju Basecamp Selo'],
                                        ['time' => '14:00', 'activity' => 'Tiba di Basecamp & Penutupan Ekspedisi'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 'suwanting',
                        'name' => 'Via Suwanting',
                        'badge' => 'Jalur Sabana Curam',
                        'selected' => false,
                        'stats' => [
                            ['label' => 'Jarak Total', 'value' => '14.5 km', 'icon' => 'milestone'],
                            ['label' => 'Durasi Waktu', 'value' => '8–9 Jam', 'icon' => 'clock'],
                            ['label' => 'Suhu Rata-rata', 'value' => '7–14°C', 'icon' => 'thermometer'],
                            ['label' => 'Sumber Air', 'value' => 'Pos 2', 'icon' => 'droplet'],
                        ],
                        'elevation_profile' => [
                            'title' => 'Elevasi & Rute Pendakian (Via Suwanting)',
                            'points' => [
                                ['name' => 'Basecamp', 'elevation' => '1.280m', 'x' => 40, 'y' => 195],
                                ['name' => 'Pos 1', 'elevation' => '1.650m', 'x' => 140, 'y' => 170],
                                ['name' => 'Pos 2', 'elevation' => '2.200m', 'x' => 250, 'y' => 140],
                                ['name' => 'Pos 3', 'elevation' => '2.750m', 'x' => 370, 'y' => 100],
                                ['name' => 'Sabana', 'elevation' => '2.900m', 'x' => 480, 'y' => 75],
                                ['name' => 'Triangulasi', 'elevation' => '3.138m', 'x' => 570, 'y' => 52],
                                ['name' => 'Kenteng Songo', 'elevation' => '3.142m', 'x' => 650, 'y' => 45],
                            ],
                            'path' => 'M 40 195 L 140 170 L 250 140 L 370 100 L 480 75 L 570 52 L 650 45',
                            'area' => 'M 40 195 L 140 170 L 250 140 L 370 100 L 480 75 L 570 52 L 650 45 L 650 210 L 40 210 Z',
                            'notes' => [
                                [
                                    'title' => 'Titik Air Terakhir:',
                                    'desc' => 'Pos 2 Lembah Mitigasi (Melimpah)',
                                    'type' => 'water',
                                    'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                                    'icon_class' => 'bg-blue-50 text-blue-600',
                                ],
                                [
                                    'title' => 'Zona Tanjakan Ekstrem:',
                                    'desc' => 'Tanjakan Cendani & Sabana Terbuka',
                                    'type' => 'wind',
                                    'badge_class' => 'bg-red-50/50 border-red-100 text-red-900',
                                    'icon_class' => 'bg-red-100 text-red-600',
                                ],
                                [
                                    'title' => 'Sinyal Seluler:',
                                    'desc' => 'Tersedia di Basecamp & Puncak',
                                    'type' => 'signal',
                                    'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                                    'icon_class' => 'bg-emerald-100 text-emerald-600',
                                ],
                            ],
                        ],
                        'itinerary' => [
                            'title' => 'Itinerary 2D1N (Via Suwanting)',
                            'days' => [
                                [
                                    'day' => 'Day 1',
                                    'title' => 'Day 1: Basecamp Suwanting ke Sabana Indah (Camp)',
                                    'description' => 'Mendaki jalur barat Magelang yang menantang menembus hutan pinus, tanjakan pipa, dan Tanjakan Cendani menuju Sabana.',
                                    'timeline' => [
                                        ['time' => '07:30', 'activity' => 'Registrasi di Basecamp Suwanting'],
                                        ['time' => '08:30', 'activity' => 'Trekking menembus Hutan Lamtoro ke Pos 1'],
                                        ['time' => '12:00', 'activity' => 'Istirahat & Isi Air di Pos 2 Lembah Mitigasi'],
                                        ['time' => '16:30', 'activity' => 'Tiba di Sabana Suwanting & Pasang Tenda'],
                                    ],
                                ],
                                [
                                    'day' => 'Day 2',
                                    'title' => 'Day 2: Muncak Triangulasi & Kenteng Songo',
                                    'description' => 'Mendaki bukit sabana menuju Puncak Triangulasi dan Puncak Kenteng Songo saat fajar.',
                                    'timeline' => [
                                        ['time' => '03:45', 'activity' => 'Summit Push menyusuri Punggung Sabana'],
                                        ['time' => '05:40', 'activity' => 'Sunrise di Puncak Triangulasi & Kenteng Songo'],
                                        ['time' => '08:30', 'activity' => 'Kembali ke Camp & Sarapan Pagi'],
                                        ['time' => '10:30', 'activity' => 'Perjalanan Turun ke Basecamp Suwanting'],
                                        ['time' => '15:30', 'activity' => 'Tiba di Basecamp, Mandi & Istirahat'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 'thekelan',
                        'name' => 'Via Thekelan',
                        'badge' => 'Jalur Tebing & Sejarah',
                        'selected' => false,
                        'stats' => [
                            ['label' => 'Jarak Total', 'value' => '15.0 km', 'icon' => 'milestone'],
                            ['label' => 'Durasi Waktu', 'value' => '7–8 Jam', 'icon' => 'clock'],
                            ['label' => 'Suhu Rata-rata', 'value' => '8–14°C', 'icon' => 'thermometer'],
                            ['label' => 'Sumber Air', 'value' => 'Pos 2', 'icon' => 'droplet'],
                        ],
                        'elevation_profile' => [
                            'title' => 'Elevasi & Rute Pendakian (Via Thekelan)',
                            'points' => [
                                ['name' => 'Basecamp', 'elevation' => '1.600m', 'x' => 40, 'y' => 190],
                                ['name' => 'Pos 1', 'elevation' => '1.900m', 'x' => 140, 'y' => 165],
                                ['name' => 'Pos 2', 'elevation' => '2.250m', 'x' => 250, 'y' => 140],
                                ['name' => 'Pos 3', 'elevation' => '2.500m', 'x' => 360, 'y' => 120],
                                ['name' => 'Pos 4', 'elevation' => '2.850m', 'x' => 460, 'y' => 90],
                                ['name' => 'Pemancar', 'elevation' => '3.050m', 'x' => 560, 'y' => 65],
                                ['name' => 'Puncak', 'elevation' => '3.142m', 'x' => 650, 'y' => 45],
                            ],
                            'path' => 'M 40 190 L 140 165 L 250 140 L 360 120 L 460 90 L 560 65 L 650 45',
                            'area' => 'M 40 190 L 140 165 L 250 140 L 360 120 L 460 90 L 560 65 L 650 45 L 650 210 L 40 210 Z',
                            'notes' => [
                                [
                                    'title' => 'Titik Air Terakhir:',
                                    'desc' => 'Pos 2 (Bak Penampungan Air)',
                                    'type' => 'water',
                                    'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                                    'icon_class' => 'bg-blue-50 text-blue-600',
                                ],
                                [
                                    'title' => 'Zona Tebing Batu:',
                                    'desc' => 'Watu Tulis & Punggung Kawah',
                                    'type' => 'wind',
                                    'badge_class' => 'bg-red-50/50 border-red-100 text-red-900',
                                    'icon_class' => 'bg-red-100 text-red-600',
                                ],
                                [
                                    'title' => 'Sinyal Seluler:',
                                    'desc' => 'Sangat bagus di Puncak Pemancar',
                                    'type' => 'signal',
                                    'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                                    'icon_class' => 'bg-emerald-100 text-emerald-600',
                                ],
                            ],
                        ],
                        'itinerary' => [
                            'title' => 'Itinerary 2D1N (Via Thekelan)',
                            'days' => [
                                [
                                    'day' => 'Day 1',
                                    'title' => 'Day 1: Basecamp Thekelan ke Pos 4 Camp',
                                    'description' => 'Pendakian jalur legendaris tertua dari lereng utara Kopeng melewati perkebunan sayur dan hutan pinus.',
                                    'timeline' => [
                                        ['time' => '08:00', 'activity' => 'Briefing di Basecamp Thekelan'],
                                        ['time' => '09:00', 'activity' => 'Mulai mendaki melewati Pos 1 & Pos 2'],
                                        ['time' => '12:30', 'activity' => 'Makan siang & istirahat di Pos 3'],
                                        ['time' => '16:00', 'activity' => 'Tiba di Camp Pos 4 / Watu Tulis'],
                                    ],
                                ],
                                [
                                    'day' => 'Day 2',
                                    'title' => 'Day 2: Menembus Puncak Pemancar ke Puncak Sejati',
                                    'description' => 'Menyeberangi punggungan tebing eksotis kawah mati menuju puncak tertinggi.',
                                    'timeline' => [
                                        ['time' => '04:00', 'activity' => 'Summit attack ke Puncak Pemancar & Syarif'],
                                        ['time' => '06:00', 'activity' => 'Menikmati lautan awan di Kenteng Songo'],
                                        ['time' => '08:30', 'activity' => 'Turun kembali ke tenda & makan pagi'],
                                        ['time' => '10:30', 'activity' => 'Perjalanan turun ke Basecamp Thekelan'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 'wekas',
                        'name' => 'Via Wekas',
                        'badge' => 'Sumber Air Melimpah',
                        'selected' => false,
                        'stats' => [
                            ['label' => 'Jarak Total', 'value' => '12.0 km', 'icon' => 'milestone'],
                            ['label' => 'Durasi Waktu', 'value' => '6–7 Jam', 'icon' => 'clock'],
                            ['label' => 'Suhu Rata-rata', 'value' => '9–15°C', 'icon' => 'thermometer'],
                            ['label' => 'Sumber Air', 'value' => 'Pos 2 (Sungai)', 'icon' => 'droplet'],
                        ],
                        'elevation_profile' => [
                            'title' => 'Elevasi & Rute Pendakian (Via Wekas)',
                            'points' => [
                                ['name' => 'Basecamp', 'elevation' => '1.700m', 'x' => 40, 'y' => 185],
                                ['name' => 'Pos 1', 'elevation' => '1.950m', 'x' => 150, 'y' => 160],
                                ['name' => 'Pos 2', 'elevation' => '2.400m', 'x' => 280, 'y' => 130],
                                ['name' => 'Pos Kawah', 'elevation' => '2.700m', 'x' => 420, 'y' => 100],
                                ['name' => 'Pertemuan', 'elevation' => '2.900m', 'x' => 540, 'y' => 75],
                                ['name' => 'Puncak', 'elevation' => '3.142m', 'x' => 650, 'y' => 45],
                            ],
                            'path' => 'M 40 185 L 150 160 L 280 130 L 420 100 L 540 75 L 650 45',
                            'area' => 'M 40 185 L 150 160 L 280 130 L 420 100 L 540 75 L 650 45 L 650 210 L 40 210 Z',
                            'notes' => [
                                [
                                    'title' => 'Sumber Air:',
                                    'desc' => 'Pos 2 (Paling Melimpah & Ada Pipa Alami)',
                                    'type' => 'water',
                                    'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                                    'icon_class' => 'bg-blue-50 text-blue-600',
                                ],
                                [
                                    'title' => 'Zona Kawah Mati:',
                                    'desc' => 'Jalur Bebatuan & Tanah Berpasir',
                                    'type' => 'wind',
                                    'badge_class' => 'bg-amber-50/50 border-amber-100 text-amber-900',
                                    'icon_class' => 'bg-amber-100 text-amber-600',
                                ],
                                [
                                    'title' => 'Sinyal Seluler:',
                                    'desc' => 'Stabil di Basecamp & Pos 1',
                                    'type' => 'signal',
                                    'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                                    'icon_class' => 'bg-emerald-100 text-emerald-600',
                                ],
                            ],
                        ],
                        'itinerary' => [
                            'title' => 'Itinerary 2D1N (Via Wekas)',
                            'days' => [
                                [
                                    'day' => 'Day 1',
                                    'title' => 'Day 1: Basecamp Wekas ke Pos 2 Camp (Air Terjun)',
                                    'description' => 'Pendakian relatif pendek menuju Pos 2 yang terkenal sebagai tempat camp ternyaman dengan sumber air melimpah.',
                                    'timeline' => [
                                        ['time' => '09:00', 'activity' => 'Registrasi di Basecamp Wekas'],
                                        ['time' => '10:00', 'activity' => 'Trekking melewati ladang wortel dan kol'],
                                        ['time' => '13:30', 'activity' => 'Tiba di Pos 2 Wekas, pasang tenda santai'],
                                        ['time' => '16:00', 'activity' => 'Eksplorasi aliran air alami & api unggun malam'],
                                    ],
                                ],
                                [
                                    'day' => 'Day 2',
                                    'title' => 'Day 2: Menuju Puncak via Bibir Kawah',
                                    'description' => 'Mendaki dini hari melintasi jalur kawah mati dan pertemuan jalur Selo menuju Puncak Kenteng Songo.',
                                    'timeline' => [
                                        ['time' => '03:30', 'activity' => 'Summit push melintasi kawah mati'],
                                        ['time' => '05:45', 'activity' => 'Golden sunrise di Puncak Triangulasi'],
                                        ['time' => '08:30', 'activity' => 'Kembali ke Pos 2 & sarapan hangat'],
                                        ['time' => '11:00', 'activity' => 'Perjalanan turun santai ke Basecamp Wekas'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Selo)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Sabana (Camp)',
                            'description' => 'Memulai pendakian dari Basecamp Selo melewati hutan pinus dan lamtoro. Istirahat dan makan siang di Pos 2 sebelum melanjutkan ke Sabana untuk mendirikan tenda.',
                            'timeline' => [
                                ['time' => '08:00', 'activity' => 'Registrasi & Persiapan'],
                                ['time' => '09:00', 'activity' => 'Mulai Trekking'],
                                ['time' => '12:30', 'activity' => 'Makan Siang di Pos 2'],
                                ['time' => '16:00', 'activity' => 'Tiba di Sabana 1, Dirikan Tenda'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Summit Push & Descent',
                            'description' => 'Bangun dini hari untuk muncak dan menikmati sunrise di Puncak Kenteng Songo. Setelah sarapan, turun kembali ke basecamp.',
                            'timeline' => [
                                ['time' => '03:30', 'activity' => 'Persiapan Summit Attack Menuju Puncak'],
                                ['time' => '05:30', 'activity' => 'Sunrise Spektakuler di Puncak Kenteng Songo'],
                                ['time' => '08:00', 'activity' => 'Kembali ke Camp, Sarapan & Packing'],
                                ['time' => '10:00', 'activity' => 'Perjalanan Turun Menuju Basecamp Selo'],
                                ['time' => '14:00', 'activity' => 'Tiba di Basecamp & Penutupan Ekspedisi'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
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
                            'Tas Carrier / Daily Pack',
                            'P3K Standar Pendakian',
                        ],
                    ],
                    'excluded' => [
                        'Kebutuhan & Perlengkapan Pribadi' => [
                            'Pakaian & Sepatu Pendakian Pribadi',
                            'Obat-obatan Pribadi Khusus',
                        ],
                        'Transportasi & Akses Awal' => [
                            'Transportasi Kota Asal ke Meeting Point',
                        ],
                    ],
                ],
            ],

            'mt-sindoro' => [
                'id' => 2,
                'slug' => 'mt-sindoro',
                'title' => 'Mt. Sindoro Expedition',
                'mountain' => 'Mt. Sindoro',
                'elevation' => '3.153 mdpl',
                'difficulty_badge' => 'Jalur Berbatu Vulkanik',
                'location' => 'Jawa Tengah',
                'grade' => 'Grade B',
                'grade_label' => 'Grade B – Menengah',
                'type' => 'open',
                'type_label' => 'Open Trip',
                'price' => 650000,
                'price_formatted' => 'Rp 650.000',
                'price_tektok' => 500000,
                'price_private' => 900000,
                'price_private_tektok' => 750000,
                'quota_current' => 6,
                'quota_max' => 12,
                'departure_date' => '22/06/2026',
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=900&auto=format&fit=crop',
                'description' => 'Trek bebatuan yang kokoh menuju kawah aktif dan lautan awan yang menakjubkan.',
                'overview' => 'Gunung Sindoro berdiri kokoh di dataran tinggi Kledung, Temanggung. Jalur Kledung menyajikan medan berbatu khas gunung vulkanik aktif dengan kawah belerang eksotis di puncaknya dan lanskap pemandangan kembarannya, Gunung Sumbing.',
                'gallery' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop',
                        'caption' => 'Kawah Aktif Sindoro',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Sunrise di Kledung',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Lautan Awan Sindoro',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Camp Pos 3 Sindoro',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=600&auto=format&fit=crop',
                        'caption' => 'Puncak Sejati 3.153 mdpl',
                    ],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '11.5 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '7–8 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '6–14°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Pos 2', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Kledung)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '1.400m', 'x' => 40, 'y' => 190],
                        ['name' => 'Pos 1', 'elevation' => '1.900m', 'x' => 150, 'y' => 165],
                        ['name' => 'Pos 2', 'elevation' => '2.300m', 'x' => 270, 'y' => 140],
                        ['name' => 'Pos 3', 'elevation' => '2.650m', 'x' => 400, 'y' => 105],
                        ['name' => 'Batu Tatah', 'elevation' => '2.900m', 'x' => 520, 'y' => 75],
                        ['name' => 'Puncak', 'elevation' => '3.153m', 'x' => 650, 'y' => 42],
                    ],
                    'path' => 'M 40 190 L 150 165 L 270 140 L 400 105 L 520 75 L 650 42',
                    'area' => 'M 40 190 L 150 165 L 270 140 L 400 105 L 520 75 L 650 42 L 650 210 L 40 210 Z',
                    'notes' => [
                        [
                            'title' => 'Titik Air Terakhir:',
                            'desc' => 'Pos 2 Kledung',
                            'type' => 'water',
                            'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                            'icon_class' => 'bg-blue-50 text-blue-600',
                        ],
                        [
                            'title' => 'Bau Belerang:',
                            'desc' => 'Wajib masker dekat kawah',
                            'type' => 'wind',
                            'badge_class' => 'bg-amber-50/50 border-amber-100 text-amber-900',
                            'icon_class' => 'bg-amber-100 text-amber-600',
                        ],
                        [
                            'title' => 'Sinyal Seluler:',
                            'desc' => 'Tersedia hingga Pos 3',
                            'type' => 'signal',
                            'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                            'icon_class' => 'bg-emerald-100 text-emerald-600',
                        ],
                    ],
                ],
                'routes' => [
                    ['id' => 'kledung', 'name' => 'Via Kledung', 'badge' => 'Jalur Terfavorit', 'selected' => true],
                    ['id' => 'sigedang', 'name' => 'Via Sigedang (Tambi)', 'badge' => 'Kebun Teh Menawan', 'selected' => false],
                    ['id' => 'bansari', 'name' => 'Via Bansari', 'badge' => 'Ojek Ramah Lutut', 'selected' => false],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Kledung)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Sunrise Camp Pos 3',
                            'description' => 'Mulai mendaki melewati ladang tembakau dengan opsi naik ojek hingga Pos 1, lalu trekking menuju Pos 3 untuk mendirikan kemah.',
                            'timeline' => [
                                ['time' => '08:30', 'activity' => 'Registrasi & Persiapan di Basecamp Kledung'],
                                ['time' => '09:30', 'activity' => 'Trekking Menuju Pos 2 & Pos 3'],
                                ['time' => '13:00', 'activity' => 'Makan Siang di Area Hutan'],
                                ['time' => '16:00', 'activity' => 'Tiba di Sunrise Camp Pos 3, Pasang Tenda'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Summit Attack & Kawah Aktif',
                            'description' => 'Mendaki jalur berbatu terjal dini hari menuju bibir kawah Sindoro. Menikmati sunrise spektakuler dengan latar Gunung Sumbing.',
                            'timeline' => [
                                ['time' => '03:00', 'activity' => 'Bangun & Summit Push'],
                                ['time' => '05:45', 'activity' => 'Puncak Sindoro & Eksplorasi Kawah'],
                                ['time' => '08:30', 'activity' => 'Kembali ke Tenda & Sarapan'],
                                ['time' => '11:00', 'activity' => 'Turun ke Basecamp Kledung'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
                        'Akomodasi Camp' => [
                            'Tenda Kapasitas Fleksibel',
                            'Common Area (Flysheet & Camp Lamp)',
                            'Peralatan Masak & Gas',
                            'Set Alat Makan & Minum',
                        ],
                        'Perizinan & Keamanan' => [
                            'Tiket Masuk & SIMAKSI Resmi',
                            'Asuransi Pendakian',
                        ],
                        'Perlengkapan Personal' => [
                            'Sleeping Bag / Warm Polar',
                            'Matras Busa',
                            'Jas Hujan & Emergency Blanket',
                            'P3K Standar Pendakian',
                        ],
                    ],
                    'excluded' => [
                        'Kebutuhan & Perlengkapan Pribadi' => [
                            'Pakaian & Sepatu Pendakian Pribadi',
                            'Masker Gas / Slayer Penahan Asap Kawah',
                        ],
                        'Transportasi' => [
                            'Ojek Basecamp ke Pos 1 (Opsional Pribadi)',
                        ],
                    ],
                ],
            ],

            'mt-prau' => [
                'id' => 3,
                'slug' => 'mt-prau',
                'title' => 'Mt. Prau Expedition',
                'mountain' => 'Mt. Prau',
                'elevation' => '2.590 mdpl',
                'difficulty_badge' => 'Sunrise Terindah Se-Jateng',
                'location' => 'Jawa Tengah',
                'grade' => 'Grade A',
                'grade_label' => 'Grade A – Pemula',
                'type' => 'private',
                'type_label' => 'Private Trip',
                'price' => 650000,
                'price_formatted' => 'Rp 650.000',
                'price_tektok' => 500000,
                'price_private' => 650000,
                'price_private_tektok' => 500000,
                'quota_current' => 4,
                'quota_max' => 8,
                'departure_date' => '28/06/2026',
                'image' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=900&auto=format&fit=crop',
                'description' => 'Sunrise terindah di Jawa Tengah dengan pemandangan 360 derajat jajaran gunung kembar.',
                'overview' => 'Gunung Prau di Dataran Tinggi Dieng adalah ikon keindahan alam Jawa Tengah. Trek yang relatif singkat dan ramah pemula menghantarkan pendaki ke bukit teletubbies dengan hamparan bunga daisy dan panorama sunrise emas berlatar Gunung Sindoro dan Sumbing.',
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sunrise Bukit Teletubbies'],
                    ['url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=600&auto=format&fit=crop', 'caption' => 'Padang Daisy Prau'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=600&auto=format&fit=crop', 'caption' => 'View Sindoro Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop', 'caption' => 'Camping Ground Prau'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=600&auto=format&fit=crop', 'caption' => 'Puncak 2.590 mdpl'],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '7.2 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '3–4 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '5–12°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Basecamp', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Patakbanteng)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '2.050m', 'x' => 40, 'y' => 190],
                        ['name' => 'Pos 1', 'elevation' => '2.200m', 'x' => 180, 'y' => 160],
                        ['name' => 'Pos 2', 'elevation' => '2.350m', 'x' => 330, 'y' => 130],
                        ['name' => 'Pos 3', 'elevation' => '2.480m', 'x' => 480, 'y' => 85],
                        ['name' => 'Puncak', 'elevation' => '2.590m', 'x' => 650, 'y' => 45],
                    ],
                    'path' => 'M 40 190 L 180 160 L 330 130 L 480 85 L 650 45',
                    'area' => 'M 40 190 L 180 160 L 330 130 L 480 85 L 650 45 L 650 210 L 40 210 Z',
                    'notes' => [
                        [
                            'title' => 'Titik Air:',
                            'desc' => 'Wajib bawa dari Basecamp',
                            'type' => 'water',
                            'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                            'icon_class' => 'bg-blue-50 text-blue-600',
                        ],
                        [
                            'title' => 'Suhu Ekstrem:',
                            'desc' => 'Dapat mencapai 0°C saat kemarau',
                            'type' => 'wind',
                            'badge_class' => 'bg-amber-50/50 border-amber-100 text-amber-900',
                            'icon_class' => 'bg-amber-100 text-amber-600',
                        ],
                        [
                            'title' => 'Sinyal Seluler:',
                            'desc' => 'Sangat stabil di sepanjang rute',
                            'type' => 'signal',
                            'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950',
                            'icon_class' => 'bg-emerald-100 text-emerald-600',
                        ],
                    ],
                ],
                'routes' => [
                    ['id' => 'patakbanteng', 'name' => 'Via Patakbanteng', 'badge' => 'Jalur Tercepat', 'selected' => true],
                    ['id' => 'dieng', 'name' => 'Via Dieng Kulon', 'badge' => 'Landai & Santai', 'selected' => false],
                    ['id' => 'kalilembu', 'name' => 'Via Kalilembu', 'badge' => 'Pemandangan Asri', 'selected' => false],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Patakbanteng)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Sunrise Camp',
                            'description' => 'Mulai trekking sore hari mendaki anak tangga dan kebun kentang hingga bukit teletubbies.',
                            'timeline' => [
                                ['time' => '13:00', 'activity' => 'Meeting point di Dieng & Makan Siang'],
                                ['time' => '14:30', 'activity' => 'Mulai Pendakian Patakbanteng'],
                                ['time' => '17:30', 'activity' => 'Tiba di Camp, Sunset & Makan Malam'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Golden Sunrise & Turun',
                            'description' => 'Menikmati lukisan langit fajar keemasan terbaik sebelum santai menuruni jalur.',
                            'timeline' => [
                                ['time' => '05:00', 'activity' => 'Golden Sunrise Dieng'],
                                ['time' => '07:30', 'activity' => 'Sarapan & Sesi Foto'],
                                ['time' => '09:30', 'activity' => 'Perjalanan Turun ke Basecamp'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
                        'Akomodasi Camp' => [
                            'Tenda Premium',
                            'Sleeping Bag Tebal & Matras',
                            'Lampu Tenda & Logistik Lengkap',
                        ],
                        'Perizinan' => [
                            'SIMAKSI & Asuransi Resmi',
                        ],
                    ],
                    'excluded' => [
                        'Pribadi' => [
                            'Jaket Gunung & Pakaian Hangat',
                        ],
                    ],
                ],
            ],

            'mt-slamet' => [
                'id' => 4,
                'slug' => 'mt-slamet',
                'title' => 'Mt. Slamet Expedition',
                'mountain' => 'Mt. Slamet',
                'elevation' => '3.428 mdpl',
                'difficulty_badge' => 'Atap Jawa Tengah',
                'location' => 'Jawa Tengah',
                'grade' => 'Grade C',
                'grade_label' => 'Grade C – Ahli',
                'type' => 'open',
                'type_label' => 'Open Trip',
                'price' => 950000,
                'price_formatted' => 'Rp 950.000',
                'price_tektok' => 750000,
                'price_private' => 1350000,
                'price_private_tektok' => 1100000,
                'quota_current' => 3,
                'quota_max' => 10,
                'departure_date' => '05/07/2026',
                'image' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=900&auto=format&fit=crop',
                'description' => 'Atap Jawa Tengah dengan jalur kerikil merah vulkanik curam dan elevasi ekstrem.',
                'overview' => 'Sebagai titik tertinggi di Jawa Tengah (3.428 mdpl), Gunung Slamet menghadirkan tantangan fisik tingkat lanjut. Karakteristik jalur Bambangan yang panjang berakar pohon serta tanjakan bebatuan vulkanik merah curam menuntut ketahanan mental dan fisik prima.',
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kawah Segara Wedi Slamet'],
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop', 'caption' => 'Trek Pasir Merah'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=600&auto=format&fit=crop', 'caption' => 'Tugu Puncak 3.428 mdpl'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop', 'caption' => 'Camp Pos 7'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=600&auto=format&fit=crop', 'caption' => 'Batas Vegetasi Plawangan'],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '16.5 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '9–11 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '4–12°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Pos 5', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Bambangan)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '1.500m', 'x' => 40, 'y' => 195],
                        ['name' => 'Pos 3', 'elevation' => '2.100m', 'x' => 190, 'y' => 165],
                        ['name' => 'Pos 5', 'elevation' => '2.550m', 'x' => 330, 'y' => 135],
                        ['name' => 'Pos 7', 'elevation' => '2.950m', 'x' => 450, 'y' => 95],
                        ['name' => 'Plawangan', 'elevation' => '3.150m', 'x' => 540, 'y' => 70],
                        ['name' => 'Puncak', 'elevation' => '3.428m', 'x' => 650, 'y' => 35],
                    ],
                    'path' => 'M 40 195 L 190 165 L 330 135 L 450 95 L 540 70 L 650 35',
                    'area' => 'M 40 195 L 190 165 L 330 135 L 450 95 L 540 70 L 650 35 L 650 210 L 40 210 Z',
                    'notes' => [
                        [
                            'title' => 'Titik Air Terakhir:',
                            'desc' => 'Pos 5 (Mata Air Samarantu)',
                            'type' => 'water',
                            'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800',
                            'icon_class' => 'bg-blue-50 text-blue-600',
                        ],
                        [
                            'title' => 'Zona Kerikil Luncur:',
                            'desc' => 'Wajib helm & gaiter di Plawangan',
                            'type' => 'wind',
                            'badge_class' => 'bg-red-50/50 border-red-100 text-red-900',
                            'icon_class' => 'bg-red-100 text-red-600',
                        ],
                        [
                            'title' => 'Sinyal:',
                            'desc' => 'Terbatas di Pos 1 dan Puncak',
                            'type' => 'signal',
                            'badge_class' => 'bg-slate-50 border-slate-200 text-slate-700',
                            'icon_class' => 'bg-slate-100 text-slate-600',
                        ],
                    ],
                ],
                'routes' => [
                    ['id' => 'bambangan', 'name' => 'Via Bambangan', 'badge' => 'Jalur Klasik Utama', 'selected' => true],
                    ['id' => 'dipajaya', 'name' => 'Via Dipajaya (Pemalang)', 'badge' => 'Jalur Alternatif', 'selected' => false],
                    ['id' => 'guci', 'name' => 'Via Guci (Tegal)', 'badge' => 'Pemandian Air Panas', 'selected' => false],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Bambangan)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Pos 7 Camp',
                            'description' => 'Pendakian panjang melintasi 7 pos peristirahatan hingga camp terakhir di batas vegetasi.',
                            'timeline' => [
                                ['time' => '07:00', 'activity' => 'Briefing & Mulai Pendakian'],
                                ['time' => '12:00', 'activity' => 'Makan Siang di Pos 4'],
                                ['time' => '16:30', 'activity' => 'Tiba di Pos 7, Pasang Tenda'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Menembus Pasir Merah ke Atap Jateng',
                            'description' => 'Trekking menantang melewati pasir kerikil curam Plawangan menuju bibir kawah raksasa.',
                            'timeline' => [
                                ['time' => '02:30', 'activity' => 'Summit Push Plawangan'],
                                ['time' => '05:30', 'activity' => 'Puncak Gunung Slamet (3.428 mdpl)'],
                                ['time' => '09:00', 'activity' => 'Turun ke Camp & Kembali ke Basecamp'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
                        'Akomodasi & Tim' => [
                            'Tenda Dome Tahan Badai',
                            'Guide Berlisensi & Porter Tim',
                            'Logistik Masak & Makan Hangat',
                            'Helm Pengaman Pendakian',
                        ],
                        'Perizinan' => [
                            'SIMAKSI & Asuransi Resmi',
                        ],
                    ],
                    'excluded' => [
                        'Pribadi' => [
                            'Gaiter, Sarung Tangan, & Trekking Pole',
                        ],
                    ],
                ],
            ],

            'mt-sumbing' => [
                'id' => 5,
                'slug' => 'mt-sumbing',
                'title' => 'Mt. Sumbing Expedition',
                'mountain' => 'Mt. Sumbing',
                'elevation' => '3.371 mdpl',
                'difficulty_badge' => 'Negeri di Atas Awan',
                'location' => 'Jawa Tengah',
                'grade' => 'Grade B',
                'grade_label' => 'Grade B – Menengah',
                'type' => 'private',
                'type_label' => 'Private Trip',
                'price' => 750000,
                'price_formatted' => 'Rp 750.000',
                'price_tektok' => 600000,
                'price_private' => 750000,
                'price_private_tektok' => 600000,
                'quota_current' => 4,
                'quota_max' => 10,
                'departure_date' => '12/07/2026',
                'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=900&auto=format&fit=crop',
                'description' => 'Melewati jalur eksotis Bowongso menuju puncak sejati beralaskan awan putih.',
                'overview' => 'Gunung Sumbing (3.371 mdpl) adalah gunung tertinggi ketiga di Pulau Jawa. Memiliki kawah luas dengan tebing-tebing batu dramatis seperti Puncak Sejati, Puncak Rajawali, dan Puncak Buntu, serta jalur Bowongso dan Butuh (Nepal Van Java) yang memukau.',
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Puncak Sejati Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=600&auto=format&fit=crop', 'caption' => 'Kawah Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=600&auto=format&fit=crop', 'caption' => 'Nepal Van Java'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop', 'caption' => 'Sunrise di Atas Awan'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=600&auto=format&fit=crop', 'caption' => 'Tebing Batu Rajawali'],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '12.8 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '7–8 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '7–14°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Pos 2', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Bowongso)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '1.600m', 'x' => 40, 'y' => 190],
                        ['name' => 'Pos 1', 'elevation' => '2.100m', 'x' => 160, 'y' => 165],
                        ['name' => 'Pos 2', 'elevation' => '2.500m', 'x' => 310, 'y' => 135],
                        ['name' => 'Pos 3', 'elevation' => '2.900m', 'x' => 460, 'y' => 95],
                        ['name' => 'Puncak', 'elevation' => '3.371m', 'x' => 650, 'y' => 40],
                    ],
                    'path' => 'M 40 190 L 160 165 L 310 135 L 460 95 L 650 40',
                    'area' => 'M 40 190 L 160 165 L 310 135 L 460 95 L 650 40 L 650 210 L 40 210 Z',
                    'notes' => [
                        ['title' => 'Titik Air:', 'desc' => 'Pos 2 Bowongso', 'type' => 'water', 'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800', 'icon_class' => 'bg-blue-50 text-blue-600'],
                        ['title' => 'Tebing Terjal:', 'desc' => 'Hati-hati saat menuju Puncak Rajawali', 'type' => 'wind', 'badge_class' => 'bg-amber-50/50 border-amber-100 text-amber-900', 'icon_class' => 'bg-amber-100 text-amber-600'],
                        ['title' => 'Sinyal:', 'desc' => 'Tersedia di Basecamp dan Pos 3', 'type' => 'signal', 'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950', 'icon_class' => 'bg-emerald-100 text-emerald-600'],
                    ],
                ],
                'routes' => [
                    ['id' => 'bowongso', 'name' => 'Via Bowongso', 'badge' => 'Jalur Hutan Asri', 'selected' => true],
                    ['id' => 'butuh', 'name' => 'Via Butuh (Nepal Van Java)', 'badge' => 'Paling Populer', 'selected' => false],
                    ['id' => 'garung', 'name' => 'Via Garung', 'badge' => 'Jalur Klasik', 'selected' => false],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Bowongso)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Camp Pos 3',
                            'description' => 'Mendaki melewati perkebunan dan hutan lindung menuju area camp Pos 3.',
                            'timeline' => [
                                ['time' => '08:30', 'activity' => 'Registrasi & Mulai Trekking'],
                                ['time' => '12:30', 'activity' => 'Makan Siang di Pos 2'],
                                ['time' => '16:00', 'activity' => 'Camp di Pos 3, Menikmati Sunset'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Puncak Sejati & Tebing Kawah',
                            'description' => 'Summit push dini hari menyambut fajar emas di Puncak Sejati.',
                            'timeline' => [
                                ['time' => '03:00', 'activity' => 'Summit Push'],
                                ['time' => '05:30', 'activity' => 'Sunrise di Puncak Sejati (3.371 mdpl)'],
                                ['time' => '10:00', 'activity' => 'Turun ke Basecamp'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
                        'Akomodasi Camp' => ['Tenda Nyaman', 'Matras & Sleeping Bag', 'Logistik Lengkap'],
                        'Perizinan' => ['Tiket Masuk & SIMAKSI Resmi'],
                    ],
                    'excluded' => [
                        'Pribadi' => ['Peralatan Mandi & Pakaian Hangat Pribadi'],
                    ],
                ],
            ],

            'mt-lawu' => [
                'id' => 6,
                'slug' => 'mt-lawu',
                'title' => 'Mt. Lawu Expedition',
                'mountain' => 'Mt. Lawu',
                'elevation' => '3.265 mdpl',
                'difficulty_badge' => 'Warung Tertinggi di Indonesia',
                'location' => 'Jawa Timur & Tengah',
                'grade' => 'Grade B',
                'grade_label' => 'Grade B – Menengah',
                'type' => 'open',
                'type_label' => 'Open Trip',
                'price' => 700000,
                'price_formatted' => 'Rp 700.000',
                'price_tektok' => 550000,
                'price_private' => 950000,
                'price_private_tektok' => 800000,
                'quota_current' => 7,
                'quota_max' => 12,
                'departure_date' => '19/07/2026',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=900&auto=format&fit=crop',
                'description' => 'Gunung sarat legenda sejarah dengan warung tertinggi di Indonesia milik Mbok Yem.',
                'overview' => 'Gunung Lawu (3.265 mdpl) berdiri di perbatasan Jawa Tengah dan Jawa Timur. Menawarkan pengalaman mendaki yang unik dengan perpaduan jalur berbatu tertata, situs-situs bersejarah kuno, kawah Candradimuka, dan warung legendaris Mbok Yem di dekat Puncak Hargo Dumilah.',
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Puncak Hargo Dumilah'],
                    ['url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=600&auto=format&fit=crop', 'caption' => 'Warung Mbok Yem'],
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=600&auto=format&fit=crop', 'caption' => 'Sendang Drajat'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop', 'caption' => 'Sunrise Hargo Dalem'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=600&auto=format&fit=crop', 'caption' => 'Trek Bebatuan Candi Cetho'],
                ],
                'stats' => [
                    ['label' => 'Jarak Total', 'value' => '14.2 km', 'icon' => 'milestone'],
                    ['label' => 'Durasi Waktu', 'value' => '7–9 Jam', 'icon' => 'clock'],
                    ['label' => 'Suhu Rata-rata', 'value' => '6–15°C', 'icon' => 'thermometer'],
                    ['label' => 'Sumber Air', 'value' => 'Sendang Drajat', 'icon' => 'droplet'],
                ],
                'elevation_profile' => [
                    'title' => 'Elevasi & Rute Pendakian (Via Candi Cetho)',
                    'points' => [
                        ['name' => 'Basecamp', 'elevation' => '1.450m', 'x' => 40, 'y' => 190],
                        ['name' => 'Pos 2', 'elevation' => '1.950m', 'x' => 170, 'y' => 160],
                        ['name' => 'Pos 3', 'elevation' => '2.350m', 'x' => 310, 'y' => 135],
                        ['name' => 'Gupakan Menjangan', 'elevation' => '2.950m', 'x' => 470, 'y' => 85],
                        ['name' => 'Hargo Dumilah', 'elevation' => '3.265m', 'x' => 650, 'y' => 45],
                    ],
                    'path' => 'M 40 190 L 170 160 L 310 135 L 470 85 L 650 45',
                    'area' => 'M 40 190 L 170 160 L 310 135 L 470 85 L 650 45 L 650 210 L 40 210 Z',
                    'notes' => [
                        ['title' => 'Mata Air Sakral:', 'desc' => 'Sendang Drajat di Pos 5', 'type' => 'water', 'badge_class' => 'bg-blue-50/80 border-blue-200/70 text-blue-800', 'icon_class' => 'bg-blue-50 text-blue-600'],
                        ['title' => 'Sabana Luas:', 'desc' => 'Gupakan Menjangan cocok untuk camp', 'type' => 'wind', 'badge_class' => 'bg-emerald-50/50 border-emerald-100 text-emerald-950', 'icon_class' => 'bg-emerald-100 text-emerald-600'],
                        ['title' => 'Sinyal:', 'desc' => 'Tersedia di warung puncak', 'type' => 'signal', 'badge_class' => 'bg-emerald-50/40 border-emerald-100 text-emerald-950', 'icon_class' => 'bg-emerald-100 text-emerald-600'],
                    ],
                ],
                'routes' => [
                    ['id' => 'cetho', 'name' => 'Via Candi Cetho', 'badge' => 'Sabana & Mistis Eksotis', 'selected' => true],
                    ['id' => 'cemorosewu', 'name' => 'Via Cemoro Sewu', 'badge' => 'Jalur Tangga Batu Cepat', 'selected' => false],
                    ['id' => 'cemorokandang', 'name' => 'Via Cemoro Kandang', 'badge' => 'Landai Santai', 'selected' => false],
                ],
                'itinerary' => [
                    'title' => 'Itinerary 2D1N (Via Candi Cetho)',
                    'days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Sabana Gupakan Menjangan',
                            'description' => 'Mendaki melewati kompleks Candi Cetho dan hutan pinus menuju padang sabana luas.',
                            'timeline' => [
                                ['time' => '08:00', 'activity' => 'Registrasi & Mulai Pendakian'],
                                ['time' => '12:00', 'activity' => 'Makan Siang di Pos 3'],
                                ['time' => '16:00', 'activity' => 'Camp di Gupakan Menjangan'],
                            ],
                        ],
                        [
                            'day' => 'Day 2',
                            'title' => 'Day 2: Muncak Hargo Dumilah & Kuliner Mbok Yem',
                            'description' => 'Menyapa pagi di titik tertinggi Lawu, mampir mencicipi nasi pecel legendaris Mbok Yem.',
                            'timeline' => [
                                ['time' => '04:00', 'activity' => 'Summit Push ke Hargo Dumilah'],
                                ['time' => '05:45', 'activity' => 'Sunrise di Puncak Lawu (3.265 mdpl)'],
                                ['time' => '07:30', 'activity' => 'Sarapan di Warung Mbok Yem & Turun'],
                            ],
                        ],
                    ],
                ],
                'facilities' => [
                    'included' => [
                        'Akomodasi' => ['Tenda Kapasitas Fleksibel', 'Sleeping Bag & Matras', 'Logistik Lengkap'],
                        'Perizinan' => ['Tiket Masuk SIMAKSI & Asuransi Resmi'],
                    ],
                    'excluded' => [
                        'Pribadi' => ['Jajan di Warung Mbok Yem (Opsional Pribadi)'],
                    ],
                ],
            ],
        ];
    }
}
