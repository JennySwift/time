<?php

use App\Models\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

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

    /**
     * @test
     */
    public function it_cannot_show_timers_if_user_is_not_logged_in()
    {
        Auth::logout();

        $this->call('GET', '/api/timers/');

        $this->assertResponseStatus(Response::HTTP_FOUND);
        $this->assertRedirectedTo('/login');

    }

}