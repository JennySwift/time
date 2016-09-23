<?php

use App\Models\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimerShowTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_a_timer()
    {
        $timer = Timer::forCurrentUser()->first();

        $content = $this->show('/api/timers/' . $timer->id);

        $this->checkTimerKeysExist($content, true);

        $this->assertEquals(1, $content['id']);
    }

}
