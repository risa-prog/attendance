<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AttendanceCorrectTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_correct_form_validate()
    {
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
            'rest_start' => '12:00',
            'rest_end' => '13:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/{$work->id}");
        $response->assertStatus(200);

        // 出勤時間が退勤時間より後になってる時
        $response1 = $this->post('/attendance/correct_request', [
            '_token' => csrf_token(),
            'work_start' => '17:00',
            'work_end' => '16:00',
            'rest_start.0' => '12:00',
            'rest_end.0' => '13:00',
            'note' => '打刻間違いのため',
        ]);

        $response1->assertStatus(302);
        $response1->assertSessionHasErrors('work_end');

        $errors = session('errors');
        $this->assertEquals('出勤時間もしくは退勤時間が不適切な値です', $errors->first('work_end'));

        // 休憩開始時間が退勤時間より後になっている時
        // $response2 = $this->post('/attendance', [
        //     'work_start' => '09:00',
        //     'work_end' => '17:00',
        //     'rest_start.0' => '18:00',
        //     'rest_end.0' => '13:00',
        //     'note' => '打刻間違いのため',
        // ]);

        // $response2->assertStatus(302);
        // $response2->assertSessionHasErrors("rest_start.0");

        // $errors = session('errors');
        // $this->assertEquals('休憩時間が勤務時間外です', $errors->first("rest_start.0"));



        // // 休憩終了時間が退勤時間より後になっている時
        // $response3 = $this->post('/attendance/correct_request', [
        //     '_token' => csrf_token(),
        //     'work_start' => '09:00',
        //     'work_end' => '17:00',
        //     'rest_start.0' => '12:00',
        //     'rest_end.0' => '18:00',
        //     'note' => '打刻間違いのため',
        // ]);
        // $response->dumpSession();


        // $response3->assertStatus(302);
        // $response3->assertSessionHasErrors('rest_end.0');

        // $errors = session('errors');
        // $this->assertEquals('休憩時間が勤務時間外です', $errors->first('rest_end.0'));

        // 備考欄が未入力の場合
        $response4 = $this->post('/attendance/correct_request', [
            '_token' => csrf_token(),
            'work_start' => '09:00',
            'work_end' => '17:00',
            'rest_start.0' => '12:00',
            'rest_end.0' => '13:00',
            'note' => null,
        ]);

        $response4->assertStatus(302);
        $response4->assertSessionHasErrors('note');

        $errors = session('errors');
        $this->assertEquals('備考を記入してください', $errors->first('note'));
    }

    // public function test_correct_request() {
    //     $user = User::factory()->create();

    //     $work = Work::factory()->create([
    //         'user_id' => $user->id,
    //         'date' => now()->subDays(3)->toDateString(),
    //         'work_start' => '9:00:00',
    //         'work_end' => '17:00:00',
    //         'status' => 3,
    //     ]);
    //     $rest = Rest::factory()->create([
    //         'work_id' => $work->id,
    //         'rest_start' => '12:00',
    //         'rest_end' => '13:00',
    //     ]);

    //     $response = $this->actingAs($user)->get("/attendance/{$work->id}");
    //     $response->assertStatus(200);

    //     $response = $this->post('/attendance/correct_request', [
    //         '_token' => csrf_token(),
    //         'work_start' => '09:00',
    //         'work_end' => '17:00',
    //         'rest_start.0' => '12:00',
    //         'rest_end.0' => '13:00',
    //         'note' => '打刻間違いのため',
    //         'work_id' => $work->id,
    //         'status' => 1,
    //     ]);

    //     $response->assertStatus(302);
    // }
}


