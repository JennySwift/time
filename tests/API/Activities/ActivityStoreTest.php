<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ActivityStoreTest
 */
class ActivityStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_activity()
    {
        $content = $this->store('/api/activities', [
            'name' => 'koala',
            'color' => 'red'
        ]);

        $this->checkActivityKeysExist($content);

        $this->assertEquals('koala', $content['name']);
        $this->assertEquals('red', $content['color']);
    }
}