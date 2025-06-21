<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 1 認証機能(一般ユーザー)

    // 1-1 名前が未入力の場合のバリデーション
    public function test_register_user_validate_name()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals('お名前を入力してください', $errors->first('name'));
    }

    //1-2 メールアドレスが未入力の場合のバリデーション
    public function test_register_user_validate_email() {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    //1-3 パスワードが8文字未満の場合のバリデーション
    public function test_register_user_validate_password_min()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@gmail.com',
            'password' => 'passwor',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors->first('password'));
    }

    // 1-4 パスワードが一致しない場合のバリデーション
    public function test_register_user_validate_password_same()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@gmail.com',
            'password' => 'password1',
            'password_confirmation' => 'password2',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password_confirmation');

        $errors = session('errors');
        $this->assertEquals('パスワードと一致しません', $errors->first('password_confirmation'));
    }

    // 1-5 パスワードが未入力の場合のバリデーション
    public function test_register_user_validate_password()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@gmail.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // 1-6 フォーム内容が入力されていた場合、データが正常に保存される
    public function test_register_user()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // $response->assertStatus(302);
        $response->assertRedirect('/email_verification');
        $this->assertDatabaseHas(User::class, [
            'name' => "山田太郎",
            'email' => "test@gmail.com",
        ]);
    }

    // 2 ログイン機能(一般ユーザー)

    // 2-1 メールアドレスが未入力の場合のバリデーション
    public function test_login_user_validate_email()
    {
        $user = User::factory()->create();

        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => $user->password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    // 2-2 パスワードが未入力の場合のバリデーション
    public function test_login_user_validate_password()
    {
        $user = User::factory()->create();
        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // 2-3 登録内容と一致しない場合のバリデーション
    public function test_login_user_validate_user()
    {
        $user = User::factory()->create();
        $response = $this->post('/admin/login', [
            'email' => $user->email.'1',
            'password' => $user->password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }
}
