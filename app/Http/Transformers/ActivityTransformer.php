<?php

namespace App\Http\Transformers;

use App\Models\Activity;
use League\Fractal\TransformerAbstract;

/**
 * Class ActivityTransformer
 */
class ActivityTransformer extends TransformerAbstract
{
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

        return $array;
    }

}