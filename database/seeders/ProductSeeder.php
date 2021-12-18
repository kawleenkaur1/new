<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'category_id'=>1,
            'name'=>'Tomato',
            'image'=>'tomato.png',
            'mrp'=>30,
            'discount'=>0,
            'selling_price'=>27,
            'subscription_price'=>23,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>1,
            'name'=>'Apple',
            'image'=>'apple.png',
            'mrp'=>30,
            'discount'=>0,
            'selling_price'=>27,
            'subscription_price'=>23,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>1,
            'name'=>'Potato',
            'image'=>'potato.png',
            'mrp'=>29,
            'discount'=>0,
            'selling_price'=>25,
            'subscription_price'=>22,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>1,
            'name'=>'Veg',
            'image'=>'default.png',
            'mrp'=>29,
            'discount'=>0,
            'selling_price'=>25,
            'subscription_price'=>22,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);



        Product::create([
            'category_id'=>2,
            'name'=>'Tomato',
            'image'=>'tomato.png',
            'mrp'=>30,
            'discount'=>0,
            'selling_price'=>27,
            'subscription_price'=>23,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>2,
            'name'=>'Apple',
            'image'=>'apple.png',
            'mrp'=>30,
            'discount'=>0,
            'selling_price'=>27,
            'subscription_price'=>23,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>2,
            'name'=>'Potato',
            'image'=>'potato.png',
            'mrp'=>29,
            'discount'=>0,
            'selling_price'=>25,
            'subscription_price'=>22,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);

        Product::create([
            'category_id'=>2,
            'name'=>'Veg',
            'image'=>'default.png',
            'mrp'=>29,
            'discount'=>0,
            'selling_price'=>25,
            'subscription_price'=>22,
            'show_in_subscriptions'=>1,
            'stock'=>15,
            'qty'=>1,
            'unit'=>'kg',
            'position'=>1,
            'status'=>1,
            'mark_as_new'=>1
        ]);
    }
}
