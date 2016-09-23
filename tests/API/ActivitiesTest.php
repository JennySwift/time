<?php

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ActivitiesTest
 */
class ActivitiesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_activities()
    {
        $response = $this->call('GET', '/api/activities');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkActivityKeysExist($content[0]);

        $this->checkContent($content);

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

    /**
     * Todo: the week stuff depends on the day being Monday
     * @test
     * @return void
     */
    public function it_can_get_the_activities_with_their_durations_for_the_week()
    {
        $response = $this->call('GET', '/api/activities?date=' . Carbon::yesterday()->format('Y-m-d'));
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkActivityKeysExist($content[0], true);

        $this->checkContent($content);

        $this->assertEquals(1980, $content[0]['week']['totalMinutes']);
        $this->assertEquals(990, $content[0]['week']['averageMinutesPerDay']);

        $this->assertEquals(120, $content[1]['week']['totalMinutes']);
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
    public function it_gets_the_total_minutes_for_the_day_for_the_activity()
    {
        $this->logInUser();
        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', '/api/activities/getTotalMinutesForDay?date=' . $date);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('totalMinutesForDay', $content[0]);

        $this->assertEquals('sleep', $content[0]['name']);
        $this->assertEquals(735, $content[0]['totalMinutesForDay']);

        $this->assertEquals('work', $content[1]['name']);
        $this->assertEquals(60, $content[1]['totalMinutesForDay']);

        $this->assertContains('untracked', $content[2]['name']);
        $this->assertEquals(645, $content[2]['totalMinutesForDay']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_activity()
    {
        $activity = [
            'name' => 'koala',
            'color' => 'red'
        ];

        $response = $this->call('POST', '/api/activities', $activity);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('color', $content);

        $this->assertEquals('koala', $content['name']);
        $this->assertEquals('red', $content['color']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_activity()
    {
        $activity = Activity::forCurrentUser()->first();
        $response = $this->call('PUT', '/api/activities/' . $activity->id, [
            'name' => 'numbat',
            'color' => 'blue'
        ]);

        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkActivityKeysExist($content);

        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('blue', $content['color']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_activity()
    {
        $activity = Activity::first();

        $response = $this->call('DELETE', '/api/activities/' . $activity->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/activity/' . $activity->id);
        $this->assertEquals(404, $response->getStatusCode());
    }

}