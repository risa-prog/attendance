<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;


class AttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    // 9 勤怠一覧情報取得機能(一般ユーザー)

    public function test_confirm_user_attendance_information()
    {
        // 9-1 勤怠情報が全て表示されている
        $user = User::factory()->create();

        $work1 = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->startOfMonth()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $work2 = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->startOfMonth()->addDays(2)->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
    
        Rest::factory()->create([
            'work_id' => $work1->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);
        Rest::factory()->create([
            'work_id' => $work2->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertStatus(200);

        $response->assertSee(
            Carbon::parse($work1->date)->translatedFormat('m/d(D)')
        );
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $response->assertSee(
        Carbon::parse($work2->date)->translatedFormat('m/d(D)'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        // 9-2 現在の月が表示されている
        $date = Carbon::now();
        $date->translatedFormat('Y/m(D)');
        $response->assertSee($date);
    }

    // 9-3「前月」を押した時に前月の情報が表示される
    public function test_confirm_user_attendance_information_previous_month() {
        $user = User::factory()->create();
        $this_month = Carbon::now()->toDateString();
        $previous_month = Carbon::now()
        ->copy()
        ->subMonthNoOverflow()->startOfMonth()
        ->addDay()
        ->toDateString();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $previous_month,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/list?tab=previous&date={$this_month}");
        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    // 9-4 「翌月」を押した時に翌月の情報が表示される
    public function test_show_next_month_attendance()
    {
        $user = User::factory()->create();
        $this_month = Carbon::now()->toDateString();
        $next_month = Carbon::now()
            ->copy()
            ->addMonthNoOverflow()->startOfMonth()
            ->addDay()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $next_month,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/list?tab=next&date={$this_month}");
        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    // 9-5「詳細」を押すと勤怠詳細画面に遷移
    public function test_show_attendance_detail() {
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->startOfMonth()->addDay()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');
        $response = $this->get("/attendance/{$work->id}");
        $response->assertStatus(200);
    }
}
