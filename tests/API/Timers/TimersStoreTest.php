<?php

use App\Models\Activity;
use App\Models\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class TimersStoreTest
 */
class TimersStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_sleep_entry()
    {
        $start = '2015-12-01 21:00:00';
        $finish = '2015-12-02 08:30:00';

        $content = $this->store('/api/timers', [
            'start' => $start,
            'finish' => $finish,
            'activity_id' => Activity::where('name', 'sleep')->first()->id
        ]);

        $this->checkTimerKeysExist($content);

        $this->assertEquals($start, $content['start']);
        $this->assertEquals($finish, $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);
        $this->assertEquals(690, $content['duration']['totalMinutes']);
        $this->assertEquals(11, $content['duration']['hours']);
        $this->assertEquals(30, $content['duration']['minutes']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_start_a_timer_then_stop_it()
    {
        $content = $this->store('/api/timers', [
            'start' => '2015-12-01 21:00:00',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ]);

        $this->checkTimerKeysExist($content, false, false);

        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals('01/12/15', $content['startDate']);

        $this->stopTimer(Timer::find($content['id']));
    }

    /**
     *
     * @param $timer
     */
    private function stopTimer($timer)
    {
        $content = $this->update('/api/timers/'.$timer->id, [
            'finish' => '2015-12-01 21:00:01'
        ]);

        $this->checkTimerKeysExist($content);

        $this->assertEquals('2015-12-01 21:00:01', $content['finish']);
        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals(2, $content['activity']['data']['id']);
    }

    /**
     * For when the user gives both start and finish times
     * @test
     * @return void
     */
    public function it_can_insert_a_manual_timer_entry()
    {
        $content = $this->store('/api/timers', [
            'start' => '2015-12-01 21:00:00',
            'finish' => '2015-12-01 22:10:05',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ]);

        $this->checkTimerKeysExist($content);

        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals('2015-12-01 22:10:05', $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);
    }
}