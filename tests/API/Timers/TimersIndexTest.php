<?php

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersIndexTest
 */
class TimersIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_sleep_entries()
    {
        $this->markTestIncomplete();
        $content = $this->show('/api/timers?byDate=true');

        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('shortDate', $content[0]);
        $this->assertArrayHasKey('orderIndex', $content[0]);
        $this->assertArrayHasKey('day', $content[0]);

        $this->assertArrayHasKey('start', $content[0][4]);
        $this->assertArrayHasKey('finish', $content[0][4]);
        $this->assertArrayHasKey('startPosition', $content[0][4]);
        $this->assertArrayHasKey('finishPosition', $content[0][4]);
        $this->assertArrayHasKey('startHeight', $content[0][4]);
        $this->assertArrayHasKey('color', $content[0][4]);

//        $this->assertArrayHasKey('id', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('name', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('color', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('totalDuration', $content[0]['activity']['data']);

//        $this->assertEquals('9:00pm', $content[0]['start']);
//        $this->assertEquals('8:00am', $content[0]['finish']);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_timers_for_a_day()
    {
        $content = $this->getTimers(Carbon::yesterday());
//        dd($content);

        $this->assertEquals(60, $content[1]['durationForDay']['totalMinutes']);
        $this->assertEquals(1, $content[1]['durationForDay']['hours']);
        $this->assertEquals(0, $content[1]['durationForDay']['minutes']);
        $this->assertEquals(60, $content[1]['duration']['totalMinutes']);
        $this->assertEquals(1, $content[1]['duration']['hours']);
        $this->assertEquals(0, $content[1]['duration']['minutes']);

        $this->assertEquals(480, $content[0]['duration']['totalMinutes']);
        $this->assertEquals(8, $content[0]['duration']['hours']);
        $this->assertEquals(0, $content[0]['duration']['minutes']);
        $this->assertEquals(60, $content[0]['durationForDay']['totalMinutes']);
        $this->assertEquals(1, $content[0]['durationForDay']['hours']);
        $this->assertEquals(0, $content[0]['durationForDay']['minutes']);

        $this->assertEquals(480, $content[5]['duration']['totalMinutes']);
        $this->assertEquals(8, $content[5]['duration']['hours']);
        $this->assertEquals(0, $content[5]['duration']['minutes']);
        $this->assertEquals(420, $content[5]['durationForDay']['totalMinutes']);
        $this->assertEquals(7, $content[5]['durationForDay']['hours']);
        $this->assertEquals(0, $content[5]['durationForDay']['minutes']);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_timers_for_a_day_without_the_timer_in_progress()
    {
        $this->startTimer();
        $content = $this->getTimers(Carbon::today());
//dd($content);
        foreach ($content as $timer) {
            $this->assertNotNull($timer['finish']);
        }
    }

    /*
     *
     */
    private function startTimer()
    {
        $start = '2015-12-01 21:00:00';

        $content = $this->store('/api/timers', [
            'start' => $start,
            'activity_id' => Activity::where('name', 'work')->first()->id
        ]);

        $this->seeInDatabase('timers', ['start' => $start]);
    }

    /**
     * @param $date
     * @return mixed
     */
    private function getTimers($date)
    {
        $content = $this->show('/api/timers?date=' . $date->copy()->format('Y-m-d'));

        $this->checkTimerKeysExist($content[0]);

        //Todo: check the values are correct
        $this->assertContains($date->copy()->format('Y-m-d'), $content[0]['start']);

        return $content;
    }

}