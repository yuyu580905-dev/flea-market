<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\User;
use App\Models\Item;

class PurchaseMethodTest extends TestCase
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

    /**
     * JavaScriptによる表示更新はFeatureTestでは検証できないため、
     * 支払い方法の選択肢が表示されることを確認する
     */
    public function test_payment_method_options_are_displayed()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログインして購入ページにアクセス
        $response = $this->actingAs($user)
            ->get("/purchase/{$item->id}");

        // 購入ページが正常に表示されることを確認
        $response->assertOk();

        // 支払い方法の選択肢が表示されていることを確認
        $response->assertSee('選択してください');
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード支払い');
    }
}
