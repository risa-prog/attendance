<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use App\Models\Rest;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;


class AdminCorrectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 15 勤怠情報修正機能(管理者)

    // 15-1 承認待ちの修正申請が全て表示される
    public function test_admin_can_view_all_waiting_for_approval_attendance()
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
            'work_start' => '10:00:00',
            'work_end' => '19:00:00',
            'status' => 3,
        ]);
        $work_correction1 = WorkCorrection::factory()->create([
            'user_id' => $user1->id,
            'work_id' => $work1->id,
            'work_start' => '10:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);
        $work_correction2 = WorkCorrection::factory()->create([
            'user_id' => $user2->id,
            'work_id' => $work2->id,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/stamp_correction_request/list?tab=waiting_for_approval');

        $response->assertSeeInOrder([
            '承認待ち',
            $work_correction1->user->name,
            $work_correction1->date,
        ]);

        $response->assertSeeInOrder([
            '承認待ち',
            $work_correction2->user->name,
            $work_correction2->date,
        ]);
    }

    // 15-2 承認済みの修正申請が全て表示される
    public function test_admin_can_view_all_approved_attendance() {
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
            'work_start' => '10:00:00',
            'work_end' => '19:00:00',
            'status' => 3,
        ]);
        $work_correction1 = WorkCorrection::factory()->create([
            'user_id' => $user1->id,
            'work_id' => $work1->id,
            'work_start' => '10:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 2,
        ]);
        $work_correction2 = WorkCorrection::factory()->create([
            'user_id' => $user2->id,
            'work_id' => $work2->id,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 2,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/stamp_correction_request/list?tab=approved');

        $response->assertSeeInOrder([
            '承認済み',
            $work_correction1->user->name,
            $work_correction1->date,
        ]);

        $response->assertSeeInOrder([
            '承認済み',
            $work_correction2->user->name,
            $work_correction2->date,
        ]);
    }

    // 15-3 修正申請の詳細内容が正しく表示されている
    public function test_admin_can_view_actual_correct_request_information() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);
        $work_correction = WorkCorrection::factory()->create([
            'user_id' => $user->id,
            'work_id' => $work->id,
            'work_start' => '10:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);
        $rest_correction = RestCorrection::factory()->create([
            'work_id' => $work->id,
            'rest_id' => $rest->id,
            'rest_start' => '13:00:00',
            'rest_end' => '14:00:00',
        ]);
        
        $response = $this->actingAs($admin, 'admin')->get("/stamp_correction_request/approve/{$work_correction->id}");
        $year = Carbon::parse($work_correction->work->date)->format('Y年');
        $day = Carbon::parse($work_correction->work->date)->format('n月j日');
        
        $response->assertSeeInOrder([
            $work_correction->user->name,
            $year,
            $day,
            substr($work_correction->work_start, 0, 5),
            substr($work_correction->work_end, 0, 5),
            substr($rest_correction->rest_start, 0, 5),
            substr($rest_correction->rest_end, 0, 5),
            $work_correction->note,
        ]); 
    }

    //15-4 修正申請の承認処理が正しく行われる
    public function test_confirm_approve_function() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);
        $work_correction = WorkCorrection::factory()->create([
            'user_id' => $user->id,
            'work_id' => $work->id,
            'work_start' => '10:00:00',
            'work_end' => '18:00:00',
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);
        $rest_correction = RestCorrection::factory()->create([
            'work_id' => $work->id,
            'rest_id' => $rest->id,
            'rest_start' => '13:00:00',
            'rest_end' => '14:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/stamp_correction_request/approve/{$work_correction->id}");
        $response->assertStatus(200);

        $response = $this->post('/stamp_correction_request/approve',[
            'work_id' => $work_correction->work_id,
            'work_start' => $work_correction->work_start,
            'work_end' => $work_correction->work_end,
            'rest_id' => [$rest_correction->rest_id],
            'rest_start' => [$rest_correction->rest_start],
            'rest_end' => [$rest_correction->rest_end],
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('works', [
            'work_start' => '10:00:00',
            'work_end' => '18:00:00',
        ]);
        $this->assertDatabaseHas('rests', [
            'rest_start' => '13:00:00',
            'rest_end' => '14:00:00',
        ]);

    } 
}