<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'storage/items/watch.jpg',
                'condition_id' => 1,
                'categories' => [1, 5]
            ],

            [
                'user_id' => 2,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'storage/items/hdd.jpg',
                'condition_id' => 2,
                'categories' => [2]
            ],

            [
                'user_id' => 3,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'storage/items/onion.jpg',
                'condition_id' => 3,
                'categories' => [10]
            ],

            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'storage/items/shoes.jpg',
                'condition_id' => 4,
                'categories' => [1, 5]
            ],

            [
                'user_id' => 2,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image' => 'storage/items/laptop.jpg',
                'condition_id' => 1,
                'categories' => [2]
            ],

            [
                'user_id' => 3,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'storage/items/mic.jpg',
                'condition_id' => 2,
                'categories' => [2]
            ],

            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'storage/items/bag.jpg',
                'condition_id' => 3,
                'categories' => [1, 4]
            ],

            [
                'user_id' => 2,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image' => 'storage/items/tumbler.jpg',
                'condition_id' => 4,
                'categories' => [10]
            ],

            [
                'user_id' => 3,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'storage/items/grinder.jpg',
                'condition_id' => 1,
                'categories' => [10]
            ],

            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image' => 'storage/items/makeup.jpg',
                'condition_id' => 2,
                'categories' => [6]
            ],

        ];


        foreach ($items as $itemData) {

            $categories = $itemData['categories'];

            unset($itemData['categories']);

            $item = Item::create($itemData);

            $item->categories()->attach($categories);
        }
    }
}
