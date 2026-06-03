<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ConditionsTableSeeder;

class SearchTest extends TestCase
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
    public function test_items_can_be_searched_by_partial_match()
    {
        // 商品を作成
        $item1 = Item::factory()->create([
            'name' => '腕時計',
        ]);

        $item2 = Item::factory()->create([
            'name' => '腕輪',
        ]);

        $item3 = Item::factory()->create([
            'name' => 'バッグ',
        ]);

        // 検索実行（検索キーワードは「腕」）
        $response = $this->get('/?keyword=腕');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 部分一致商品は表示
        $response->assertSee('腕時計');
        $response->assertSee('腕輪');

        // 関係ない商品は非表示
        $response->assertDontSee('バッグ');
    }
    public function test_search_keyword_is_retained_in_mylist()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create([
            'name' => '腕時計',
        ]);

        // いいね登録（ユーザーと商品を紐づける）
        $item->likedUsers()->attach($user->id);

        // ログインしてkeyword付きでマイリストアクセス
        $response = $this->actingAs($user)
            ->get('/?tab=mylist&keyword=腕');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 検索キーワードが入力欄に保持されていることを確認
        $response->assertSee('value="腕"', false);
    }
}
