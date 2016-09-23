<?php

namespace App\Traits;

/**
 * Class OnDate
 * @package App\Traits
 */
trait OnDate {
    /**
     *
     * @param $query
     * @param $dateString
     * @return mixed
     */
    public function scopeOnDate($query, $dateString)
    {
        $dateString = $dateString . '%';

        return $query->where(function ($q) use ($dateString) {
            $q->where('finish', 'LIKE', $dateString)
                ->orWhere('start', 'LIKE', $dateString);
        })
            ->whereNotNull('finish');
    }
}