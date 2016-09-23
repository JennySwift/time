<?php

use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimerInProgressTest
 */
class TimerInProgressTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Todo: check values are correct? Check it doesn't error if there is no timer in progress?
     * @test
     * @return void
     */
    public function it_checks_for_timer_in_progress()
    {
        $content = $this->store('/api/timers', [
            'start' => '2015-12-01 21:00:00',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ]);

        $content = $this->show('/api/timers/checkForTimerInProgress');

        $this->checkTimerKeysExist($content, false, false);
    }


}