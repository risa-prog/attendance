<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Models\Admin;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;
use Illuminate\Support\Carbon;
use App\Http\Middleware\VerifyCsrfToken;


class CorrectRequestTest extends TestCase
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

    // 11 勤怠詳細情報修正機能(一般ユーザー)

    // 11-1 出勤時間が退勤時間より後になっている場合のバリデーション
    public function test_correct_request_form_validate_work_start_before()
    {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->yesterday()->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_start' => '17:00',
            'work_end' => '16:00',
            'rest_start' => ['12:00'],
            'rest_end' => ['13:00'],
            'note' => '打刻間違いのため',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('work_start');

        $errors = session('errors');
        $this->assertEquals('出勤時間もしくは退勤時間が不適切な値です', $errors->first('work_start'));
    }

    // 11-2 休憩開始時間が退勤時間より後になっている時のバリデーション
    public function test_correct_request_form_validate_rest_start_before()
    {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->yesterday()->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_start' => ['18:00'],
            'rest_end' => ['13:00'],
            'note' => '打刻間違いのため',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors("rest_start.0");

        $errors = session('errors');
        $this->assertEquals('休憩時間が勤務時間外です', $errors->first("rest_start.0"));
    }

    // 11-3 休憩終了時間が退勤時間より後になっている時
    public function test_correct_request_form_validate_rest_end_before() {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->yesterday()->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_start' => ['12:00'],
            'rest_end' => ['18:00'],
            'note' => '打刻間違いのため',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors("rest_end.0");

        $errors = session('errors');
        $this->assertEquals('休憩時間が勤務時間外です', $errors->first("rest_end.0"));
    }

    // // 11-4 備考欄が未入力の場合
    public function test_correct_request_form_validate_note()
    {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->yesterday()->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_start.0' => '12:00',
            'rest_end.0' => '13:00',
            'note' => null,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('note');

        $errors = session('errors');
        $this->assertEquals('備考を記入してください', $errors->first('note'));
    }

    // 11-5 修正申請処理
    public function test_confirm_correct_request_function() {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->subDays(3)->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_id' => $work->id,
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_id' => $rest->id,
            'rest_start' => ['12:00'],
            'rest_end' => ['13:00'],
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('work_corrections', [
            'work_id' => $work->id,
            'work_start' => '09:00:00',
            'work_end' => '17:00:00',
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);
        $this->assertDatabaseHas('rest_corrections', [
            'work_id' => $work->id,
            // 'rest_id' => $rest->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);
    }

    // 11-6「承認待ち」にログインユーザーが行った申請が全て表示されていること
    public function test_user_can_view_all_waiting_for_approval_attendance() {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->subDays(3)->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_id' => $work->id,
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_id' => $rest->id,
            'rest_start' => ['12:00'],
            'rest_end' => ['13:00'],
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);

        $work_correction = WorkCorrection::where('work_id',$work->id)->first();

        $response = $this->get('/stamp_correction_request/list?tab=waiting_for_approval');
        $date = Carbon::parse($work_correction->work->date)->format('Y/m/d');
        $created_at = Carbon::parse($work_correction->created_at)->format('Y/m/d');
        $response->assertSeeInOrder([
            '承認待ち',
            $date,
            $work_correction->note,
            $created_at,
        ]);
    }

    // 11-7「承認済み」に管理者が承認した修正申請が全て表示されていること
    public function test_user_can_view_all_approved_attendance() {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->subDays(3)->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_id' => $work->id,
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_id' => $rest->id,
            'rest_start' => ['12:00'],
            'rest_end' => ['13:00'],
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);

        $admin = Admin::factory()->create();
        $work_correction = WorkCorrection::where('work_id', $work->id)->first();
        $rest_correction = RestCorrection::where('work_id', $work->id)->first();
        $response = $this->actingAs($admin, 'admin')->post('/stamp_correction_request/approve', [
            'work_id' => $work_correction->work_id,
            'work_start' => $work_correction->work_start,
            'work_end' => $work_correction->work_end,
            'rest_id' => [$rest_correction->rest_id],
            'rest_start' => [$rest_correction->rest_start],
            'rest_end' => [$rest_correction->rest_end],
        ]);
        $response->assertStatus(302);

        $response = $this->actingAs($user)->get('/stamp_correction_request/list?tab=approval');
        $date = Carbon::parse($work_correction->work->date)->format('Y/m/d');
        $created_at = Carbon::parse($work_correction->created_at)->format('Y/m/d');
        $response->assertSeeInOrder([
            '承認済み',
            $date,
            $work_correction->note,
            $created_at,
        ]);
    }

    // 11-8 各申請の「詳細」を押すと申請詳細画面に遷移する
    public function test_shift_correct_request_detail() {
        $user = User::factory()->create();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->subDays(3)->toDateString(),
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => 3,
        ]);
        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response = $this->post('/attendance/correct_request', [
            'work_id' => $work->id,
            'work_start' => '09:00:00',
            'work_end' => '17:00:00',
            'rest_id' => $rest->id,
            'rest_start' => ['12:00:00'],
            'rest_end' => ['13:00:00'],
            'note' => '打刻間違いのため',
            'status' => 1,
        ]);

        $response->assertStatus(302);

        $response = $this->get('/stamp_correction_request/list');
        $response->assertStatus(200);

        $response = $this->get("/attendance/{$work->id}");
        $response->assertStatus(200);
    
    }

}
