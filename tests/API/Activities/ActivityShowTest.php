<?php

use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ActivityShowTest
 */
class ActivityShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_a_activity()
    {
        $activity = Activity::forCurrentUser()->first();

        $content = $this->show('/api/activities/' . $activity->id);
//        dd($content);

        $this->checkActivityKeysExist($content);
    }


}