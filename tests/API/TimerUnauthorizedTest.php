<?php

use App\Models\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimerUnauthorizedTest
 */
class TimerUnauthorizedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_cannot_show_a_timer_that_belongs_to_another_user()
    {
        $this->assertEquals(1, $this->user->id);
        $timer = Timer::where('user_id', 2)->first();

        $this->showUnauthorized('/api/timers/' . $timer->id);
    }

}