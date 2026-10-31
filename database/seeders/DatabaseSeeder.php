<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Destination;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin & Demo User — firstOrCreate agar idempotent ─────
        User::firstOrCreate(['email' => 'admin@wisata.com'], [
            'name'     => 'Administrator',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
        User::firstOrCreate(['email' => 'user@wisata.com'], [
            'name'     => 'Pengunjung Demo',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // ── Kategori ──────────────────────────────────────────────
        $categories = [
            ['name' => 'Pantai',  'name_en' => 'Beach',       'slug' => 'pantai',  'icon' => 'fa-umbrella-beach', 'color' => '#3b82f6'],
            ['name' => 'Gunung',  'name_en' => 'Mountain',    'slug' => 'gunung',  'icon' => 'fa-mountain',        'color' => '#10b981'],
            ['name' => 'Budaya',  'name_en' => 'Culture',     'slug' => 'budaya',  'icon' => 'fa-landmark',        'color' => '#f59e0b'],
            ['name' => 'Kuliner', 'name_en' => 'Culinary',    'slug' => 'kuliner', 'icon' => 'fa-utensils',        'color' => '#ef4444'],
            ['name' => 'Religi',  'name_en' => 'Religious',   'slug' => 'religi',  'icon' => 'fa-place-of-worship','color' => '#8b5cf6'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ── Fasilitas ─────────────────────────────────────────────
        $facilities = [
            ['name' => 'Toilet Umum',   'name_en' => 'Public Toilet',  'icon' => 'fa-restroom'],
            ['name' => 'Area Parkir',   'name_en' => 'Parking Area',   'icon' => 'fa-parking'],
            ['name' => 'Mushola',       'name_en' => 'Prayer Room',    'icon' => 'fa-mosque'],
            ['name' => 'Warung/Kafe',   'name_en' => 'Food Stall',     'icon' => 'fa-coffee'],
            ['name' => 'Penginapan',    'name_en' => 'Accommodation',  'icon' => 'fa-bed'],
            ['name' => 'WiFi',          'name_en' => 'WiFi',           'icon' => 'fa-wifi'],
            ['name' => 'Camping Area',  'name_en' => 'Camping Area',   'icon' => 'fa-campground'],
            ['name' => 'Penyewaan Alat','name_en' => 'Equipment Rent', 'icon' => 'fa-tools'],
        ];
        foreach ($facilities as $fac) {
            Facility::firstOrCreate(['name' => $fac['name']], $fac);
        }

        // ── Sample Destinations ───────────────────────────────────
        $d1 = Destination::firstOrCreate(['slug' => 'pantai-parangtritis'], [
            'category_id'    => 1,
            'user_id'        => 1,
            'name'           => 'Pantai Parangtritis',
            'name_en'        => 'Parangtritis Beach',
            'slug'           => 'pantai-parangtritis',
            'description'    => 'Pantai ikonik Yogyakarta dengan pemandangan matahari terbenam yang memukau dan legenda Ratu Selatan.',
            'description_en' => 'Yogyakarta\'s iconic beach with breathtaking sunsets and the legend of the South Queen.',
            'address'        => 'Parangtritis, Kretek, Bantul',
            'region'         => 'Bantul',
            'province'       => 'DI Yogyakarta',
            'latitude'       => -8.0256,
            'longitude'      => 110.3316,
            'thumbnail'      => 'destinations/thumbnails/parangtritis.jpg',
            'open_hours'     => '24 Jam',
            'ticket_price'   => 'Rp 10.000',
            'ticket_price_en'=> 'IDR 10,000',
            'rating_avg'     => 4,
            'review_count'   => 128,
            'view_count'     => 3420,
            'is_popular'     => true,
            'is_active'      => true,
        ]);
        // Tambah fasilitas: Toilet, Parkir, Warung
        $d1->facilities()->syncWithoutDetaching([1, 2, 4]);

        $d2 = Destination::firstOrCreate(['slug' => 'gunung-merapi'], [
            'category_id'    => 2,
            'user_id'        => 1,
            'name'           => 'Gunung Merapi',
            'name_en'        => 'Mount Merapi',
            'slug'           => 'gunung-merapi',
            'description'    => 'Gunung berapi aktif paling terkenal di Indonesia dengan pemandangan kawah yang menakjubkan.',
            'description_en' => 'Indonesia\'s most famous active volcano with spectacular crater views.',
            'address'        => 'Sleman, DI Yogyakarta',
            'region'         => 'Sleman',
            'province'       => 'DI Yogyakarta',
            'latitude'       => -7.5407,
            'longitude'      => 110.4457,
            'thumbnail'      => 'destinations/thumbnails/merapi.jpg',
            'open_hours'     => '05:00 - 17:00',
            'ticket_price'   => 'Rp 25.000',
            'ticket_price_en'=> 'IDR 25,000',
            'rating_avg'     => 5,
            'review_count'   => 256,
            'view_count'     => 8900,
            'is_popular'     => true,
            'is_active'      => true,
        ]);
        $d2->facilities()->syncWithoutDetaching([1, 2, 7, 8]);

        $d3 = Destination::firstOrCreate(['slug' => 'candi-borobudur'], [
            'category_id'    => 3,
            'user_id'        => 1,
            'name'           => 'Candi Borobudur',
            'name_en'        => 'Borobudur Temple',
            'slug'           => 'candi-borobudur',
            'description'    => 'Candi Buddha terbesar di dunia, warisan budaya UNESCO yang menakjubkan.',
            'description_en' => 'The world\'s largest Buddhist temple, a stunning UNESCO World Heritage Site.',
            'address'        => 'Borobudur, Magelang',
            'region'         => 'Magelang',
            'province'       => 'Jawa Tengah',
            'latitude'       => -7.6079,
            'longitude'      => 110.2038,
            'thumbnail'      => 'destinations/thumbnails/borobudur.jpg',
            'open_hours'     => '06:00 - 17:00',
            'ticket_price'   => 'Rp 50.000',
            'ticket_price_en'=> 'IDR 50,000',
            'rating_avg'     => 5,
            'review_count'   => 512,
            'view_count'     => 15600,
            'is_popular'     => true,
            'is_active'      => true,
        ]);
        $d3->facilities()->syncWithoutDetaching([1, 2, 3, 4, 6]);
    }
}
