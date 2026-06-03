<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_email_is_required_for_login()
    {
        // ログインページからのリクエストで、メールアドレスが空の場合のテスト
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
    public function test_password_is_required_for_login()
    {
        // ログインページからのリクエストで、パスワードが空の場合のテスト
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
    public function test_users_cannot_login_with_invalid_credentials()
    {
        // ログインページからのリクエストで、無効な資格情報の場合のテスト
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrong-password',
        ]);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
    public function test_users_can_login()
    {
        // 事前にユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインページからのリクエストで、正しい資格情報の場合のテスト
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // ユーザーが認証されていることを確認
        $this->assertAuthenticated();

        // 認証されたユーザーが正しいこと（誰としてログインしたか）を確認
        $this->assertAuthenticatedAs($user);

        // ホームページにリダイレクトされることを確認
        $response->assertRedirect('/');
    }
}
