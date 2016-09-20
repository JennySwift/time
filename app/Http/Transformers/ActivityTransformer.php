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
        $array = [
            'id' => $activity->id,
            'name' => $activity->name,
            'totalMinutes' => $activity->totalMinutesForAllTime(),
            'color' => $activity->color,
        ];

        return $array;
    }

}