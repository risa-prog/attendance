<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class MailVerifyTest extends TestCase
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

        // CSRF チェックのみ無効にする
        $this->withoutMiddleware([
            VerifyCsrfToken::class,
        ]);
    }
    
    // 応用16 メール認証機能

    // 16-1 会員登録後、認証メールが送信される
    public function test_confirm_verification_email_after_new_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/email_verification');

        $user = User::where('email', 'test@gmail.com')->first();

        // VerifyEmail 通知が送信されたか確認
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    // 16-2 メール認証誘導画面で「認証はこちらから」ボタンを押すとメール認証サイトに遷移する
    // public function test_user_can_verify_email_and_shift_email_verification_complete_page() {
    //     $response = $this->post('/register', [
    //         'name' => '山田太郎',
    //         'email' => 'test@gmail.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //     ]);

    //     $response->assertRedirect('/email_verification');

    //     $response  = Http::get('http://localhost:8025');
    //     $response->assertStatus(200);
    // }
}
