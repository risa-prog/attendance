<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 3 ログイン認証機能(管理者)

    // 3-1 メールアドレス未入力の場合のバリデーション
    public function test_login_admin_validate_email()
    {
        $admin = Admin::factory()->create();
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => $admin->password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    // 3-2 パスワードが未入力の場合のバリデーション
    public function test_login_admin_validate_password()
    {
        $admin = Admin::factory()->create();
        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // 3-3 登録内容と一致しない場合のバリデーション
    public function test_login_admin_validate_user()
    {
        $admin = Admin::factory()->create();
        $response = $this->post('/admin/login', [
            'email' => $admin->email.'1',
            'password' => $admin->password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }
}
