<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class AdminAttendanceDetailTest extends TestCase
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

        $this->withoutMiddleware([
            VerifyCsrfToken::class,
        ]);
    }

    public function test_admin_can_view_selected_work_data()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $date = Carbon::now()->yesterday()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin,'admin')->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($date)->format('Y年'));
        $response->assertSee(Carbon::parse($date)->format('n月j日'));
        $response->assertSee(substr($work->work_start, 0, 5));
        $response->assertSee(substr($work->work_end, 0, 5));
        $response->assertSee(substr($rest->rest_start, 0, 5));
        $response->assertSee(substr($rest->rest_end, 0, 5));
    }

    public function test_correct_form_validate_work_start_before()
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
            'rest_start.0' => '12:00',
            'rest_end.0' => '13:00',
            'note' => '打刻間違いのため',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('work_start');

        $errors = session('errors');
        $this->assertEquals('出勤時間もしくは退勤時間が不適切な値です', $errors->first('work_start'));
    }

    public function test_correct_form_validate_rest_start_before()
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

    public function test_correct_form_validate_rest_end_before()
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
            'rest_start' => ['12:00'],
            'rest_end' => ['18:00'],
            'note' => '打刻間違いのため',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors("rest_end.0");

        $errors = session('errors');
        $this->assertEquals('休憩時間が勤務時間外です', $errors->first("rest_end.0"));
    }

    public function test_correct_form_validate_note()
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
}
