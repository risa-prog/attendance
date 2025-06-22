<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Illuminate\Support\Carbon;
use App\Http\Middleware\VerifyCsrfToken;

class TimestampTest extends TestCase
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

    // 4 日時取得機能

    // 4-1 現在の日時情報がUIと同じ形式で出力されている
    public function test_get_correct_date_information()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/attendance');

        $day = Carbon::now()->translatedFormat('Y年n月j日(D)');
        $response->assertSee($day);
        // $time = Carbon::now()->format('H:i');
        // $response->assertSee($time);
    }

    // 5 ステータス確認機能

    // 5-1 勤務外の場合のステータス確認
    public function test_timestamp_status_off_duty() {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('勤務外');
    }

    // 5-2 出勤中の場合のステータス確認
    public function test_timestamp_status_working() {
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '',
            'status' => 1,
        ]);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('出勤中');
    }

    // 5-3 休憩中の場合のステータス確認
    public function test_timestamp_status_breaking() {
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '',
            'status' => 2,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '',
        ]);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('休憩中');
    }

    // 5-4 退勤済みの場合のステータス確認
    public function test_timestamp_status_finished_work() {
        $user = User::factory()->create();
        Work::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('退勤済');
    }
}
