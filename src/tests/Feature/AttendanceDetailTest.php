<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_name()
    {
        // 名前がログインユーザーのなっている
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->subDay()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response->assertSee($user->name);
    }

    public function test_show_date()
    {
        // 日付が選択したものになっている
        $user = User::factory()->create();
        $yesterday = now()->subDay()->toDateString();
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
    public function test_attendance_detail_shows_accurate_times() {
        $user = User::factory()->create();
        $yesterday = now()->subDay()->toDateString();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);


        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        // 出勤・退勤時間
        $response->assertSee($work->start_time);
        $response->assertSee($work->end_time);

        // 休憩時間
        $response->assertSee($rest->start_time);
        $response->assertSee($rest->end_time);
    }
}
