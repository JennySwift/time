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
        DB::beginTransaction();
        $this->logInUser();

        $start = '2015-12-01 21:00:00';
        $finish = '2015-12-02 08:30:00';

        $sleep = [
            'start' => $start,
            'finish' => $finish,
            'activity_id' => Activity::where('name', 'sleep')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $sleep);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
//        $this->assertArrayHasKey('finish', $content);
        $this->assertArrayHasKey('startDate', $content);

        $this->assertEquals($start, $content['start']);
        $this->assertEquals($finish, $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);
        $this->assertEquals(690, $content['durationInMinutes']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_start_a_timer_then_stop_it()
    {
        $this->logInUser();

        $timer = [
            'start' => '2015-12-01 21:00:00',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $timer);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
        $this->assertArrayHasKey('startDate', $content);

        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals('01/12/15', $content['startDate']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $this->stopTimer(Timer::find($content['id']));
    }

    /**
     *
     * @param $timer
     */
    private function stopTimer($timer)
    {
        $response = $this->call('PUT', '/api/timers/'.$timer->id, [
            'finish' => '2015-12-01 21:00:01'
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkTimerKeysExist($content);

        $this->assertEquals('2015-12-01 21:00:01', $content['finish']);
        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals(2, $content['activity']['data']['id']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * For when the user gives both start and finish times
     * @test
     * @return void
     */
    public function it_can_insert_a_manual_timer_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $timer = [
            'start' => '2015-12-01 21:00:00',
            'finish' => '2015-12-01 22:10:05',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $timer);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTimerKeysExist($content);

        $this->assertEquals('2015-12-01 21:00:00', $content['start']);
        $this->assertEquals('2015-12-01 22:10:05', $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }
}