<?php

namespace App\Http\Transformers;

use App\Models\Activity;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class ActivityTransformer
 */
class ActivityTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    private $params;

    /**
     * ActivityTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param Activity $activity
     * @return array
     */
    public function transform(Activity $activity)
    {
        $array = [
            'id' => $activity->id,
            'name' => $activity->name,
            'color' => $activity->color,

            'duration' => [
                'totalMinutes' => $activity->totalMinutes,
                'hours' => floor($activity->totalMinutes / 60),
                'minutes' => $activity->totalMinutes % 60
            ]
        ];

        if (isset($this->params['date'])) {
            $date = $this->params['date'];

            if (isset($this->params['forWeek'])) {
                $totalMinutesForWeek = $activity->getTotalMinutesForWeek($date);

                $dailyAverageMinutes = $activity->calculateAverageMinutesPerDayForWeek($date, $totalMinutesForWeek);

                $array['week'] = [
                    'totalMinutes' => $totalMinutesForWeek,
                    'hours' => floor($totalMinutesForWeek / 60),
                    'minutes' => $totalMinutesForWeek % 60,

                    'dailyAverage' => [
                        'totalMinutes' => $dailyAverageMinutes,
                        'hours' => floor($dailyAverageMinutes / 60),
                        'minutes' => $dailyAverageMinutes % 60,
                    ]

                ];
            }

            if (isset($this->params['forDay'])) {
                $startOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0);
                $endOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(24)->minute(0)->second(0);
                $totalMinutesForDay = $activity->calculateTotalMinutesForDay($startOfDay, $endOfDay);

                $array['day'] = [
                    'totalMinutes' => $totalMinutesForDay,
                    'hours' => floor($totalMinutesForDay / 60),
                    'minutes' => $totalMinutesForDay % 60,
                ];
            }
        }

        return $array;
    }

}