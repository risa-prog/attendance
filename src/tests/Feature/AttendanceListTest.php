<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Database\Seeders\DatabaseSeeder;
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
    
    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->seed(DatabaseSeeder::class);
    // }
    
    public function test_show_attendance()
    {
        // 勤怠情報が全て表示されている
        $user = User::factory()->create();
    
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->startOfMonth()->addDay()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
    
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertStatus(200);
        
        $response->assertSee(
        Carbon::parse($work->date)->translatedFormat('m/d(D)'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        // 現在の月が表示されている
        $date = Carbon::now();
        $date->translatedFormat('Y/m(D)');
        $response->assertSee($date);
    }

    public function test_show_previous_month_attendance() {
        $user = User::factory()->create();
        $this_month = Carbon::now()->toDateString();
        $previous = Carbon::now()
        ->copy()
        ->subMonthNoOverflow()->startOfMonth()
        ->addDay()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $previous,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/list?tab=previous&date={$this_month}");
        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    // 翌月
    public function test_show_next_month_attendance()
    {
        $user = User::factory()->create();
        $this_month = Carbon::now()->toDateString();
        $next = Carbon::now()
            ->copy()
            ->addMonthNoOverflow()->startOfMonth()
            ->addDay()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $next,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/list?tab=next&date={$this_month}");
        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }



    // 勤怠詳細画面に遷移
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
