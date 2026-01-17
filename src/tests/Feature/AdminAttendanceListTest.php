<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\Admin;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class AdminAttendanceListTest extends TestCase
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

    public function test_admin_can_view_all_users_attendance_information_correctly()
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

        $rest1 = Rest::factory()->create([
            'work_id' => $work1->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);
        $rest2 = Rest::factory()->create([
            'work_id' => $work2->id,
            'rest_start' => '13:00:00',
            'rest_end' => '14:00:00',
        ]);

        $response = $this->actingAs($admin,'admin')->get('admin/attendance/list');
        $response->assertStatus(200);

        $response->assertSee($user1->name);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $response->assertSee($user2->name);
        $response->assertSee('10:00');
        $response->assertSee('19:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');

        $date = Carbon::now()->format('Y/m/d');
        $response->assertSee($date);
    }

    public function test_admin_can_view_users_previous_day_attendance() {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $date = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->yesterday()->toDateString();

        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'work_start' => '09:00:00',
            'work_end' => '18:00:00',
            'status' => 3,
        ]);

        $rest = Rest::factory()->create([
            'work_id' => $work->id,
            'rest_start' => '12:00:00',
            'rest_end' => '13:00:00',
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/list?tab=previous&date={$date}");
        $response->assertStatus(200);

        $response->assertSee($yesterday);
        $response->assertSee($user->name);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    public function  test_admin_can_view_users_next_day_attendance() {
            $admin = Admin::factory()->create();
            $user = User::factory()->create();
            $date = Carbon::now()->toDateString();
            $tomorrow = Carbon::now()->tomorrow()->toDateString();

            $work = Work::factory()->create([
                'user_id' => $user->id,
                'date' => $tomorrow,
                'work_start' => '09:00:00',
                'work_end' => '18:00:00',
                'status' => 3,
            ]);

            $rest = Rest::factory()->create([
                'work_id' => $work->id,
                'rest_start' => '12:00:00',
                'rest_end' => '13:00:00',
            ]);

            $response = $this->actingAs($admin, 'admin')->get("/admin/attendance/list?tab=next&date={$date}");
            $response->assertStatus(200);

            $response->assertSee($tomorrow);
            $response->assertSee($user->name);
            $response->assertSee('09:00');
            $response->assertSee('18:00');
            $response->assertSee('01:00');
            $response->assertSee('08:00');
        }
}
