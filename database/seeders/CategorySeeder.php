<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $create1 = Category::create([
            'name'=>'Fruits',
            'image'=>'default2.png',
            'status'=>1
        ]);

        $create2 = Category::create([
            'name'=>'Vegetables',
            'image'=>'default.png',
            'status'=>1
        ]);

        $create3 = Category::create([
            'name'=>'Milk & Diary',
            'image'=>'default.png',
            'status'=>1
        ]);

        $create4 = Category::create([
            'name'=>'Bakery Products',
            'image'=>'default.png',
            'status'=>1
        ]);

        $create5 = Category::create([
            'name'=>'Fruits & Vegetables',
            'image'=>'default.png',
            'status'=>1
        ]);

        $create6 = Category::create([
            'name'=>'Foodgrains, Oils & Masalas',
            'image'=>'default.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create1->id,
            'name'=>'Apple Fruits',
            'image'=>'apple.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create1->id,
            'name'=>'Banana Fruits',
            'image'=>'banana.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create1->id,
            'name'=>'Daily Vegetables',
            'image'=>'potato.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create1->id,
            'name'=>'Exotic Corner',
            'image'=>'tomato.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create2->id,
            'name'=>'Apple Fruits',
            'image'=>'apple.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create2->id,
            'name'=>'Banana Fruits',
            'image'=>'banana.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create2->id,
            'name'=>'Daily Vegetables',
            'image'=>'potato.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create2->id,
            'name'=>'Exotic Corner',
            'image'=>'tomato.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create5->id,
            'name'=>'Apple Fruits',
            'image'=>'apple.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create5->id,
            'name'=>'Banana Fruits',
            'image'=>'banana.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create5->id,
            'name'=>'Daily Vegetables',
            'image'=>'potato.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create5->id,
            'name'=>'Exotic Corner',
            'image'=>'tomato.png',
            'status'=>1
        ]);



        Subcategory::create([
            'category_id'=>$create6->id,
            'name'=>'Aata',
            'image'=>'aata.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create6->id,
            'name'=>'Rice',
            'image'=>'rice.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create6->id,
            'name'=>'Dals & Pulses',
            'image'=>'dals.png',
            'status'=>1
        ]);

        Subcategory::create([
            'category_id'=>$create6->id,
            'name'=>'Dry Fruits',
            'image'=>'tomato.png',
            'status'=>1
        ]);
    }
}
