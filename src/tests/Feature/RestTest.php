<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Http\Middleware\VerifyCsrfToken;

class RestTest extends TestCase
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

    public function test_confirm_rest_start_function()
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

        $response->assertSee('<button class="timestamp__condition-rest-start">休憩入</button>', false);

        $response = $this->followingRedirects()->post('/timestamp/rest_start');
        $response->assertSee('休憩中');
    }

    public function test_confirm_rest_start_function_again_and_again() {
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

        $response1 = $this->followingRedirects()->post('/timestamp/rest_start');
        $response1->assertStatus(200);

        $response2 = $this->followingRedirects()->post('/timestamp/rest_end');
        $response2->assertSee('<button class="timestamp__condition-rest-start">休憩入</button>', false);
    }

    public function test_confirm_rest_end_function() {
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

        $response1 = $this->followingRedirects()->post('/timestamp/rest_start');
        $response1->assertSee('<button class="timestamp__condition-rest-end">休憩戻</button>', false);

        $response2 = $this->followingRedirects()->post('/timestamp/rest_end');
        $response2->assertSee('出勤中');
    }

    public function test_confirm_rest_end_function_again_and_again() {
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

        $response1 = $this->followingRedirects()->post('/timestamp/rest_start');
        $response1->assertStatus(200);

        $response2 = $this->followingRedirects()->post('/timestamp/rest_end');
        $response2->assertStatus(200);

        $response3 = $this->followingRedirects()->post('/timestamp/rest_start');
        
        $response3->assertSee('<button class="timestamp__condition-rest-end">休憩戻</button>',false);
    }

    public function test_user_can_view_rest_time_correctly() {
        $user = User::factory()->create();
        $date = Carbon::now()->toDateString();
        $work = Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => '09:00:00',
            'work_end' => '',
            'status' => 1,
        ]);
        $response = $this->actingAs($user)->get('/attendance');

        $response1 = $this->followingRedirects()->post('/timestamp/rest_start');
        $response1->assertStatus(200);

        $response2 = $this->followingRedirects()->post('/timestamp/rest_end');
        $response2->assertStatus(200);

        $rest = Rest::where('work_id',$work->id)->first();

        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertSeeInOrder([
            $date,
            substr($rest->rest_start, 0, 5),
            substr($rest->rest_end, 0, 5),
        ]);
    }
}
