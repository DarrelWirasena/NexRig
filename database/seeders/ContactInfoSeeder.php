<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        ContactInfo::insert([
            [
                'type'          => 'email',
                'label'         => 'Email Support',
                'title'         => null,
                'value'         => 'nexrigsupp0rt@gmail.com',
                'url'           => 'mailto:nexrigsupp0rt@gmail.com',
                'display_value' => null,
                'is_active'     => true,
                'order'         => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'type'          => 'whatsapp',
                'label'         => 'WhatsApp Chat',
                'title'         => null,
                'value'         => '+62 895-0709-4710',
                'url'           => 'https://wa.me/6289507094710',
                'display_value' => null,
                'is_active'     => true,
                'order'         => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'type'          => 'address',
                'label'         => 'Headquarters',
                'title'         => 'NexRig Experience Center',
                'value'         => "Jl. Kanal No. 5, Lamper Lor\nSemarang Selatan, 50132\nJawa Tengah, Indonesia",
                'url'           => 'https://www.google.com/maps/search/?api=1&query=-7.000663,110.437499',
                'display_value' => 'Lihat di Peta',
                'is_active'     => true,
                'order'         => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}