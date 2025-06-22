<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class AttendanceDetailTest extends TestCase
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

    // 10 勤怠詳細情報取得機能(一般ユーザー)

    // 10-1 名前がログインユーザーのものになっている
    public function test_user_can_view_users_name()
    {
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->yesterday()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response->assertSee($user->name);
    }

    // 10-2 日付が選択したものになっている
    public function test_user_can_view_selected_date()
    {
        $user = User::factory()->create();
        $yesterday = now()->yesterday()->toDateString();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $year = Carbon::parse($yesterday)->format('Y年');
        $day = Carbon::parse($yesterday)->format('n月j日');
        $response->assertSee($year);
        $response->assertSee($day);
    }

    // 正確な時間が表示されている
    public function test_user_can_view_accurate_time() {
        $user = User::factory()->create();
        $yesterday = now()->yesterday()->toDateString();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        // 10-3 出勤・退勤時間
        $response->assertSee(substr($work->work_start, 0, 5));
        $response->assertSee(substr($work->work_end, 0, 5));

        // 10-4 休憩時間
        $response->assertSee(substr($rest->rest_start, 0, 5));
        $response->assertSee(substr($rest->rest_end, 0, 5));
    }
}
