<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\User;
use App\Models\Item;

class MyPageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }
    public function test_user_profile_information_is_displayed()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // プロフィール作成
        $user->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'profile_image' => 'test.jpg',
        ]);

        // 出品商品作成
        $sellingItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);

        // 購入商品作成
        $purchasedItem = Item::factory()->create([
            'name' => '購入商品',
        ]);

        // 購入情報作成
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        // マイページ表示
        $response = $this->actingAs($user)
            ->get('/mypage');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // プロフィール画像表示確認
        $response->assertSee('storage/profiles/test.jpg');

        // ユーザー名表示確認
        $response->assertSee('テストユーザー');

        // 出品商品表示確認
        $response->assertSee('出品商品');
    }
    public function test_purchased_items_are_displayed_on_buy_page()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // プロフィール作成
        $user->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'profile_image' => 'test.jpg',
        ]);

        // 購入商品作成
        $item = Item::factory()->create([
            'name' => '購入商品',
        ]);

        // 購入情報作成
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        // マイページの購入した商品タブを表示
        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 購入商品表示確認
        $response->assertSee('購入商品');
    }
}
