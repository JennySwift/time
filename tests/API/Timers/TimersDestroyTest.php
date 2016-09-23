<?php

use App\Models\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersDestroyTest
 */
class TimersDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_timer()
    {
        $timer = Timer::forCurrentUser()->first();

        $this->destroy('/api/timers/'.$timer->id);
    }
}