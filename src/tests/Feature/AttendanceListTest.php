<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class AttendanceListTest extends TestCase
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

    public function test_confirm_user_attendance_information()
    {
        $user = User::factory()->create();

        $work1 = Work::factory()->create([
            'user_id' => $user->id,
            'date' => now()->startOfMonth()->addDay()->toDateString(),
            'work_start' => '10:00:00',
            'work_end' => '19:00:00',
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
        $response->assertSee('10:00');
        $response->assertSee('19:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $response->assertSee(
        Carbon::parse($work2->date)->translatedFormat('m/d(D)'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $date = Carbon::now();
        $date->translatedFormat('Y/m(D)');
        $response->assertSee($date);
    }

    public function test_confirm_user_attendance_information_previous_month() {
        $user = User::factory()->create();
        $this_month = Carbon::now()
            ->toDateString();
        $previous_month = Carbon::now()
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

        $response->assertSee(Carbon::parse($previous_month)->format('Y/m'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    public function test_show_next_month_attendance()
    {
        $user = User::factory()->create();
        $this_month = Carbon::now()
            ->toDateString();
        $next_month = Carbon::now()
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

        $response->assertSee(Carbon::parse($next_month)->format('Y/m'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

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
