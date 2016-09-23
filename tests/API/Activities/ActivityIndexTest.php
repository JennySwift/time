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
     * Todo: the week stuff depends on the day being Monday
     * @test
     * @return void
     */
    public function it_can_get_the_activities_with_their_durations_for_the_week()
    {
        $response = $this->call('GET', '/api/activities?forWeek=true&date=' . Carbon::yesterday()->format('Y-m-d'));
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkActivityKeysExist($content[0], true);

        $this->checkContent($content);

        $this->assertEquals(1980, $content[0]['week']['totalMinutes']);
        $this->assertEquals(1980, $content[0]['week']['hours']);
        $this->assertEquals(1980, $content[0]['week']['minutes']);

        $this->assertEquals(990, $content[0]['week']['averageMinutesPerDay']);

        $this->assertEquals(120, $content[1]['week']['totalMinutes']);
        $this->assertEquals(120, $content[1]['week']['hours']);
        $this->assertEquals(120, $content[1]['week']['minutes']);

        $this->assertEquals(60, $content[1]['week']['averageMinutesPerDay']);

        $this->assertEquals('untracked', $content[2]['name']);
        $this->assertEquals(7980, $content[2]['totalMinutesForWeek']);

        $this->assertEquals(200, $response->getStatusCode());
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