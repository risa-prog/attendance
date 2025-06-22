<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class AdminStaffTest extends TestCase
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

    // 14 ユーザー情報取得機能(管理者)

    //14-1 管理者が全ユーザーの氏名とメールアドレスを確認できる
    public function test_admin_can_view_all_users_name_and_email() {
        $admin = Admin::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($admin,'admin')->get('/admin/staff/list');
        $response->assertStatus(200);

        $response->assertSee($user1->name);
        $response->assertSee($user1->email);

        $response->assertSee($user2->name);
        $response->assertSee($user2->email);
    }

    // 14-2 ユーザーの勤怠情報が正しく表示される
    public function test_admin_can_view_actual_user_attendance() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $date = Carbon::now()
        ->yesterday()
        ->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}");
        $response->assertStatus(200);

        $response->assertSee($date);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    // 14-3 前月の情報が表示される
    public function test_show_previous_month_staff_attendance() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $subMonth = Carbon::now()
            ->subMonthNoOverflow()
            ->startOfMonth()
            ->addDay()
            ->toDateString();
        $date = Carbon::now()  
            ->toDateString();
        
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $subMonth,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}?tab=previous&date={$date}");
        $response->assertStatus(200);

        $response->assertSee($subMonth);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    // 14-4 翌月の情報が表示される
    public function test_show_next_month_staff_attendance() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $nextMonth = Carbon::now()
            ->addMonthNoOverflow()->startOfMonth()
            ->addDay()
            ->toDateString();
        $date = Carbon::now() 
            ->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $nextMonth,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}?tab=next&date={$date}");
        $response->assertStatus(200);

        $response->assertSee($nextMonth);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    //14-5「詳細」を押すとその日の勤怠詳細画面に遷移
    public function test_show_the_day_attendance_detail() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $date = Carbon::now()->yesterday()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/staff/{$user->id}");
        $response->assertStatus(200);

        $response = $this->actingAs($admin, 'admin')->get("/attendance/{$work->id}");
        $response->assertStatus(200);
    }
}
