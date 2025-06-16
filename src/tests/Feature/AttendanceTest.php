<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class AttendanceTest extends TestCase
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
    // public function test_show_attendance_list()
    // {
        

        // $date = Carbon::now();
        // $works = Work::where('user_id', $user->id)->whereMonth('date', $date->month)->get();
        // $response = $this->actingAs($user)->get('/attendance/list');
        // $response->assertStatus(200);
        // $response->assertViewHas('works', $works);
    // }

    public function test_show_attendance_detail() {
        // 名前がログインユーザーのものになっている
        $user = User::find(1);
        $work = Work::find(1);
        
        $response = $this->actingAs($user)->followingRedirects()->get('/attendance/1');
        // $response->assertSee('work', $work->user->name);

        // 日付が選択した日付と一致している
        $year = Carbon::now()->format('Y年');
        $day = Carbon::now()->format('n月j日');
        $response->assertSee([
            $year, $day,
        ]);
    }

    // public function attendance_detail_request() {

    // }
}
