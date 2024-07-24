<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Sample Product 1',
            'price' => 19.99,
            'description' => 'Description for sample product 1',
        ]);

        Product::create([
            'name' => 'Sample Product 2',
            'price' => 29.99,
            'description' => 'Description for sample product 2',
        ]);
    }
}
