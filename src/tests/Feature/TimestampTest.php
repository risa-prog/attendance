<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Work;
use App\Models\Rest;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class TimestampTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_date_information()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/attendance');

        // Carbon::setTestNow(Carbon::now()); // 任意で now を固定可能

        // 画面上に表示されるであろうフォーマットに合わせる
        $date = Carbon::now()->translatedFormat('Y年n月j日(D)');
        // $time = Carbon::now()->format('H:i');
        // dd($time);

        //  $response->assertSee([
        //     $date,
        //     $time
        // ]);
        $response->assertSee($date);
    }

    public function test_timestamp_status() {
        // 勤務外の場合のステータス確認
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('勤務外');

        // 出勤中の場合のステータス確認
        $user = User::find(2);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('出勤中');

        // 休憩中の場合のステータス確認
        $user = User::find(3);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('休憩中');

        // 退勤済みの場合のステータス確認
        $user = User::find(4);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('退勤済');
    }

    // 出勤機能
    public function test_attendance_at_work() {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('<button class="timestamp__condition-working">出勤</button>', false);

        $response = $this->followingRedirects()->post('/timestamp/work_start');
        // $time = Carbon::now()->format('H:i');
        
        $response->assertSee('出勤中');


        // 出勤は一日一回まで
        $user = User::find(4);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertDontSee('<button class="timestamp__condition-working">出勤</button>', false);


        // 出勤時刻が管理画面で確認できる(出勤処理は上でしてるから省略)
        $date = Carbon::now()->format('Y-m-d');
        $work = Work::where('user_id', 1)->where('date',$date)->first();
        $work_start = substr($work->work_start, 0, 5);
        $admin = Admin::find(1);
        $response = $this->actingAs($admin)->get('admin/attendance/list');
        $response->assertSeeInOrder([
            $date,
            $work_start,
        ]);
    }
    

    // 休憩機能
    public function test_break() {
        // 休憩ボタンが正しく機能する
        $user = User::find(2);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('<button class="timestamp__condition-rest-start">休憩入</button>', false);

        $response = $this->followingRedirects()->post('/timestamp/rest_start');
        $response->assertSee('休憩中');

        // 休憩は一日に何回でもできる
        // (上の続きで休憩戻るから始める)
        $response = $this->followingRedirects()->post('/timestamp/rest_end');
        $response->assertSee('<button class="timestamp__condition-rest-start">休憩入</button>', false);

        // 休憩戻ボタンが正しく機能する
        // (再び上の続きから)
        $response = $this->followingRedirects()->post('/timestamp/rest_start');
        $response->assertSee('<button class="timestamp__condition-rest-end">', false);
        $response = $this->followingRedirects()->post('/timestamp/rest_end');
        $response->assertSee('出勤中');

        // 休憩戻は一日に何回でもできる

        //     休憩時間が勤怠一覧画面で確認できる(休憩処理省略)
        $date = Carbon::now()->format('Y-m-d');
        $work = Work::where('user_id',2)->where('date',$date)->first();
        $rests = Rest::where('work_id', $work->id)->get();
        foreach($rests as $rest) {
            $rest_start = substr($rest->rest_start, 0, 5);
            $rest_end = substr($rest->rest_end, 0, 5);
            $admin = Admin::find(1);
            $admin = Admin::find(1);
            $response = $this->actingAs($admin)->get('admin/attendance/list');
            $response->assertSeeInOrder([
                $date,
                $rest_start,
                $rest_end,
            ]);
        }
        
        
    }

    // 退勤機能
    public function test_leaving_work() {
        // 退勤ボタンが正しく機能する
        $user = User::find(2);
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('<button class="timestamp__condition-leaving">退勤</button>', false);
        $response = $this->followingRedirects()->post('/timestamp/work_end');
        $response->assertSee('退勤済');



        // 退勤時間が管理画面で確認できる
        $date = Carbon::now()->format('Y-m-d');
        $work = Work::where('user_id', 2)->where('date', $date)->first();
        $work_end = substr($work->work_end, 0, 5);
        $admin = Admin::find(1);
        $response = $this->actingAs($admin)->get('admin/attendance/list');
        $response->assertSeeInOrder([
            $date,
            $work_end,
        ]);
    } 
}
