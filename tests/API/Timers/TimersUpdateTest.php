<?php

use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersUpdateTest
 */
class TimersUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_timer()
    {
        $timer = Timer::forCurrentUser()->first();
        $finish = Carbon::today()->hour(23)->format('Y-m-d H:i:s');
        $this->assertEquals(1, $timer->activity_id);

        $content = $this->update('/api/timers/'.$timer->id, [
            'finish' => $finish,
            'start' => '2016-03-01 10:30:00',
            'activity_id' => 2
        ]);

        $this->checkTimerKeysExist($content);

        //Todo: test values are correct
        $this->assertEquals($finish, $content['finish']);
        $this->assertEquals('2016-03-01 10:30:00', $content['start']);
        $this->assertEquals(2, $content['activity']['data']['id']);
    }
}