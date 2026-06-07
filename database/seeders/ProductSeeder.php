<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'image_url'     =>  'laptops/ASUS_ProArt_Studiobook_16.png',
            ],
            [
                'name'          =>  'ASUS ROG Strix G16 G614',
                'description'   =>  'laptop gaming premium berperforma tinggi yang dirancang untuk gamer dan kreator serius.',
                'price'         =>  26000000,
                'stock'         =>  20,
                'image_url'     =>  'laptops/ASUS_ROG_Strix_G16_G614.png',
            ],
            [
                'name'          =>  'Lenovo Legion 5i Pro Gen 8',
                'description'   =>  'laptop gaming premium yang ditenagai prosesor Intel Core Generasi ke-13 (hingga i7 atau i9 HX-Series) dan GPU NVIDIA GeForce RTX 40-Series.',
                'price'         =>  30000000,
                'stock'         =>  5,
                'image_url'     =>  'laptops/Lenovo_Legion_5i_Pro_Gen_8.png',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
