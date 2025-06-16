<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\Admin;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // その日になされた全ユーザーの勤怠情報が正確か確認
    public function test_get_staff_attendance()
    {
        $admin = Admin::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $work1 = Work::factory()->create([
            'user_id' => $user1->id,
            'date' => now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $work2 = Work::factory()->create([
            'user_id' => $user2->id,
            'date' => now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest1 = Rest::factory()->create([
            'work_id' => $work1->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);
        $rest2 = Rest::factory()->create([
            'work_id' => $work2->id,
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);

        $response = $this->actingAs($admin,'admin')->get('admin/attendance/list');
        $response->assertStatus(200);

        $response->assertSee($user1->name);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $response->assertSee($user2->name);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        // 現在の日付が表示されている
        $date = Carbon::now()->format('Y/m/d');
        $response->assertSee($date);
    }   

    // 前日の勤怠情報
    public function test_get_staff_previous_month_attendance() {

    }

    // 翌日の勤怠情報
    // test_get_staff_next_month_attendance() {
        
    // }
}
