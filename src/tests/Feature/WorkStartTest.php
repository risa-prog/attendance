<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Admin;
use App\Http\Middleware\VerifyCsrfToken;

class WorkStartTest extends TestCase
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

    //  6 出勤機能確認

    // 6-1 出勤ボタンが正しく機能する
    public function test_confirm_the_work_start_function()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('<button class="timestamp__condition-working">出勤</button>', false);

        $response = $this->followingRedirects()->post('/timestamp/work_start');

        $response->assertSee('出勤中');
    }

    // 6-2 出勤は一日一回のみできる
    public function test_confirm_the_work_start_function_only_once_a_day() {
        $user = User::factory()->create();
        $date = Carbon::now()->toDateString();
        Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertDontSee('<button class="timestamp__condition-working">出勤</button>', false);
    }

    // 6-3 出勤時刻が管理画面で確認できる
    public function test_admin_can_view_the_work_start_time() {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/attendance');

        $response = $this->post('/timestamp/work_start');

        $admin = Admin::factory()->create();
        $date = Carbon::now()->toDateString();
        $work = Work::where('user_id',$user->id)->where('date',$date)->first();
        $response = $this->actingAs($admin,'admin')->get('admin/attendance/list');
        $response->assertSeeInOrder([
            $date,
            substr($work->work_start, 0, 5),
        ]);
    }
}
