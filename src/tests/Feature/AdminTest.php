<?php

namespace Tests\Feature;

use Database\Seeders\AdminsTableSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class AdminTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseMigrations;

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
    public function test_show_admin_attendance_list()
    {
        // その日の全ユーザーの勤怠情報
        $admin = Admin::find(1);
        $response = $this->actingAs($admin)->get('/admin/attendance/list');
        // $works = 

        // 現在の日付が表示されてる
        $date = Carbon::now()->format('Y年n月j日');
        $response->assertSee($date);

        // 前日の勤怠情報

        // 翌日の勤怠情報
        
    }

    // public function test_admin_attendance_detail_form_validate() {

    // }

    public function test_get_staff_information() {
        $response = $this->get('/admin/staff/list');

        $response->assertStatus(200);
        $response->assertViewHas('users', User::all());
    }

    // public function test_admin_correct() {

    // }
}
