<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name'          =>  'ASUS ProArt Studiobook 16',
                'description'   =>  'laptop kelas workstation yang dirancang khusus untuk kreator profesional seperti editor video dan desainer 3D',
                'price'         =>  40000000,
                'stock'         =>  12,
                'image_url'     =>  'storage/laptops/ASUS ProArt Studiobook 16.png',
            ],
            [
                'name'          =>  'ASUS ROG Strix G16 G614',
                'description'   =>  'laptop gaming premium berperforma tinggi yang dirancang untuk gamer dan kreator serius.',
                'price'         =>  26000000,
                'stock'         =>  20,
                'image_url'     =>  'storage/laptops/ASUS ROG Strix G16 G614.png',
            ],
            [
                'name'          =>  'Lenovo Legion 5i Pro Gen 8',
                'description'   =>  'laptop gaming premium yang ditenagai prosesor Intel Core Generasi ke-13 (hingga i7 atau i9 HX-Series) dan GPU NVIDIA GeForce RTX 40-Series.',
                'price'         =>  30000000,
                'stock'         =>  5,
                'image_url'     =>  'storage/laptops/Lenovo Legion 5i Pro Gen 8.png',
            ],
        ];
    }
}
