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
    private $date;

    public function run()
    {
        Timer::truncate();

        $this->faker = Faker::create();

        $users = User::all();

        foreach ($users as $user) {
            $this->user = $user;

            /**
             * Create entries for the last 5 days.
             */
            foreach (range(0, 4) as $index) {
                $this->date = Carbon::today()->subDays($index);

                $this->createTimer('sleep', $this->date->copy()->hour(16), $this->date->copy()->hour(17)->minute($index * 15));
                $this->createTimer('sleep', $this->date->copy()->hour(21), $this->date->copy()->addDays(1)->hour(8)->minute($index * 15));

                $this->createTimer('work', $this->date->copy()->hour(13), $this->date->copy()->hour(14));
            }

            $this->createTimer('reading', Carbon::yesterday()->hour(8)->minute(30), Carbon::yesterday()->hour(8)->minute(40));
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