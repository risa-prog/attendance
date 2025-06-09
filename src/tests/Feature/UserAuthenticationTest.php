<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;

class UserAuthenticationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
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

    public function test_login_user_validate_email()
    {
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    public function test_login_user_validate_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'test@gmail.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    public function test_login_user_validate_user()
    {
        $response = $this->post('/admin/login', [
            'email' => "test2@gmail.com",
            'password' => "password2",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません', $errors->first('email'));
    }
}
