<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ConditionsTableSeeder;

class ItemListTest extends TestCase
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
    public function test_all_items_are_displayed()
    {
        // 商品を作成
        $item1 = Item::factory()->create([
            'name' => 'テスト商品1',
        ]);

        $item2 = Item::factory()->create([
            'name' => 'テスト商品2',
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品名が表示されていることを確認
        $response->assertSee('テスト商品1');
        $response->assertSee('テスト商品2');
    }
    public function test_sold_label_is_displayed_for_sold_items()
    {
        // 売り切れ商品を作成
        $item = Item::factory()->create([
            'name' => '売り切れ商品',
            'is_sold' => true,
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // SOLDラベルと商品名が順番に表示されていることを確認
        $response->assertSeeInOrder([
            'SOLD',
            '売り切れ商品',
        ]);
    }
    public function test_user_items_are_not_displayed()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // 自分の商品を作成
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        // 他人の商品を作成
        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
        ]);

        // ログイン状態で商品一覧ページにアクセス
        $response = $this->actingAs($user)->get('/');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 自分の出品した商品が表示されていないことを確認（他人の商品は表示される）
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}
