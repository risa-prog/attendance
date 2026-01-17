<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use App\Http\Middleware\VerifyCsrfToken;

class WorkEndTest extends TestCase
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

    public function test_confirm_work_end_function()
    {
        $user = User::factory()->create();
        $date = Carbon::now()->toDateString();
        Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '',
            'status' => 1,
        ]);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('<button class="timestamp__condition-leaving">退勤</button>',false);

        $response = $this->followingRedirects()->post('/timestamp/work_end');
        $response->assertSee('退勤済');
    }

    public function test_admin_can_view_work_end_time() {
        $user = User::factory()->create();
    
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/timestamp/work_start');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/timestamp/work_end');

        $admin = Admin::factory()->create();
        $date = Carbon::now()->toDateString();
        $work = Work::where('user_id', $user->id)
        ->where('date', $date)
        ->first();
        $response = $this->actingAs($admin,'admin')->get('/admin/attendance/list');
        $response->assertSeeInOrder([
            $date,
            substr($work->work_end, 0, 5),
        ]);
    }
}
