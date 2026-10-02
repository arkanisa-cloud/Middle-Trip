<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TrailGrade;
use App\Http\Controllers\Controller;
use App\Models\ExpeditionPriceTier;
use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MountainController extends Controller
{
    /**
     * Display a listing of the mountains.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $mountains = Mountain::with(['routes', 'priceTiers'])
            ->when($search, function ($query, $term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('province', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.mountains.index', compact('mountains', 'search'));
    }

    /**
     * Show the form for creating a new mountain.
     */
    public function create(): View
    {
        $grades = TrailGrade::cases();

        $defaultRoutes = [];

        return view('admin.mountains.create', compact('grades', 'defaultRoutes'));
    }

    /**
     * Store a newly created mountain in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'elevation' => 'required|integer|min:0',
            'province' => 'required|string|max:100',
            'grade' => 'required|string|in:Grade A,Grade B,Grade C',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cover_image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'base_price' => 'required|integer|min:0',
            'price_private' => 'nullable|integer|min:0',
            'price_tektok' => 'nullable|integer|min:0',
            'price_private_tektok' => 'nullable|integer|min:0',
            'booking_fee_per_pax' => 'required|integer|min:0',
            'price_lock_days_before_departure' => 'required|integer|min:1|max:30',
            'has_open_trip' => 'nullable|boolean',
            'has_private_trip' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'featured_order' => 'nullable|integer|in:1,2,3',
            'is_active' => 'nullable|boolean',
            'routes' => 'nullable|array',
            'routes.*.name' => 'required_with:routes|string|max:100',
            'routes.*.grade' => 'nullable|string',
            'routes.*.distance_km' => 'nullable|numeric|min:0',
            'routes.*.duration_hours' => 'nullable|string|max:50',
            'routes.*.is_primary' => 'nullable|boolean',
            'routes.*.checkpoints' => 'nullable|array',
            'routes.*.checkpoints.*.name' => 'nullable|string|max:100',
            'routes.*.checkpoints.*.elevation' => 'nullable|integer|min:0',
            'routes.*.water_note' => 'nullable|string|max:255',
            'routes.*.wind_note' => 'nullable|string|max:255',
            'routes.*.signal_note' => 'nullable|string|max:255',
            'routes.*.itinerary_days' => 'nullable|array',
            'routes.*.itinerary_days.*.day' => 'nullable|string|max:50',
            'routes.*.itinerary_days.*.title' => 'nullable|string|max:150',
            'routes.*.itinerary_days.*.desc' => 'nullable|string',
            'routes.*.itinerary_days.*.timeline' => 'nullable|string',
            'routes.*.itinerary_tektok_title' => 'nullable|string|max:150',
            'routes.*.itinerary_tektok_desc' => 'nullable|string',
            'routes.*.itinerary_tektok_timeline' => 'nullable|string',
            'routes.*.day1_title' => 'nullable|string|max:150',
            'routes.*.day1_desc' => 'nullable|string',
            'routes.*.day1_timeline' => 'nullable|string',
            'routes.*.day2_title' => 'nullable|string|max:150',
            'routes.*.day2_desc' => 'nullable|string',
            'routes.*.day2_timeline' => 'nullable|string',
            'price_tiers' => 'nullable|array',
            'price_tiers.*.min_pax' => 'required_with:price_tiers|integer|min:1',
            'price_tiers.*.max_pax' => 'nullable|integer',
            'price_tiers.*.price_per_pax' => 'required_with:price_tiers|integer|min:0',
            'gallery_files' => 'nullable|array|max:10',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_file_captions' => 'nullable|array',
            'gallery_file_captions.*' => 'nullable|string|max:150',
        ]);

        $mountain = DB::transaction(function () use ($validated, $request): Mountain {
            $slug = Str::slug($validated['name']);
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Mountain::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            // Handle cover image
            $coverImagePath = 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=1200&auto=format&fit=crop';
            if ($request->hasFile('cover_image_file')) {
                $path = $request->file('cover_image_file')->store('mountains', 'public');
                $coverImagePath = Storage::url($path);
            } elseif (! empty($validated['cover_image'])) {
                $coverImagePath = $validated['cover_image'];
            }

            $gallery = $this->processGalleryUploads($request, $validated['name']);

            $mountainCheckpoints = $this->formatCheckpoints($request);
            if (empty($mountainCheckpoints) && ! empty($validated['routes'][0])) {
                $mountainCheckpoints = $this->formatRouteCheckpoints($validated['routes'][0]);
            }

            $isFeatured = $request->boolean('is_featured', false);
            $featuredOrder = $isFeatured ? (int) ($validated['featured_order'] ?? 1) : null;
            if ($isFeatured && $featuredOrder) {
                Mountain::where('featured_order', $featuredOrder)->update([
                    'is_featured' => false,
                    'featured_order' => null,
                ]);
            }

            $mountain = Mountain::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'elevation' => $validated['elevation'],
                'province' => $validated['province'],
                'cover_image' => $coverImagePath,
                'description' => $validated['description'] ?? null,
                'overview' => $validated['overview'] ?? null,
                'elevation_checkpoints' => $mountainCheckpoints,
                'facilities_included' => $this->formatFacilities($request->input('facilities_included')),
                'facilities_excluded' => $this->formatFacilities($request->input('facilities_excluded')),
                'base_price' => $validated['base_price'],
                'price_private' => $validated['price_private'] ?? null,
                'price_tektok' => $validated['price_tektok'] ?? null,
                'price_private_tektok' => $validated['price_private_tektok'] ?? null,
                'booking_fee_per_pax' => $validated['booking_fee_per_pax'],
                'price_lock_days_before_departure' => $validated['price_lock_days_before_departure'],
                'has_open_trip' => $request->boolean('has_open_trip', true),
                'has_private_trip' => $request->boolean('has_private_trip', false),
                'is_featured' => $isFeatured,
                'featured_order' => $featuredOrder,
                'is_active' => $request->boolean('is_active', true),
                'gallery' => ! empty($gallery) ? $gallery : null,
            ]);

            $hasCreatedPrimaryRoute = false;
            if (! empty($validated['routes'])) {
                foreach ($validated['routes'] as $index => $routeData) {
                    if (empty($routeData['name'])) {
                        continue;
                    }
                    $isPrimary = ! empty($routeData['is_primary']) || $index === 0;
                    if ($isPrimary) {
                        $hasCreatedPrimaryRoute = true;
                    }

                    $routeCheckpoints = $this->formatRouteCheckpoints($routeData) ?? ($isPrimary ? $mountainCheckpoints : null);
                    $routeItinerary = $this->formatRouteItinerary($routeData, $routeData['name']);

                    Route::create([
                        'mountain_id' => $mountain->id,
                        'name' => $routeData['name'],
                        'slug' => Str::slug($routeData['name']),
                        'grade' => $isPrimary ? $validated['grade'] : ($routeData['grade'] ?? $validated['grade']),
                        'is_primary' => $isPrimary,
                        'distance_km' => $routeData['distance_km'] ?? null,
                        'duration_hours' => $routeData['duration_hours'] ?? null,
                        'elevation_checkpoints' => $routeCheckpoints,
                        'itinerary' => $routeItinerary,
                    ]);
                }
            }

            if (! empty($validated['price_tiers'])) {
                foreach ($validated['price_tiers'] as $tierData) {
                    if (empty($tierData['price_per_pax'])) {
                        continue;
                    }
                    ExpeditionPriceTier::create([
                        'mountain_id' => $mountain->id,
                        'min_pax' => $tierData['min_pax'],
                        'max_pax' => null,
                        'price_per_pax' => $tierData['price_per_pax'],
                    ]);
                }
            }

            return $mountain;
        });

        return redirect()->route('admin.mountains.index')->with('success', "Master data gunung '{$mountain->name}' berhasil ditambahkan. Silakan buka menu Jadwal Ekspedisi jika ingin menambahkan jadwal batch trip.");
    }

    /**
     * Show the form for editing the specified mountain.
     */
    public function edit(Mountain $mountain): View
    {
        $mountain->load(['routes', 'priceTiers', 'primaryRoute']);
        $grades = TrailGrade::cases();
        $defaultGrade = $mountain->default_grade?->value ?? 'Grade A';

        // Checkpoints preparation
        $checkpoints = null;
        if (! empty($mountain->elevation_checkpoints)) {
            if (isset($mountain->elevation_checkpoints['points']) && is_array($mountain->elevation_checkpoints['points'])) {
                $checkpoints = $mountain->elevation_checkpoints['points'];
            } elseif (array_is_list($mountain->elevation_checkpoints) && ! empty($mountain->elevation_checkpoints[0]['name'])) {
                $checkpoints = $mountain->elevation_checkpoints;
            }
        }
        if (empty($checkpoints)) {
            $checkpoints = [
                ['name' => 'Basecamp', 'elevation' => (int) max(500, round($mountain->elevation * 0.45))],
                ['name' => 'Pos 1', 'elevation' => (int) round($mountain->elevation * 0.6)],
                ['name' => 'Pos 2', 'elevation' => (int) round($mountain->elevation * 0.75)],
                ['name' => 'Pos 3', 'elevation' => (int) round($mountain->elevation * 0.88)],
                ['name' => 'Puncak', 'elevation' => (int) $mountain->elevation],
            ];
        }

        $waterNote = $mountain->elevation_checkpoints['water_note'] ?? 'Pos Tengah (Sumber Air Terakhir)';
        $windNote = $mountain->elevation_checkpoints['wind_note'] ?? 'Waspada terpaan angin kencang di punggungan';
        $signalNote = $mountain->elevation_checkpoints['signal_note'] ?? 'Stabil di Basecamp & Pos 1';

        $routesData = $mountain->routes->map(function ($r, $index) use ($checkpoints, $waterNote, $windNote, $signalNote) {
            $rPoints = null;
            if (! empty($r->elevation_checkpoints)) {
                if (isset($r->elevation_checkpoints['points']) && is_array($r->elevation_checkpoints['points'])) {
                    $rPoints = $r->elevation_checkpoints['points'];
                } elseif (array_is_list($r->elevation_checkpoints) && ! empty($r->elevation_checkpoints[0]['name'])) {
                    $rPoints = $r->elevation_checkpoints;
                }
            }
            $rPoints = $rPoints ?: $checkpoints;
            $rWater = $r->elevation_checkpoints['water_note'] ?? $waterNote;
            $rWind = $r->elevation_checkpoints['wind_note'] ?? $windNote;
            $rSignal = $r->elevation_checkpoints['signal_note'] ?? $signalNote;

            $itineraryDays = [];
            $itinSource = $r->itinerary['camping'] ?? $r->itinerary ?? [];
            if (! empty($itinSource['days']) && is_array($itinSource['days'])) {
                foreach ($itinSource['days'] as $dIdx => $d) {
                    $timelineStr = ! empty($d['timeline']) && is_array($d['timeline'])
                        ? implode("\n", array_map(fn ($item) => ($item['time'] ?? '08:00').' - '.($item['activity'] ?? ''), $d['timeline']))
                        : '';
                    $itineraryDays[] = [
                        'day' => $d['day'] ?? ('Day '.($dIdx + 1)),
                        'title' => $d['title'] ?? ('Day '.($dIdx + 1)),
                        'desc' => $d['description'] ?? '',
                        'timeline' => $timelineStr,
                    ];
                }
            }

            if (empty($itineraryDays)) {
                $day1 = $itinSource['days'][0] ?? null;
                $day2 = $itinSource['days'][1] ?? null;

                $day1Timeline = ! empty($day1['timeline'])
                    ? implode("\n", array_map(fn ($item) => ($item['time'] ?? '08:00').' - '.($item['activity'] ?? ''), $day1['timeline']))
                    : "08:00 - Registrasi & Persiapan di Basecamp\n09:00 - Mulai Trekking Menuju Pos 1 & 2\n12:30 - Makan Siang & Istirahat di Pos Tengah\n16:00 - Tiba di Camp Area & Dirikan Tenda";

                $day2Timeline = ! empty($day2['timeline'])
                    ? implode("\n", array_map(fn ($item) => ($item['time'] ?? '03:30').' - '.($item['activity'] ?? ''), $day2['timeline']))
                    : "03:30 - Summit Push Menuju Puncak\n05:30 - Sunrise Spektakuler di Puncak\n08:00 - Kembali ke Camp, Sarapan & Packing\n10:00 - Perjalanan Turun Menuju Basecamp\n14:00 - Tiba di Basecamp & Penutupan Trip";

                $itineraryDays = [
                    [
                        'day' => 'Day 1',
                        'title' => $day1['title'] ?? 'Day 1: Basecamp ke Camp Area',
                        'desc' => $day1['description'] ?? 'Mulai pendakian dari Basecamp melewati perkebunan dan vegetasi hutan, beristirahat di pos tengah dan mendirikan tenda di camp area.',
                        'timeline' => $day1Timeline,
                    ],
                    [
                        'day' => 'Day 2',
                        'title' => $day2['title'] ?? 'Day 2: Summit Push & Turun Kembali',
                        'desc' => $day2['description'] ?? 'Bangun dini hari untuk summit push menikmati sunrise di puncak tertinggi, sarapan hangat, lalu berkemas turun kembali ke basecamp.',
                        'timeline' => $day2Timeline,
                    ],
                ];
            }

            $tektokData = $r->itinerary['tektok'] ?? null;
            $tektokDays = $tektokData['days'] ?? null;
            $tektokDay1 = $tektokDays[0] ?? null;
            $tektokTimeline = ! empty($tektokDay1['timeline']) && is_array($tektokDay1['timeline'])
                ? implode("\n", array_map(fn ($item) => ($item['time'] ?? '00:00').' - '.($item['activity'] ?? ''), $tektokDay1['timeline']))
                : "00:00 - Registrasi & Cek Medis di Basecamp\n01:00 - Mulai Trekking Dini Hari Menuju Pos 2 & 3\n05:30 - Sunrise Spektakuler di Puncak Tertinggi\n07:30 - Foto Bersama & Mulai Perjalanan Turun\n12:00 - Tiba Kembali di Basecamp & Penutupan Trip";
            $tektokTitle = $tektokData['title'] ?? "Itinerary 1D Tek-tok ({$r->name})";
            $tektokDesc = $tektokDay1['description'] ?? 'Pendakian cepat langsung turun dalam 1 hari tanpa mendirikan tenda.';

            $bookingsCount = $r->bookings()->count();
            $activeBookingsCount = $r->bookings()
                ->whereIn('status', ['open', 'reserved', 'price_locked', 'paid'])
                ->count();

            return [
                'id' => $r->id,
                'name' => $r->name,
                'distance_km' => $r->distance_km,
                'duration_hours' => $r->duration_hours,
                'grade' => $r->grade?->value ?? (string) $r->grade,
                'is_primary' => (bool) $r->is_primary,
                'isOpen' => $r->is_primary || $index === 0,
                'activeSubTab' => 'elevation',
                'bookings_count' => $bookingsCount,
                'active_bookings_count' => $activeBookingsCount,
                'has_bookings' => $bookingsCount > 0,
                'has_active_bookings' => $activeBookingsCount > 0,
                'checkpoints' => $rPoints,
                'water_note' => $rWater,
                'wind_note' => $rWind,
                'signal_note' => $rSignal,
                'itinerary_type' => 'camping',
                'itinerary_days' => $itineraryDays,
                'itinerary_tektok_title' => $tektokTitle,
                'itinerary_tektok_desc' => $tektokDesc,
                'itinerary_tektok_timeline' => $tektokTimeline,
            ];
        })->values()->toArray();

        // Facilities preparation
        $facilitiesIncluded = [];
        if (! empty($mountain->facilities_included) && is_array($mountain->facilities_included)) {
            foreach ($mountain->facilities_included as $cat => $items) {
                $facilitiesIncluded[] = [
                    'category' => $cat,
                    'items' => is_array($items) ? implode("\n", $items) : (string) $items,
                ];
            }
        } else {
            $facilitiesIncluded = [
                ['category' => 'Akomodasi Camp', 'items' => "Tenda Kapasitas Fleksibel\nCommon Area (Flysheet & Camp Lamp)\nPeralatan Masak & Gas (Kompor / Nesting)\nSet Alat Makan & Minum"],
                ['category' => 'Perizinan & Keamanan', 'items' => "Tiket Masuk & SIMAKSI Resmi\nAsuransi Pendakian Resmi"],
                ['category' => 'Perlengkapan Personal', 'items' => "Sleeping Bag / Warm Polar\nMatras Busa\nJas Hujan & Emergency Blanket\nP3K Standar Pendakian"],
            ];
        }

        $facilitiesExcluded = [];
        if (! empty($mountain->facilities_excluded) && is_array($mountain->facilities_excluded)) {
            foreach ($mountain->facilities_excluded as $cat => $items) {
                $facilitiesExcluded[] = [
                    'category' => $cat,
                    'items' => is_array($items) ? implode("\n", $items) : (string) $items,
                ];
            }
        } else {
            $facilitiesExcluded = [
                ['category' => 'Kebutuhan & Perlengkapan Pribadi', 'items' => "Pakaian & Sepatu Pendakian Pribadi\nObat-obatan Pribadi Khusus"],
                ['category' => 'Transportasi & Akses Awal', 'items' => 'Transportasi Kota Asal ke Meeting Point'],
            ];
        }

        return view('admin.mountains.edit', compact(
            'mountain',
            'grades',
            'defaultGrade',
            'routesData',
            'checkpoints',
            'waterNote',
            'windNote',
            'signalNote',
            'facilitiesIncluded',
            'facilitiesExcluded'
        ));
    }

    /**
     * Update the specified mountain in storage.
     */
    public function update(Request $request, Mountain $mountain): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'elevation' => 'required|integer|min:0',
            'province' => 'required|string|max:100',
            'grade' => 'required|string|in:Grade A,Grade B,Grade C',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cover_image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'base_price' => 'required|integer|min:0',
            'price_private' => 'nullable|integer|min:0',
            'price_tektok' => 'nullable|integer|min:0',
            'price_private_tektok' => 'nullable|integer|min:0',
            'booking_fee_per_pax' => 'required|integer|min:0',
            'price_lock_days_before_departure' => 'required|integer|min:1|max:30',
            'has_open_trip' => 'nullable|boolean',
            'has_private_trip' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'featured_order' => 'nullable|integer|in:1,2,3',
            'is_active' => 'nullable|boolean',
            'routes' => 'nullable|array',
            'routes.*.id' => 'nullable|integer',
            'routes.*.name' => 'required_with:routes|string|max:100',
            'routes.*.grade' => 'nullable|string',
            'routes.*.distance_km' => 'nullable|numeric|min:0',
            'routes.*.duration_hours' => 'nullable|string|max:50',
            'routes.*.is_primary' => 'nullable|boolean',
            'routes.*.checkpoints' => 'nullable|array',
            'routes.*.checkpoints.*.name' => 'nullable|string|max:100',
            'routes.*.checkpoints.*.elevation' => 'nullable|integer|min:0',
            'routes.*.water_note' => 'nullable|string|max:255',
            'routes.*.wind_note' => 'nullable|string|max:255',
            'routes.*.signal_note' => 'nullable|string|max:255',
            'routes.*.itinerary_days' => 'nullable|array',
            'routes.*.itinerary_days.*.day' => 'nullable|string|max:50',
            'routes.*.itinerary_days.*.title' => 'nullable|string|max:150',
            'routes.*.itinerary_days.*.desc' => 'nullable|string',
            'routes.*.itinerary_days.*.timeline' => 'nullable|string',
            'routes.*.itinerary_tektok_title' => 'nullable|string|max:150',
            'routes.*.itinerary_tektok_desc' => 'nullable|string',
            'routes.*.itinerary_tektok_timeline' => 'nullable|string',
            'routes.*.day1_title' => 'nullable|string|max:150',
            'routes.*.day1_desc' => 'nullable|string',
            'routes.*.day1_timeline' => 'nullable|string',
            'routes.*.day2_title' => 'nullable|string|max:150',
            'routes.*.day2_desc' => 'nullable|string',
            'routes.*.day2_timeline' => 'nullable|string',
            'price_tiers' => 'nullable|array',
            'price_tiers.*.id' => 'nullable|integer',
            'price_tiers.*.min_pax' => 'required_with:price_tiers|integer|min:1',
            'price_tiers.*.max_pax' => 'nullable|integer',
            'price_tiers.*.price_per_pax' => 'required_with:price_tiers|integer|min:0',
            'existing_gallery' => 'nullable|array',
            'existing_gallery.*.url' => 'required|string',
            'existing_gallery.*.caption' => 'nullable|string|max:150',
            'gallery_files' => 'nullable|array|max:10',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_file_captions' => 'nullable|array',
            'gallery_file_captions.*' => 'nullable|string|max:150',
        ]);

        // Validate if any deleted route is currently being booked or has bookings
        if (isset($validated['routes'])) {
            $submittedRouteIds = array_filter(array_column($validated['routes'], 'id'));
            $routesAttemptedToDelete = Route::where('mountain_id', $mountain->id)
                ->whereNotIn('id', $submittedRouteIds)
                ->get();

            foreach ($routesAttemptedToDelete as $rDelete) {
                $activeBookingsCount = $rDelete->bookings()
                    ->whereIn('status', ['open', 'reserved', 'price_locked', 'paid'])
                    ->count();

                if ($activeBookingsCount > 0) {
                    return back()->withInput()->with('error', "Jalur '{$rDelete->name}' tidak dapat dihapus karena sedang ada pemesanan ({$activeBookingsCount} booking aktif) oleh pendaki.");
                }

                if ($rDelete->bookings()->exists()) {
                    return back()->withInput()->with('error', "Jalur '{$rDelete->name}' tidak dapat dihapus karena memiliki riwayat pemesanan tiket.");
                }

                if ($rDelete->expeditions()->whereIn('status', ['open', 'price_locked'])->exists()) {
                    return back()->withInput()->with('error', "Jalur '{$rDelete->name}' tidak dapat dihapus karena masih memiliki jadwal ekspedisi aktif.");
                }
            }
        }

        DB::transaction(function () use ($validated, $request, $mountain) {
            $coverImagePath = $mountain->cover_image;
            if ($request->hasFile('cover_image_file')) {
                $path = $request->file('cover_image_file')->store('mountains', 'public');
                $coverImagePath = Storage::url($path);
                if ($mountain->cover_image && Str::startsWith($mountain->cover_image, '/storage/mountains/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $mountain->cover_image));
                }
            } elseif (! empty($validated['cover_image'])) {
                $coverImagePath = $validated['cover_image'];
            }

            // Tentukan rute utama (is_primary)
            $primaryIndex = null;
            if (! empty($validated['routes'])) {
                foreach ($validated['routes'] as $idx => $rData) {
                    if (! empty($rData['is_primary'])) {
                        $primaryIndex = $idx;
                        break;
                    }
                }
                if ($primaryIndex === null) {
                    $primaryIndex = 0;
                }
            }

            $primaryRouteData = $primaryIndex !== null ? ($validated['routes'][$primaryIndex] ?? null) : null;
            $mountainCheckpoints = $this->formatCheckpoints($request);
            if (empty($mountainCheckpoints) && ! empty($primaryRouteData)) {
                $mountainCheckpoints = $this->formatRouteCheckpoints($primaryRouteData);
            }

            $gallery = $this->syncGallery($request, $mountain, $validated['name']);

            $isFeatured = $request->boolean('is_featured', false);
            $featuredOrder = $isFeatured ? (int) ($validated['featured_order'] ?? 1) : null;
            if ($isFeatured && $featuredOrder) {
                Mountain::where('id', '!=', $mountain->id)
                    ->where('featured_order', $featuredOrder)
                    ->update([
                        'is_featured' => false,
                        'featured_order' => null,
                    ]);
            }

            $mountain->update([
                'name' => $validated['name'],
                'elevation' => $validated['elevation'],
                'province' => $validated['province'],
                'cover_image' => $coverImagePath,
                'description' => $validated['description'] ?? null,
                'overview' => $validated['overview'] ?? null,
                'elevation_checkpoints' => $mountainCheckpoints ?? $mountain->elevation_checkpoints,
                'facilities_included' => $this->formatFacilities($request->input('facilities_included')),
                'facilities_excluded' => $this->formatFacilities($request->input('facilities_excluded')),
                'base_price' => $validated['base_price'],
                'price_private' => $validated['price_private'] ?? null,
                'price_tektok' => $validated['price_tektok'] ?? null,
                'price_private_tektok' => $validated['price_private_tektok'] ?? null,
                'booking_fee_per_pax' => $validated['booking_fee_per_pax'],
                'price_lock_days_before_departure' => $validated['price_lock_days_before_departure'],
                'has_open_trip' => $request->boolean('has_open_trip'),
                'has_private_trip' => $request->boolean('has_private_trip'),
                'is_featured' => $isFeatured,
                'featured_order' => $featuredOrder,
                'is_active' => $request->boolean('is_active'),
                'gallery' => ! empty($gallery) ? $gallery : null,
            ]);

            // Sync routes
            if (isset($validated['routes'])) {
                $keptRouteIds = [];
                foreach ($validated['routes'] as $index => $routeData) {
                    if (empty($routeData['name'])) {
                        continue;
                    }
                    $isPrimary = ($index === $primaryIndex);
                    $routeGrade = $isPrimary ? $validated['grade'] : ($routeData['grade'] ?? $validated['grade']);
                    $routeCheckpoints = $this->formatRouteCheckpoints($routeData) ?? ($isPrimary ? ($mountainCheckpoints ?? $mountain->elevation_checkpoints) : null);
                    $routeItinerary = $this->formatRouteItinerary($routeData, $routeData['name']);

                    if (! empty($routeData['id'])) {
                        $route = Route::where('mountain_id', $mountain->id)->find($routeData['id']);
                        if ($route) {
                            $route->update([
                                'name' => $routeData['name'],
                                'slug' => Str::slug($routeData['name']),
                                'grade' => $routeGrade,
                                'is_primary' => $isPrimary,
                                'distance_km' => $routeData['distance_km'] ?? null,
                                'duration_hours' => $routeData['duration_hours'] ?? null,
                                'elevation_checkpoints' => $routeCheckpoints,
                                'itinerary' => $routeItinerary,
                            ]);
                            $keptRouteIds[] = $route->id;
                        }
                    } else {
                        $newRoute = Route::create([
                            'mountain_id' => $mountain->id,
                            'name' => $routeData['name'],
                            'slug' => Str::slug($routeData['name']),
                            'grade' => $routeGrade,
                            'is_primary' => $isPrimary,
                            'distance_km' => $routeData['distance_km'] ?? null,
                            'duration_hours' => $routeData['duration_hours'] ?? null,
                            'elevation_checkpoints' => $routeCheckpoints,
                            'itinerary' => $routeItinerary,
                        ]);
                        $keptRouteIds[] = $newRoute->id;
                    }
                }
                // Delete removed routes only if no bookings and no active expeditions
                Route::where('mountain_id', $mountain->id)
                    ->whereNotIn('id', $keptRouteIds)
                    ->whereDoesntHave('bookings')
                    ->whereDoesntHave('expeditions')
                    ->delete();
            } else {
                // Ensure primary route has updated grade
                $primary = $mountain->primaryRoute ?? $mountain->routes()->first();
                if ($primary) {
                    $primary->update(['grade' => $validated['grade']]);
                }
            }

            // Sync price tiers without max_pax
            if (isset($validated['price_tiers'])) {
                $mountain->priceTiers()->delete();
                foreach ($validated['price_tiers'] as $tierData) {
                    if (empty($tierData['price_per_pax'])) {
                        continue;
                    }
                    ExpeditionPriceTier::create([
                        'mountain_id' => $mountain->id,
                        'min_pax' => $tierData['min_pax'],
                        'max_pax' => null,
                        'price_per_pax' => $tierData['price_per_pax'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.mountains.index')->with('success', 'Data gunung, jalur, dan fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified mountain from storage.
     */
    public function destroy(Mountain $mountain): RedirectResponse
    {
        if ($mountain->expeditions()->whereIn('status', ['open', 'price_locked'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus gunung yang masih memiliki jadwal ekspedisi aktif.');
        }

        if ($mountain->cover_image && Str::startsWith($mountain->cover_image, '/storage/mountains/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $mountain->cover_image));
        }

        $mountain->delete();

        return redirect()->route('admin.mountains.index')->with('success', 'Data gunung berhasil dihapus.');
    }

    /**
     * Parse and structure facilities input from request.
     *
     * @param  array<int, array{category?: string, items?: string}>|null  $rawCategories
     * @return array<string, array<int, string>>|null
     */
    private function formatFacilities(?array $rawCategories): ?array
    {
        if (empty($rawCategories)) {
            return null;
        }

        $result = [];
        foreach ($rawCategories as $cat) {
            $categoryName = trim($cat['category'] ?? '');
            $rawItems = $cat['items'] ?? '';
            if ($categoryName === '') {
                continue;
            }
            $items = array_values(array_filter(array_map('trim', explode("\n", (string) $rawItems))));
            if (! empty($items)) {
                $result[$categoryName] = $items;
            }
        }

        return ! empty($result) ? $result : null;
    }

    /**
     * Parse elevation checkpoints and notes from request.
     *
     * @return array<string, mixed>|null
     */
    private function formatCheckpoints(Request $request): ?array
    {
        $rawPoints = $request->input('checkpoints', []);
        $cleanPoints = [];
        foreach ($rawPoints as $pt) {
            if (! empty($pt['name']) && ! empty($pt['elevation'])) {
                $cleanPoints[] = [
                    'name' => trim($pt['name']),
                    'elevation' => (int) $pt['elevation'],
                ];
            }
        }

        if (empty($cleanPoints)) {
            return null;
        }

        return [
            'points' => $cleanPoints,
            'water_note' => $request->input('water_note') ?: 'Pos Tengah (Sumber Air Terakhir)',
            'wind_note' => $request->input('wind_note') ?: 'Waspada terpaan angin kencang di punggungan',
            'signal_note' => $request->input('signal_note') ?: 'Stabil di Basecamp & Pos 1',
        ];
    }

    /**
     * Parse elevation checkpoints and notes specifically for a route.
     *
     * @param  array<string, mixed>  $routeData
     * @return array<string, mixed>|null
     */
    private function formatRouteCheckpoints(array $routeData): ?array
    {
        $rawPoints = $routeData['checkpoints'] ?? [];
        $cleanPoints = [];
        foreach ($rawPoints as $pt) {
            if (! empty($pt['name']) && isset($pt['elevation']) && $pt['elevation'] !== '') {
                $cleanPoints[] = [
                    'name' => trim((string) $pt['name']),
                    'elevation' => (int) $pt['elevation'],
                ];
            }
        }

        if (empty($cleanPoints)) {
            return null;
        }

        return [
            'points' => $cleanPoints,
            'water_note' => ! empty($routeData['water_note']) ? trim($routeData['water_note']) : 'Pos Tengah (Sumber Air Terakhir)',
            'wind_note' => ! empty($routeData['wind_note']) ? trim($routeData['wind_note']) : 'Waspada terpaan angin kencang di punggungan',
            'signal_note' => ! empty($routeData['signal_note']) ? trim($routeData['signal_note']) : 'Stabil di Basecamp & Pos 1',
        ];
    }

    /**
     * Parse itinerary timeline specifically for a route.
     *
     * @param  array<string, mixed>  $routeData
     * @return array<string, mixed>|null
     */
    private function formatRouteItinerary(array $routeData, string $routeName): ?array
    {
        $parseTimeline = function (string $raw): array {
            $lines = array_values(array_filter(array_map('trim', explode("\n", $raw))));
            $items = [];
            foreach ($lines as $line) {
                if (preg_match('/^(\d{1,2}[:.]\d{2})\s*[-:]\s*(.+)$/u', $line, $matches)) {
                    $items[] = [
                        'time' => str_replace('.', ':', trim($matches[1])),
                        'activity' => trim($matches[2]),
                    ];
                } elseif (! empty($line)) {
                    $items[] = [
                        'time' => '—',
                        'activity' => $line,
                    ];
                }
            }

            return $items;
        };

        $rawDays = $routeData['itinerary_days'] ?? [];
        $parsedDays = [];

        if (! empty($rawDays) && is_array($rawDays)) {
            foreach ($rawDays as $idx => $dayItem) {
                $dayNum = $idx + 1;
                $dayLabel = ! empty($dayItem['day']) ? trim((string) $dayItem['day']) : "Day {$dayNum}";
                $title = ! empty($dayItem['title']) ? trim((string) $dayItem['title']) : "Day {$dayNum}: Rencana Pendakian";
                $desc = ! empty($dayItem['desc']) ? trim((string) $dayItem['desc']) : '';
                $timelineRaw = $dayItem['timeline'] ?? '';
                $timeline = $parseTimeline((string) $timelineRaw);

                $parsedDays[] = [
                    'day' => $dayLabel,
                    'title' => $title,
                    'description' => $desc,
                    'timeline' => ! empty($timeline) ? $timeline : [
                        ['time' => '08:00', 'activity' => 'Mulai kegiatan hari '.$dayNum],
                        ['time' => '12:00', 'activity' => 'Istirahat & logistik'],
                        ['time' => '17:00', 'activity' => 'Tiba di lokasi camp / tujuan'],
                    ],
                ];
            }
        } else {
            // Fallback untuk legacy day1 & day2 input
            $day1Title = ! empty($routeData['day1_title']) ? trim($routeData['day1_title']) : 'Day 1: Basecamp ke Camp Area';
            $day1Desc = ! empty($routeData['day1_desc']) ? trim($routeData['day1_desc']) : 'Mulai pendakian dari Basecamp melewati perkebunan dan vegetasi hutan, beristirahat di pos tengah dan mendirikan tenda di camp area.';
            $day1RawTimeline = $routeData['day1_timeline'] ?? '';

            $day2Title = ! empty($routeData['day2_title']) ? trim($routeData['day2_title']) : 'Day 2: Summit Push & Turun Kembali';
            $day2Desc = ! empty($routeData['day2_desc']) ? trim($routeData['day2_desc']) : 'Bangun dini hari untuk summit push menikmati sunrise di puncak tertinggi, sarapan hangat, lalu berkemas turun kembali ke basecamp.';
            $day2RawTimeline = $routeData['day2_timeline'] ?? '';

            $day1Items = $parseTimeline((string) $day1RawTimeline);
            $day2Items = $parseTimeline((string) $day2RawTimeline);

            if (! empty($day1Items) || ! empty($day2Items)) {
                $parsedDays = [
                    [
                        'day' => 'Day 1',
                        'title' => $day1Title,
                        'description' => $day1Desc,
                        'timeline' => ! empty($day1Items) ? $day1Items : [
                            ['time' => '08:00', 'activity' => 'Registrasi & Persiapan di Basecamp'],
                            ['time' => '09:00', 'activity' => 'Mulai Trekking Menuju Pos 1 & 2'],
                            ['time' => '12:30', 'activity' => 'Makan Siang & Istirahat di Pos Tengah'],
                            ['time' => '16:00', 'activity' => 'Tiba di Camp Area & Dirikan Tenda'],
                        ],
                    ],
                    [
                        'day' => 'Day 2',
                        'title' => $day2Title,
                        'description' => $day2Desc,
                        'timeline' => ! empty($day2Items) ? $day2Items : [
                            ['time' => '03:30', 'activity' => 'Summit Push Menuju Puncak'],
                            ['time' => '05:30', 'activity' => 'Sunrise Spektakuler di Puncak'],
                            ['time' => '08:00', 'activity' => 'Kembali ke Camp, Sarapan & Packing'],
                            ['time' => '10:00', 'activity' => 'Perjalanan Turun Menuju Basecamp'],
                            ['time' => '14:00', 'activity' => 'Tiba di Basecamp & Penutupan Trip'],
                        ],
                    ],
                ];
            }
        }

        if (empty($parsedDays)) {
            return null;
        }

        $countDays = count($parsedDays);
        if ($countDays === 1) {
            $durationLabel = '1D (Tek-tok)';
        } else {
            $nights = $countDays - 1;
            $durationLabel = "{$countDays}D{$nights}N";
        }

        $campingItinerary = [
            'title' => "Itinerary {$durationLabel} ({$routeName})",
            'duration_label' => $durationLabel,
            'days_count' => $countDays,
            'days' => $parsedDays,
        ];

        // Format Tektok Itinerary
        $rawTektokTimeline = $routeData['itinerary_tektok_timeline'] ?? '';
        $rawTektokTitle = ! empty($routeData['itinerary_tektok_title'])
            ? trim((string) $routeData['itinerary_tektok_title'])
            : "Itinerary 1D Tek-tok ({$routeName})";
        $rawTektokDesc = ! empty($routeData['itinerary_tektok_desc'])
            ? trim((string) $routeData['itinerary_tektok_desc'])
            : 'Pendakian cepat langsung turun dalam 1 hari tanpa mendirikan tenda.';

        $parsedTektokItems = ! empty($rawTektokTimeline) ? $parseTimeline((string) $rawTektokTimeline) : [];
        if (empty($parsedTektokItems)) {
            $parsedTektokItems = [
                ['time' => '00:00', 'activity' => 'Registrasi & Cek Medis di Basecamp'],
                ['time' => '01:00', 'activity' => 'Mulai Trekking Dini Hari Menuju Pos 2 & 3'],
                ['time' => '05:30', 'activity' => 'Sunrise Spektakuler di Puncak Tertinggi'],
                ['time' => '07:30', 'activity' => 'Foto Bersama & Mulai Perjalanan Turun'],
                ['time' => '12:00', 'activity' => 'Tiba Kembali di Basecamp & Penutupan Trip'],
            ];
        }

        $tektokItinerary = [
            'title' => $rawTektokTitle,
            'duration_label' => '1D (Tek-tok)',
            'days_count' => 1,
            'days' => [
                [
                    'day' => 'Day 1',
                    'title' => 'Trekking Tek-tok 1 Hari (Langsung Turun)',
                    'description' => $rawTektokDesc,
                    'timeline' => $parsedTektokItems,
                ],
            ],
        ];

        return [
            'title' => $campingItinerary['title'],
            'duration_label' => $campingItinerary['duration_label'],
            'days_count' => $campingItinerary['days_count'],
            'days' => $campingItinerary['days'],
            'camping' => $campingItinerary,
            'tektok' => $tektokItinerary,
        ];
    }

    /**
     * Memproses upload file galeri untuk create gunung baru.
     *
     * @return array<int, array{url: string, caption: string}>
     */
    private function processGalleryUploads(Request $request, string $mountainName): array
    {
        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            $files = $request->file('gallery_files');
            $captions = $request->input('gallery_file_captions', []);

            foreach ($files as $idx => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('mountains/gallery', 'public');
                    $caption = ! empty($captions[$idx]) ? trim((string) $captions[$idx]) : "{$mountainName} Foto ".($idx + 1);
                    $gallery[] = [
                        'url' => Storage::url($path),
                        'caption' => $caption,
                    ];
                }
            }
        }

        return $gallery;
    }

    /**
     * Menyinkronkan foto galeri yang sudah ada dengan foto baru pada update gunung.
     *
     * @return array<int, array{url: string, caption: string}>
     */
    private function syncGallery(Request $request, Mountain $mountain, string $mountainName): array
    {
        $finalGallery = [];
        $existing = $request->input('existing_gallery', []);

        if (is_array($existing)) {
            foreach ($existing as $item) {
                if (! empty($item['url'])) {
                    $finalGallery[] = [
                        'url' => (string) $item['url'],
                        'caption' => ! empty($item['caption']) ? trim((string) $item['caption']) : $mountainName,
                    ];
                }
            }
        }

        if ($request->hasFile('gallery_files')) {
            $files = $request->file('gallery_files');
            $captions = $request->input('gallery_file_captions', []);

            foreach ($files as $idx => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('mountains/gallery', 'public');
                    $caption = ! empty($captions[$idx]) ? trim((string) $captions[$idx]) : "{$mountainName} Foto ".(count($finalGallery) + 1);
                    $finalGallery[] = [
                        'url' => Storage::url($path),
                        'caption' => $caption,
                    ];
                }
            }
        }

        // Hapus file fisik lama di storage jika dihapus dari galeri
        $oldStoredUrls = collect($mountain->gallery ?? [])
            ->pluck('url')
            ->filter(fn ($url) => is_string($url) && Str::startsWith($url, '/storage/mountains/gallery/'));
        $newUrls = collect($finalGallery)->pluck('url');

        foreach ($oldStoredUrls as $oldUrl) {
            if (! $newUrls->contains($oldUrl)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldUrl));
            }
        }

        return $finalGallery;
    }
}
