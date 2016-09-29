<?php

use App\Models\Activity;
use App\Models\Timer;
use App\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TimerSeeder extends Seeder
{

    private $user;
    private $faker;

    public function run()
    {
        Timer::truncate();

        $this->faker = Faker::create();

        $users = User::all();

        foreach ($users as $user) {
            $this->user = $user;

            $this->createSleepEntries();
            $this->createWalkingEntries();
            $this->createWorkEntries();

            $this->createTimer('reading', Carbon::yesterday()->hour(8)->minute(30), Carbon::yesterday()->hour(8)->minute(40));
            $this->createTimer('reading', Carbon::today()->subDays(7)->hour(8)->minute(30), Carbon::today()->subDays(7)->hour(10)->minute(30));

            $this->createTimer('tennis', Carbon::today()->subDays(7)->hour(10)->minute(30), Carbon::today()->subDays(7)->hour(12)->minute(30));
        }

    }

    /**
     *
     */
    private function createSleepEntries()
    {
        foreach (range(1,14) as $index) {
            $date = Carbon::today()->subDays($index);

            $this->createTimer('sleep', $date->copy()->hour(16), $date->copy()->hour(17));
            $this->createTimer('sleep', $date->copy()->hour(23), $date->copy()->addDays(1)->hour(7));
        }
    }

    /**
     * Create an entry for walking each day,
     * so I can test the daily average regardless of what day I run my tests
     */
    private function createWalkingEntries()
    {
        foreach (range(0,6) as $index) {
            $date = Carbon::today()->subDays($index);
            $this->createTimer('walking', $date->copy()->hour(10)->minute(45), $date->copy()->hour(10)->minute(55));
        }
    }

    /**
     * Create entries for the last 5 days.
     */
    private function createWorkEntries()
    {
        foreach (range(0, 4) as $index) {
            $date = Carbon::today()->subDays($index);

            $this->createTimer('work', $date->copy()->hour(13), $date->copy()->hour(14));
        }
    }


    /**
     * Create a night's sleep
     * @param $activityName
     * @param Carbon $start
     * @param Carbon $finish
     */
    private function createTimer($activityName, Carbon $start, Carbon $finish)
    {
        $entry = new Timer([
            'start' => $start->format('Y-m-d H:i:s'),
            'finish' => $finish->format('Y-m-d H:i:s')
        ]);

        $entry->user()->associate($this->user);
        $entry->activity()->associate(Activity::where('name', $activityName)->where('user_id', $this->user->id)->first());
        $entry->save();
    }

}