<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;

class TimestampTest extends TestCase
{
    use DatabaseMigrations;

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
    public function test_timestamp_matches_current_time()
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


        // 出勤中の場合のステータス確認


        // 休憩中の場合のステータス確認


        // 退勤済みの場合のステータス確認
    }

    // 出勤機能
    public function test_attendance_at_work() {

    }

    // 休憩機能
    public function test_break() {

    }

    // 退勤機能
    public function test_leaving_work() {

    } 
}
