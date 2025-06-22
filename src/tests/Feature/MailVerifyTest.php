<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;

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
    
    // 応用 メール認証機能

    // 会員登録後、認証メールが送信される
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

    // 送られてきたメール認証リンクを押して認証を完了させると、メール認証完了画面に遷移(仕様が異なるため、テストの内容と順番が変わっています)
    public function test_user_can_verify_email_and_shift_email_verification_complete_page() {

    }

    // メール認証完了後、勤怠画面に遷移する
    public function test_shift_attendance_page_after_email_verification() {
        
    }




    // メール認証リンクは、新規登録後にmailhogにメールで送られてくるので、メール認証誘導画面の「認証はこちらから」ボタンからメール認証サイトに遷移することはできません。
}
