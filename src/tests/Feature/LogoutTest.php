<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_users_can_logout()
    {
        /** @var \App\Models\User $user */
        // ユーザーを作成
        $user = User::factory()->create();

        // ユーザーとしてログイン→ログアウト
        $response = $this->actingAs($user)->post('/logout');

        // ログアウト後、ユーザーが認証されていないことを確認
        $this->assertGuest();

        // ログアウト後、リダイレクトされることを確認
        $response->assertRedirect('/');
    }
}
