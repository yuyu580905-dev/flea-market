<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_profile_edit_page_displays_current_user_information()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // プロフィール作成
        $user->profile()->create([
            'profile_image' => 'test.jpg',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // ログインしてプロフィール編集ページにアクセス
        $response = $this->actingAs($user)
            ->get('/mypage/profile');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // プロフィール画像の初期値確認
        $response->assertSee('storage/profiles/test.jpg');

        // ユーザー名の初期値確認
        $response->assertSee('value="テストユーザー"', false);

        // 郵便番号の初期値確認
        $response->assertSee('value="123-4567"', false);

        // 住所の初期値確認
        $response->assertSee('value="東京都渋谷区"', false);
    }
}
