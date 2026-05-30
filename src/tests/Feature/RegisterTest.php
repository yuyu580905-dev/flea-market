<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_name_is_required()
    {
        // 登録フォームに必要なフィールドを送信して、nameフィールドが空の場合のエラーメッセージを検証
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // nameフィールドが空の場合のエラーメッセージを検証
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }
    public function test_email_is_required()
    {
        // 登録フォームに必要なフィールドを送信して、emailフィールドが空の場合のエラーメッセージを検証
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // emailフィールドが空の場合のエラーメッセージを検証
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }
    public function test_password_is_required()
    {
        // 登録フォームに必要なフィールドを送信して、passwordフィールドが空の場合のエラーメッセージを検証
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        // passwordフィールドが空の場合のエラーメッセージを検証
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }
    public function test_password_must_be_at_least_8_characters()
    {
        // 登録フォームに必要なフィールドを送信して、passwordフィールドが8文字未満の場合のエラーメッセージを検証
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        // passwordフィールドが8文字未満の場合のエラーメッセージを検証
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }
    public function test_password_confirmation_does_not_match()
    {
        // 登録フォームに必要なフィールドを送信して、password_confirmationフィールドがpasswordフィールドと一致しない場合のエラーメッセージを検証
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ]);

        // password_confirmationフィールドがpasswordフィールドと一致しない場合のエラーメッセージを検証
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }
    public function test_verification_email_is_sent_after_register()
    {
        // 実際にはメールを送信せず、送ったことにして記録だけする
        Notification::fake();

        // 登録フォームに必要なフィールドを送信して、ユーザーを作成
        $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録後、ユーザーが作成されていることを確認
        $user = User::where('email', 'test@example.com')->first();

        // 登録後、VerifyEmail通知がユーザーに送信されたことを確認
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }
    public function test_mailtrap_link_is_displayed()
    {
        /** @var \App\Models\User $user */
        // 未認証のユーザーを作成
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 未認証のユーザーでログインして、メール認証の案内ページにアクセス
        $response = $this->actingAs($user)
            ->get('/email/verify');

        $response->assertStatus(200);

        // メール認証の案内ページに、「メール認証はこちらから」ボタンとMailtrapのURLが存在することを確認
        $response->assertSee('認証はこちらから');
        $response->assertSee(
            'href="https://mailtrap.io/home"',
            false
        );
    }
    public function test_email_can_be_verified()
    {
        // イベントを偽装して、実際のイベントが発生したかどうかを検証できるようにする
        Event::fake();

        // 未認証のユーザーを作成
        $user = User::factory()->unverified()->create();

        // 一時的な署名付きURLを生成して、ユーザーのメールアドレスを検証するためのリンクを作成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        // 生成したURLにアクセスして、メールアドレスの検証を行う
        $response = $this->actingAs($user)->get($verificationUrl);

        // Verifiedイベントが発生したことを検証
        Event::assertDispatched(Verified::class);

        // ユーザーのメールアドレスが検証された（認証が完了した）ことを確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // メールアドレスの検証後、ユーザーがマイページのプロフィールにリダイレクトされることを確認
        $response->assertRedirect('/mypage/profile');
    }
}
