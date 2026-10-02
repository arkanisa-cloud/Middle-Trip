<?php

namespace Database\Seeders;

use App\Enums\TrailGrade;
use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Database\Seeder;

class MountainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mountains = [
            [
                'name' => 'Mt. Merbabu',
                'slug' => 'mt-merbabu',
                'elevation' => 3142,
                'province' => 'Jawa Tengah',
                'cover_image' => '/storage/mountains/merbabu.jpeg',
                'description' => 'Bukit teletubbies yang hijau dan pemandangan sunrise di puncak terbaik berlatar megahnya Gunung Merapi.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 500000,
                'price_private' => 750000,
                'price_tektok' => 380000,
                'price_private_tektok' => 600000,
                'is_featured' => true,
                'featured_order' => 1,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sabana Merbabu Ridge'],
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=1200&auto=format&fit=crop', 'caption' => 'View Puncak Merapi'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Pendaki di Padang Rumput'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sabana Camp Pos 4'],
                ],
                'routes' => [
                    ['name' => 'Via Selo', 'slug' => 'via-selo', 'grade' => TrailGrade::GradeA, 'is_primary' => true, 'distance_km' => 13.8, 'duration_hours' => '6–7 Jam'],
                    ['name' => 'Via Suwanting', 'slug' => 'via-suwanting', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 14.5, 'duration_hours' => '8–9 Jam'],
                    ['name' => 'Via Thekelan', 'slug' => 'via-thekelan', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 15.0, 'duration_hours' => '7–8 Jam'],
                    ['name' => 'Via Wekas', 'slug' => 'via-wekas', 'grade' => TrailGrade::GradeA, 'is_primary' => false, 'distance_km' => 12.0, 'duration_hours' => '6–7 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Slamet',
                'slug' => 'mt-slamet',
                'elevation' => 3428,
                'province' => 'Jawa Tengah',
                'cover_image' => '/storage/mountains/mt-slamet.jpeg',
                'description' => 'Jalur menantang & cuaca ekstrem khusus pendaki berpengalaman di atap Jawa Tengah.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 950000,
                'price_private' => 1400000,
                'price_tektok' => 700000,
                'price_private_tektok' => 1100000,
                'is_featured' => true,
                'featured_order' => 2,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Pos Samarantu Slamet'],
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kawah Aktif Segoro Wedi'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Camp Plawangan'],
                    ['url' => 'https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Puncak Surono 3428 Mdpl'],
                ],
                'routes' => [
                    ['name' => 'Via Bambangan', 'slug' => 'via-bambangan', 'grade' => TrailGrade::GradeC, 'is_primary' => true, 'distance_km' => 18.2, 'duration_hours' => '10–12 Jam'],
                    ['name' => 'Via Guci', 'slug' => 'via-guci', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 16.5, 'duration_hours' => '9–10 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Sumbing',
                'slug' => 'mt-sumbing',
                'elevation' => 3371,
                'province' => 'Jawa Tengah',
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=800&auto=format&fit=crop',
                'description' => 'Melewati pos bowongso hingga sabana yang eksotis berhadapan dengan Gunung Sindoro.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 2100000,
                'price_private' => 2800000,
                'price_tektok' => 1600000,
                'price_private_tektok' => 2200000,
                'is_featured' => true,
                'featured_order' => 3,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kawah Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Punggung Naga Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Camp Pasukan Sumbing'],
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Puncak Rajawali'],
                ],
                'routes' => [
                    ['name' => 'Via Bowongso', 'slug' => 'via-bowongso', 'grade' => TrailGrade::GradeB, 'is_primary' => true, 'distance_km' => 13.0, 'duration_hours' => '7–8 Jam'],
                    ['name' => 'Via Garung', 'slug' => 'via-garung', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 14.2, 'duration_hours' => '8–9 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Sindoro',
                'slug' => 'mt-sindoro',
                'elevation' => 3153,
                'province' => 'Jawa Tengah',
                'cover_image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=900&auto=format&fit=crop',
                'description' => 'Trek bebatuan yang kokoh menuju kawah aktif dan lautan awan yang menakjubkan.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 650000,
                'price_private' => 900000,
                'price_tektok' => 480000,
                'price_private_tektok' => 750000,
                'is_featured' => false,
                'featured_order' => null,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kebun Teh Kledung'],
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Pos 3 Sindoro'],
                    ['url' => 'https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kawah Aktif Sindoro'],
                    ['url' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sunrise Lautan Awan'],
                ],
                'routes' => [
                    ['name' => 'Via Kledung', 'slug' => 'via-kledung', 'grade' => TrailGrade::GradeB, 'is_primary' => true, 'distance_km' => 11.5, 'duration_hours' => '7–8 Jam'],
                    ['name' => 'Via Sigedang', 'slug' => 'via-sigedang', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 12.0, 'duration_hours' => '7–8 Jam'],
                    ['name' => 'Via Bansari', 'slug' => 'via-bansari', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 10.5, 'duration_hours' => '6–7 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Prau',
                'slug' => 'mt-prau',
                'elevation' => 2590,
                'province' => 'Jawa Tengah',
                'cover_image' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=900&auto=format&fit=crop',
                'description' => 'Sunrise terindah di Jawa Tengah dengan pemandangan 360 derajat jajaran gunung kembar.',
                'has_open_trip' => false,
                'has_private_trip' => true,
                'base_price' => 650000,
                'price_private' => 650000,
                'price_tektok' => 450000,
                'price_private_tektok' => 550000,
                'is_featured' => false,
                'featured_order' => null,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Bukit Teletubbies Prau'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sunrise Golden Hour Prau'],
                    ['url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Camp Area Puncak Prau'],
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'View Gunung Sindoro Sumbing'],
                ],
                'routes' => [
                    ['name' => 'Via Patak Banteng', 'slug' => 'via-patak-banteng', 'grade' => TrailGrade::GradeA, 'is_primary' => true, 'distance_km' => 7.2, 'duration_hours' => '3–4 Jam'],
                    ['name' => 'Via Dieng', 'slug' => 'via-dieng', 'grade' => TrailGrade::GradeA, 'is_primary' => false, 'distance_km' => 8.0, 'duration_hours' => '4–5 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Lawu',
                'slug' => 'mt-lawu',
                'elevation' => 3265,
                'province' => 'Jawa Timur',
                'cover_image' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=900&auto=format&fit=crop',
                'description' => 'Pendakian mistis nan agung dengan warung tertinggi di Indonesia dekat puncak Hargo Dumilah.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 750000,
                'price_private' => 1100000,
                'price_tektok' => 550000,
                'price_private_tektok' => 850000,
                'is_featured' => false,
                'featured_order' => null,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Pos Sendang Drajat'],
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Warung Mbok Yem Lawu'],
                    ['url' => 'https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Tugu Hargo Dumilah'],
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Sabana Gupakan Menjangan'],
                ],
                'routes' => [
                    ['name' => 'Via Cemoro Sewu', 'slug' => 'via-cemoro-sewu', 'grade' => TrailGrade::GradeB, 'is_primary' => true, 'distance_km' => 13.5, 'duration_hours' => '7–8 Jam'],
                    ['name' => 'Via Cemoro Kandang', 'slug' => 'via-cemoro-kandang', 'grade' => TrailGrade::GradeB, 'is_primary' => false, 'distance_km' => 14.8, 'duration_hours' => '8–9 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Rinjani',
                'slug' => 'mt-rinjani',
                'elevation' => 3726,
                'province' => 'Nusa Tenggara Barat',
                'cover_image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=900&auto=format&fit=crop',
                'description' => 'Danau Segara Anak yang magis dan tantangan summit attack puncak gunung berapi tertinggi kedua.',
                'has_open_trip' => true,
                'has_private_trip' => true,
                'base_price' => 1850000,
                'price_private' => 2600000,
                'price_tektok' => 1400000,
                'price_private_tektok' => 2000000,
                'is_featured' => false,
                'featured_order' => null,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Danau Segara Anak'],
                    ['url' => 'https://images.unsplash.com/photo-1579618218290-24a26f63a708?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Plawangan Sembalun'],
                    ['url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Gunung Baru Jari'],
                    ['url' => 'https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Puncak Rinjani 3726 Mdpl'],
                ],
                'routes' => [
                    ['name' => 'Via Sembalun', 'slug' => 'via-sembalun', 'grade' => TrailGrade::GradeC, 'is_primary' => true, 'distance_km' => 22.0, 'duration_hours' => '12–14 Jam'],
                    ['name' => 'Via Senaru', 'slug' => 'via-senaru', 'grade' => TrailGrade::GradeC, 'is_primary' => false, 'distance_km' => 20.5, 'duration_hours' => '11–13 Jam'],
                ],
            ],
            [
                'name' => 'Mt. Papandayan',
                'slug' => 'mt-papandayan',
                'elevation' => 2665,
                'province' => 'Jawa Barat',
                'cover_image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=900&auto=format&fit=crop',
                'description' => 'Hutan mati eksotis, kawah belerang ramah pemula, dan padang edelweiss Tegal Alun.',
                'has_open_trip' => true,
                'has_private_trip' => false,
                'base_price' => 450000,
                'price_private' => null,
                'price_tektok' => 350000,
                'price_private_tektok' => null,
                'is_featured' => false,
                'featured_order' => null,
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Hutan Mati Papandayan'],
                    ['url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Kawah Belerang Papandayan'],
                    ['url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Padang Edelweiss Tegal Alun'],
                    ['url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop', 'caption' => 'Pondok Saladah Camp Area'],
                ],
                'routes' => [
                    ['name' => 'Via Cisurupan', 'slug' => 'via-cisurupan', 'grade' => TrailGrade::GradeA, 'is_primary' => true, 'distance_km' => 9.0, 'duration_hours' => '4–5 Jam'],
                ],
            ],
        ];

        foreach ($mountains as $mountainData) {
            $routes = $mountainData['routes'];
            unset($mountainData['routes']);

            $mountain = Mountain::updateOrCreate(
                ['slug' => $mountainData['slug']],
                $mountainData
            );

            foreach ($routes as $routeData) {
                Route::updateOrCreate(
                    [
                        'mountain_id' => $mountain->id,
                        'slug' => $routeData['slug'],
                    ],
                    $routeData
                );
            }
        }
    }
}
