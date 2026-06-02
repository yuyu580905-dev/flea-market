<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;

class ExhibitionTest extends TestCase
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
        $this->seed(CategoriesTableSeeder::class);
    }
    public function test_item_information_can_be_saved()
    {
        // ストレージのモックを作成
        Storage::fake('public');

        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // ログインして商品出品のリクエストを送信
        $response = $this->actingAs($user)
            ->post('/sell', [
                'categories' => [1, 2],
                'condition_id' => 1,
                'name' => 'テスト商品',
                'brand' => 'テストブランド',
                'description' => 'テスト商品の説明',
                'price' => 5000,
                'image' => UploadedFile::fake()->create(
                    'test.jpg', // ファイル名
                    100, // ファイルサイズ（KB）
                    'image/jpeg' // MIMEタイプ（JPEG画像として扱う）
                ),
            ]);

        // リダイレクト確認
        $response->assertRedirect('/');

        // 商品保存確認（商品の状態、商品名、ブランド名、商品の説明、販売価格が保存されているか）
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => 1,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
        ]);

        $item = Item::where('name', 'テスト商品')->first();

        // カテゴリ保存確認（カテゴリ1とカテゴリ2が保存されているか）
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => 1,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => 2,
        ]);
    }
}
