<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\User;
use App\Models\Item;

class PurchaseAddressTest extends TestCase
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
    public function test_changed_shipping_address_is_displayed_on_purchase_page()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // 送付先住所変更のフォームに入力して送信
        $this->post("/purchase/address/{$item->id}", [
            'postcode' => '987-6543',
            'address' => '大阪府大阪市中央区1-1-1',
            'building' => '大阪マンション101',
        ]);

        // 購入画面再表示
        $response = $this->get("/purchase/{$item->id}");

        // 購入画面が正常に表示されることを確認
        $response->assertOk();

        // 変更した送付先住所が表示されていることを確認
        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市中央区1-1-1');
        $response->assertSee('大阪マンション101');
    }
    public function test_shipping_address_is_saved_with_purchase()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create([
            'is_sold' => false,
        ]);

        // ログイン
        $this->actingAs($user);

        // 送付先住所変更のフォームに入力して送信
        session([
            'purchase_data' => [
                'postcode' => '987-6543',
                'address' => '大阪府大阪市中央区1-1-1',
                'building' => '大阪マンション101',
                'payment_method' => 'card',
            ],
        ]);

        // 購入処理を実行
        $this->get(route('purchase.success', $item));

        // 購入処理後、送付先住所がデータベースに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postcode' => '987-6543',
            'address' => '大阪府大阪市中央区1-1-1',
            'building' => '大阪マンション101',
        ]);
    }
}
