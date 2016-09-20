<?php

use App\Models\Activity;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{

    private $user;
    private $faker;
    private $date;

    public function run()
    {
        Activity::truncate();

        $this->faker = Faker::create();

        $users = User::all();

        $activities = [
            [
                'name' => 'sleep',
                'color' => '#5cb85c'
            ],
            [
                'name' => 'work',
                'color' => '#5bc0de'
            ]
        ];

        foreach ($users as $user) {
            $this->user = $user;

            foreach ($activities as $activity) {
                $temp = new Activity([
                    'name' => $activity['name'],
                    'color' => $activity['color']
                ]);
                $temp->user()->associate($user);
                $temp->save();
            }
        }

    }

}