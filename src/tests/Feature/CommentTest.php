<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionsTableSeeder;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }
    public function test_authenticated_user_can_post_comment()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログイン済みユーザーとしてコメントを送信
        $response = $this->actingAs($user)
            ->postJson("/comment/{$item->id}", [
                'comment' => 'テストコメント',
            ]);

        // レスポンスが成功であることを確認
        $response->assertOk();

        // データベースにコメントが保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        // データベースにコメントが1件保存されていることを確認
        $this->assertDatabaseCount('comments', 1);
    }

    public function test_guest_cannot_post_comment()
    {
        // アイテムを作成
        $item = Item::factory()->create();

        // ゲストユーザーとしてコメントを送信
        $response = $this->post("/comment/{$item->id}", [
            'comment' => 'テストコメント',
        ]);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');
    }
    public function test_comment_is_required()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログイン済みユーザーとして空のコメントを送信
        $response = $this->actingAs($user)
            ->postJson("/comment/{$item->id}", [
                'comment' => '',
            ]);

        // バリデーションエラーが発生することを確認
        $response->assertStatus(422);

        // バリデーションエラーの内容を確認
        $response->assertJsonValidationErrors([
            'comment',
        ]);
    }
    public function test_comment_must_be_within_255_characters()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // アイテムを作成
        $item = Item::factory()->create();

        // ログイン済みユーザーとして255文字を超えるコメントを送信
        $response = $this->actingAs($user)
            ->postJson("/comment/{$item->id}", [
                'comment' => str_repeat('a', 256),
            ]);

        // バリデーションエラーが発生することを確認
        $response->assertStatus(422);

        // バリデーションエラーの内容を確認
        $response->assertJsonValidationErrors([
            'comment',
        ]);
    }
}
