<?php

namespace Testing\Traits;

use Illuminate\Http\Response;

trait CheckKeys
{
    /**
     *
     * @param $timer
     * @param null $onlyBasicKeys
     */
    protected function checkTimerKeysExist($timer, $onlyBasicKeys = null)
    {
        $this->assertArrayHasKey('id', $timer);
        $this->assertArrayHasKey('start', $timer);
        $this->assertArrayHasKey('finish', $timer);
        $this->assertArrayHasKey('startDate', $timer);
        $this->assertArrayHasKey('activity', $timer);

        $this->assertArrayHasKey('duration', $timer);
        $this->assertArrayHasKey('totalMinutes', $timer['duration']);
        $this->assertArrayHasKey('hours', $timer['duration']);
        $this->assertArrayHasKey('minutes', $timer['duration']);

        if (!isset($onlyBasicKeys)) {
            $this->assertArrayHasKey('durationForDay', $timer);
            $this->assertArrayHasKey('totalMinutes', $timer['durationForDay']);
            $this->assertArrayHasKey('hours', $timer['durationForDay']);
            $this->assertArrayHasKey('minutes', $timer['durationForDay']);
        }

    }

    /**
     *
     * @param $activity
     */
    protected function checkActivityKeysExist($activity, $weekValues = null, $dayValues = null)
    {
        $this->assertArrayHasKey('id', $activity);
        $this->assertArrayHasKey('name', $activity);

        $this->assertArrayHasKey('duration', $activity);
        $this->assertArrayHasKey('totalMinutes', $activity['duration']);
        $this->assertArrayHasKey('hours', $activity['duration']);
        $this->assertArrayHasKey('minutes', $activity['duration']);

        if ($weekValues) {
            $this->assertArrayHasKey('totalMinutes', $activity['week']);
            $this->assertArrayHasKey('hours', $activity['week']);
            $this->assertArrayHasKey('minutes', $activity['week']);

            $this->assertArrayHasKey('totalMinutes', $activity['week']['dailyAverage']);
            $this->assertArrayHasKey('hours', $activity['week']['dailyAverage']);
            $this->assertArrayHasKey('minutes', $activity['week']['dailyAverage']);
        }

        if ($dayValues) {
            $this->assertArrayHasKey('totalMinutes', $activity['day']);
            $this->assertArrayHasKey('hours', $activity['day']);
            $this->assertArrayHasKey('minutes', $activity['day']);
        }
    }
}