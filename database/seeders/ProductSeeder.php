<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Samsung Galaxy A55',
                'description' => 'Samsung Brand',
                'image' => 'https://media.u-mall.com.tw/nximg/007413/7413068/7413068_xxl.jpg?t=20971295457',
                'price' => 100
            ],
            [
                'name' => 'Apple iPhone 12',
                'description' => 'Apple Brand',
                'image' => 'https://kryinternational.com/wp-content/uploads/2024/08/iphone-12-official.jpg',
                'price' => 500
            ],
            [
                'name' => 'Google Pixel 2 XL',
                'description' => 'Google Pixel Brand',
                'image' => 'https://spigen.ph/sites/default/files/styles/product_details/public/images/products/details/groupphoto_311.jpg?itok=f65qJE1n',
                'price' => 400
            ],
            [
                'name' => 'LG V10 H800',
                'description' => 'LG Brand',
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtLDHXw-MKrGm2LjGh4XA1ql7Y1e-ANGHHaQ&s',
                'price' => 200
            ]
        ];

        foreach ($products as $key => $value) {
            Product::create($value);
        }
    }
}
