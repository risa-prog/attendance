<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;

class RestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 7 休憩機能確認

    // 7-1 休憩ボタンが正しく機能する
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

    // 7-2休憩は一日に何回でもできる
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

    // 7-3 休憩戻ボタンが正しく機能する
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

    // 7-4 休憩戻は一日に何回でもできる
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

    // 7-5 休憩時刻が勤怠一覧画面から確認できる
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
            $rest->rest_start,
            $rest->rest_end,
        ]);
    }
}
