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
                $this->createDaySleep($index, $index * 15);
                $this->createNightSleep($index, $index * 15);
                $this->createWorkEntry($index);
            }
        }

    }

    /**
     *
     * @param $index
     */
    private function createWorkEntry($index)
    {
        $today = Carbon::today();
        $this->date = $today->subDays($index);

        $entry = new Timer([
            'start' => $this->date->hour(13)->format('Y-m-d H:i:s'),
            'finish' => $this->date->hour(14)->format('Y-m-d H:i:s')
        ]);

        $entry->user()->associate($this->user);
        $entry->activity()->associate(Activity::where('name', 'work')->where('user_id', $this->user->id)->first());
        $entry->save();
    }

    /**
     * Create a night's sleep
     */
    private function createNightSleep($index, $finishMinutes)
    {
        $today = Carbon::today();
        $this->date = $today->subDays($index);

        $entry = new Timer([
            'start' => $this->date->hour(21)->format('Y-m-d H:i:s'),
            'finish' => $this->date->addDays(1)->hour(8)->minute($finishMinutes)->format('Y-m-d H:i:s')
        ]);

        $entry->user()->associate($this->user);
        $entry->activity()->associate(Activity::where('name', 'sleep')->where('user_id', $this->user->id)->first());
        $entry->save();
    }

    /**
     * Create a day's sleep
     */
    private function createDaySleep($index, $finishMinutes)
    {
        $today = Carbon::today();
        $this->date = $today->subDays($index);

        $entry = new Timer([
            'start' => $this->date->hour(16)->format('Y-m-d H:i:s'),
            'finish' => $this->date->hour(17)->minute($finishMinutes)->format('Y-m-d H:i:s')
        ]);

        $entry->user()->associate($this->user);
        $entry->activity()->associate(Activity::where('name', 'sleep')->where('user_id', $this->user->id)->first());
        $entry->save();
    }

}