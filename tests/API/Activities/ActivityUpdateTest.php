<?php

use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ActivityUpdateTest
 */
class ActivityUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_activity()
    {
        $activity = Activity::forCurrentUser()->first();

        $content = $this->update('/api/activities/' . $activity->id, [
            'name' => 'numbat',
            'color' => 'blue'
        ]);

        $this->checkActivityKeysExist($content);

        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('blue', $content['color']);
    }
}