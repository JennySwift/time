<?php

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ActivityIndexTest
 */
class ActivityIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_activities()
    {
        $content = $this->show('/api/activities');

        $this->checkActivityKeysExist($content[0]);

        $this->checkContent($content);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_get_the_activities_with_their_durations_for_the_previous_week()
    {
        $content = $this->show('/api/activities?forWeek=true&date=' . Carbon::today()->subDays(7)->format('Y-m-d'));
//dd($content);
        $this->checkActivityKeysExist($content[0], true);

        $this->checkContent($content);

        //First activity
        $this->assertEquals(0, $content[0]['week']['totalMinutes']);
        $this->assertEquals(0, $content[0]['week']['hours']);
        $this->assertEquals(0, $content[0]['week']['minutes']);

        $this->assertEquals(0, $content[0]['week']['dailyAverage']['totalMinutes']);
        $this->assertEquals(0, $content[0]['week']['dailyAverage']['hours']);
        $this->assertEquals(0, $content[0]['week']['dailyAverage']['minutes']);

        //Second activity
        $this->assertEquals(0, $content[1]['week']['totalMinutes']);
        $this->assertEquals(0, $content[1]['week']['hours']);
        $this->assertEquals(0, $content[1]['week']['minutes']);

        $this->assertEquals(0, $content[1]['week']['dailyAverage']['totalMinutes']);
        $this->assertEquals(0, $content[1]['week']['dailyAverage']['hours']);
        $this->assertEquals(0, $content[1]['week']['dailyAverage']['minutes']);

        //Third activity
        $this->assertEquals(120, $content[2]['week']['totalMinutes']);
        $this->assertEquals(2, $content[2]['week']['hours']);
        $this->assertEquals(0, $content[2]['week']['minutes']);
        $this->assertEquals(130, $content[2]['duration']['totalMinutes']);

        $this->assertEquals(17, $content[2]['week']['dailyAverage']['totalMinutes']);
        $this->assertEquals(0, $content[2]['week']['dailyAverage']['hours']);
        $this->assertEquals(17, $content[2]['week']['dailyAverage']['minutes']);

//        $this->assertEquals('untracked', $content[2]['name']);
//        $this->assertEquals(7980, $content[2]['totalMinutesForWeek']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_get_the_activities_with_their_durations_for_the_current_week()
    {
        $content = $this->show('/api/activities?forWeek=true&date=' . Carbon::today()->format('Y-m-d'));
//dd($content);
        $this->checkActivityKeysExist($content[0], true);

        $this->checkContent($content);

        //First activity
//        $this->assertEquals(0, $content[0]['week']['totalMinutes']);
//        $this->assertEquals(0, $content[0]['week']['hours']);
//        $this->assertEquals(0, $content[0]['week']['minutes']);
//
//        $this->assertEquals(0, $content[0]['week']['dailyAverage']['totalMinutes']);
//        $this->assertEquals(0, $content[0]['week']['dailyAverage']['hours']);
//        $this->assertEquals(0, $content[0]['week']['dailyAverage']['minutes']);

        //Second activity
//        $this->assertEquals(0, $content[1]['week']['totalMinutes']);
//        $this->assertEquals(0, $content[1]['week']['hours']);
//        $this->assertEquals(0, $content[1]['week']['minutes']);
//
//        $this->assertEquals(0, $content[1]['week']['dailyAverage']['totalMinutes']);
//        $this->assertEquals(0, $content[1]['week']['dailyAverage']['hours']);
//        $this->assertEquals(0, $content[1]['week']['dailyAverage']['minutes']);

        //Third activity
//        $this->assertEquals(10, $content[2]['week']['totalMinutes']);
//        $this->assertEquals(0, $content[2]['week']['hours']);
//        $this->assertEquals(10, $content[2]['week']['minutes']);
//        $this->assertEquals(130, $content[2]['duration']['totalMinutes']);
//
//        $this->assertEquals(2, $content[2]['week']['dailyAverage']['totalMinutes']);
//        $this->assertEquals(0, $content[2]['week']['dailyAverage']['hours']);
//        $this->assertEquals(2, $content[2]['week']['dailyAverage']['minutes']);

        //Fourth activity
        //Can't test because that would depend on the day I run my tests?
//        $this->assertEquals(10, $content[3]['week']['totalMinutes']);
//        $this->assertEquals(0, $content[3]['week']['hours']);
//        $this->assertEquals(10, $content[3]['week']['minutes']);
//        $this->assertEquals(130, $content[3]['duration']['totalMinutes']);

        $this->assertEquals(10, $content[3]['week']['dailyAverage']['totalMinutes']);
        $this->assertEquals(0, $content[3]['week']['dailyAverage']['hours']);
        $this->assertEquals(10, $content[3]['week']['dailyAverage']['minutes']);

//        $this->assertEquals('untracked', $content[2]['name']);
//        $this->assertEquals(7980, $content[2]['totalMinutesForWeek']);
    }



    /**
     * @test
     * @return void
     */
    public function it_checks_the_timers_that_belong_to_the_activity_are_for_the_right_user()
    {
        $activities = Activity::forCurrentUser()->get();

        foreach ($activities as $activity) {
            foreach ($activity->timers as $timer) {
                $this->assertEquals(1, $timer->user->id);
            }
        }
    }

    /**
     * @test
     * @return void
     */
    public function it_can_get_the_total_minutes_for_the_day_for_the_activities()
    {
        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', '/api/activities?forDay=true&date=' . $date);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkActivityKeysExist($content[0], false, true);

        $this->assertCount(3, $content);

        $this->assertEquals('sleep', $content[0]['name']);
        $this->assertEquals(735, $content[0]['day']['totalMinutes']);
        $this->assertEquals(12, $content[0]['day']['hours']);
        $this->assertEquals(15, $content[0]['day']['minutes']);

        $this->assertEquals('work', $content[1]['name']);
        $this->assertEquals(60, $content[1]['day']['totalMinutes']);
        $this->assertEquals(1, $content[1]['day']['hours']);
        $this->assertEquals(0, $content[1]['day']['minutes']);

//        $this->assertContains('untracked', $content[2]['name']);
//        $this->assertEquals(645, $content[2]['day']['totalMinutes']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     *
     * @param $content
     */
    private function checkContent($content)
    {

        $this->assertEquals('sleep', $content[0]['name']);

        $this->assertEquals(3900, $content[0]['duration']['totalMinutes']);
        $this->assertEquals(65, $content[0]['duration']['hours']);
        $this->assertEquals(0, $content[0]['duration']['minutes']);

        $this->assertEquals(300, $content[1]['duration']['totalMinutes']);
        $this->assertEquals(5, $content[1]['duration']['hours']);
        $this->assertEquals(0, $content[1]['duration']['minutes']);

    }

}