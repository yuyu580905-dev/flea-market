<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
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
    public function test_purchase_is_completed()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログインして購入処理を実行
        $response = $this->actingAs($user)
            ->withSession([
                'purchase_data' => [
                    'postcode' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                    'payment_method' => 'card',
                ],
            ])
            ->get("/purchase/{$item->id}/success");

        // 購入完了後にトップページにリダイレクトされることを確認
        $response->assertRedirect('/');

        // データベースに購入記録が保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_is_marked_as_sold()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログインして購入処理を実行
        $this->actingAs($user)
            ->withSession([
                'purchase_data' => [
                    'postcode' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                    'payment_method' => 'card',
                ],
            ])
            ->get("/purchase/{$item->id}/success");

        // アイテムが売り切れ状態に更新されていることを確認
        $this->assertTrue($item->fresh()->is_sold);
    }

    public function test_purchased_item_is_displayed_in_purchase_history()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログインして購入処理を実行
        $this->actingAs($user)
            ->withSession([
                'purchase_data' => [
                    'postcode' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                    'payment_method' => 'card',
                ],
            ])
            ->get("/purchase/{$item->id}/success");

        // プロフィール画面（購入履歴ページ）にアクセスして、購入したアイテムが表示されていることを確認
        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee($item->name);
    }
}
