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
        $totalMinutes = $activity->totalMinutesForAllTime();

        $array = [
            'id' => $activity->id,
            'name' => $activity->name,
            'color' => $activity->color,

            'duration' => [
                'totalMinutes' => $totalMinutes,
                'hours' => floor($totalMinutes / 60),
                'minutes' => $totalMinutes % 60
            ]
        ];

        if (isset($this->params['date'])) {
            $date = $this->params['date'];
            $startOfWeek = Carbon::createFromFormat('Y-m-d', $date)->startOfWeek();
            $endOfWeek = Carbon::createFromFormat('Y-m-d', $date)->endOfWeek();
            $totalMinutesForWeek = $activity->calculateTotalMinutesForWeek($startOfWeek, $endOfWeek);

            $activity->calculateTotalMinutesForWeek($startOfWeek, $endOfWeek);
            $dailyAverageMinutes = $activity->calculateAverageMinutesPerDayForWeek($date);

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

        return $array;
    }

}