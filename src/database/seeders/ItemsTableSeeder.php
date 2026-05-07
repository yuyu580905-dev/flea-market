<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Condition;

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
                'image' => 'clock.jpg',
                'condition' => '良好',
                'categories' => [1, 5],
                'is_sold' => true,
            ],

            [
                'user_id' => 2,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'hdd.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories' => [2],
            ],

            [
                'user_id' => 3,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'onion.jpg',
                'condition' => 'やや傷や汚れあり',
                'categories' => [10],
            ],

            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'shoes.jpg',
                'condition' => '状態が悪い',
                'categories' => [1, 5],
            ],

            [
                'user_id' => 2,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image' => 'laptop.jpg',
                'condition' => '良好',
                'categories' => [2],
            ],

            [
                'user_id' => 3,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'mic.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories' => [2],
                'is_sold' => true,
            ],

            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'bag.jpg',
                'condition' => 'やや傷や汚れあり',
                'categories' => [1, 4],
            ],

            [
                'user_id' => 2,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image' => 'tumbler.jpg',
                'condition' => '状態が悪い',
                'categories' => [10],
                'is_sold' => true,
            ],

            [
                'user_id' => 3,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'grinder.jpg',
                'condition' => '良好',
                'categories' => [10],
                'is_sold' => true,
            ],

            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image' => 'makeup.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories' => [6],
            ],

        ];

        foreach ($items as $itemData) {

            $categories = $itemData['categories'];
            $conditionName = $itemData['condition'];
            unset($itemData['categories'], $itemData['condition']);

            $condition = Condition::where('name', $conditionName)->first();
            $itemData['condition_id'] = $condition->id;

            $item = Item::create($itemData);
            $item->categories()->attach($categories);
        }
    }
}
