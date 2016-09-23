<?php

namespace App\Http\Transformers;

use App\Models\Sleep\Sleep;
use App\Models\Timer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class TimerTransformer
 */
class TimerTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'activity'
    ];

    /**
     * @var array
     */
    private $params;

    /**
     * TimerTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param Timer $timer
     * @return array
     */
    public function transform(Timer $timer)
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $timer->start);

        $array = [
            'id' => $timer->id,
            'start' => $timer->start,
            'startDate' => $start->format('d/m/y'),
            'finish' => $timer->finish
        ];

        if ($timer->finish) {
            $array['duration'] = [
                'totalMinutes' => $timer->totalMinutes,

                //If total minutes is 90, hours will be 1 and minutes, 30
                'hours' => $timer->hours,
                'minutes' => $timer->minutes,
            ];

            if (isset($this->params['date'])) {
                $totalMinutesForDay = $timer->getTotalMinutesForDay(Carbon::createFromFormat('Y-m-d', $this->params['date']));
                $array['durationForDay'] = [
                    'totalMinutes' => $totalMinutesForDay,

                    //If total minutes is 90, hours will be 1 and minutes, 30
                    'hours' => floor($totalMinutesForDay / 60),
                    'minutes' => $totalMinutesForDay % 60
                ];
            }
        }

        return $array;
    }

    /**
     *
     * @param Timer $timer
     * @return \League\Fractal\Resource\Item
     */
    public function includeActivity(Timer $timer)
    {
        $activity = $timer->activity;

        return $this->item($activity, new ActivityTransformer);
    }

}