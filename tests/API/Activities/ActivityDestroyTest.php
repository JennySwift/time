<?php

use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ActivityDestroyTest
 */
class ActivityDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_activity()
    {
        $activity = Activity::first();

        $this->destroy('/api/activities/' . $activity->id);
    }
}