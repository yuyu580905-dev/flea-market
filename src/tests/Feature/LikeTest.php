<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionsTableSeeder;

class LikeTest extends TestCase
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
    public function test_user_can_like_item()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create();

        // ログイン済ユーザーでいいね登録
        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        // 正常にレスポンスが返ってくることを確認
        $response->assertOk();

        // レスポンスのJSON構造を確認
        $response->assertJson([
            'liked' => true,
            'likes_count' => 1,
        ]);

        // データベースにいいねが保存されていることを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
    public function test_liked_item_displays_pink_heart_icon()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create();

        // いいね登録
        $item->likedUsers()->attach($user->id);

        // 商品詳細ページにアクセス
        $response = $this->actingAs($user)
            ->get("/item/{$item->id}");

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // いいねした商品にはピンクのハートアイコンが表示されることを確認
        $response->assertSee(
            'icon-heart-pink.png',
            false
        );
    }
    public function test_user_can_unlike_item()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create();

        // いいね登録
        $item->likedUsers()->attach($user->id);

        // ログイン済ユーザーでいいね解除
        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        // 正常にレスポンスが返ってくることを確認
        $response->assertOk();

        // レスポンスのJSON構造を確認
        $response->assertJson([
            'liked' => false,
            'likes_count' => 0,
        ]);

        // データベースからいいねが削除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
