<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;

class ItemDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_item_detail_page_displays_all_required_information()
    {
        // 商品の状態を作成
        $condition = Condition::create([
            'name' => 'テスト状態',
        ]);

        // コメントしたユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // カテゴリを作成
        $category = Category::create([
            'name' => 'ファッション',
        ]);

        // 商品を作成
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュな腕時計です',
            'image' => 'dummy.jpg',
        ]);

        // 商品とカテゴリの紐付け（多対多）
        $item->categories()->attach($category->id);

        // いいね登録（pivotでユーザーと商品を紐づける）
        $item->likedUsers()->attach($user->id);

        // コメントを作成
        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素敵な商品ですね！',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品画像
        $response->assertSee('dummy.jpg');

        // 商品名
        $response->assertSee('腕時計');

        // ブランド名
        $response->assertSee('Rolax');

        // 価格
        $response->assertSee('15,000');

        // 商品説明
        $response->assertSee('スタイリッシュな腕時計です');

        // カテゴリ
        $response->assertSee('ファッション');

        // 商品状態
        $response->assertSee('テスト状態');

        // コメントしたユーザー
        $response->assertSee('テストユーザー');

        // コメント内容
        $response->assertSee('素敵な商品ですね！');

        // いいね数
        $response->assertSee(
            '<span class="item-detail__meta-count js-like-count">',
            false
        );

        // コメント数（アイコン下）
        $response->assertSee(
            'class="item-detail__meta-count js-comment-meta-count"',
            false
        );

        // コメント数（見出し）
        $response->assertSee(
            '<span class="js-comment-count">1</span>',
            false
        );
    }

    public function test_multiple_categories_are_displayed_on_item_detail_page()
    {
        // 商品の状態を作成
        $condition = Condition::create([
            'name' => '良好',
        ]);

        // 商品を作成
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        // 複数のカテゴリを作成して商品に紐付ける
        $category1 = Category::create([
            'name' => '家電',
        ]);
        $category2 = Category::create([
            'name' => 'インテリア',
        ]);
        $category3 = Category::create([
            'name' => 'スポーツ',
        ]);

        // 商品と複数のカテゴリを紐付ける
        $item->categories()->attach([
            $category1->id,
            $category2->id,
            $category3->id,
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 各カテゴリ名が表示されていることを確認
        $response->assertSee('家電');
        $response->assertSee('インテリア');
        $response->assertSee('スポーツ');
    }
}
