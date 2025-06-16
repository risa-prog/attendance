<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Carbon;

class WorkFactory extends Factory
{
    protected $model = Work::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // ランダムな開始時刻（例: 9:00〜17:00 の間）
        $start = Carbon::createFromTime(rand(7, 16), [0, 15, 30, 45][rand(0, 3)]);

        // 開始時刻から最低1時間〜最大10時間後の終了時刻
        $end = (clone $start)->addMinutes(rand(60, 600)); // 1〜10時間


        return [
            // 'user_id' => User::factory(),
            // 'date' => Carbon::now()->subDays(rand(0, 28))->toDateString(),
            // 'start_time' => $this->faker->time('H:i:s'),
            // 'work_time' => $this->faker->time('H:i:s'),
            // 'status' => $this ->faker->numberBetween('1,3'),
        ];
    }
}
