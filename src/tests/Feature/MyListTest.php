<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ConditionsTableSeeder;

class MyListTest extends TestCase
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
    public function test_only_liked_items_are_displayed_in_mylist()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $likedItem = Item::factory()->create([
            'name' => '腕時計',
        ]);

        $notLikedItem = Item::factory()->create([
            'name' => 'バッグ',
        ]);

        // いいね登録
        $likedItem->likedUsers()->attach($user->id);

        // マイリスト表示
        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // いいねした商品は表示
        $response->assertSee('腕時計');

        // いいねしてない商品は非表示
        $response->assertDontSee('バッグ');
    }
    public function test_sold_label_is_displayed_for_purchased_items()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // 売り切れ商品作成
        $soldItem = Item::factory()->create([
            'name' => '腕時計',
            'is_sold' => true,
        ]);

        // いいね登録
        $soldItem->likedUsers()->attach($user->id);

        // ログインしてマイリスト表示
        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);

        // 売り切れ商品の名前とSOLDラベルが表示されることを確認
        $response->assertSee('腕時計');
        $response->assertSee('SOLD');
    }
    public function test_nothing_is_displayed_for_guest_users_in_mylist()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '腕時計',
        ]);

        // いいね登録
        $item->likedUsers()->attach($user->id);

        // ゲストユーザーとしてマイリスト表示
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        // ゲストユーザーには商品が表示されないことを確認
        $response->assertDontSee('腕時計');
    }
}
