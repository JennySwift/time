<?php

namespace App\Repositories;

use Carbon\Carbon;

class ActivitiesRepository
{

    /**
     *
     * @param $date
     * @return array
    //     */
//    public function calculateTotalMinutesForWeek($date)
//    {
//        Carbon::setWeekStartsAt(Carbon::SUNDAY);
//        Carbon::setWeekEndsAt(Carbon::SATURDAY);
//
//        //For calculating total untracked time
////        $totalMinutesForAllActivities = 0;
//
//        $activities = Activity::forCurrentUser()->get();
//
////        foreach ($activities as $activity) {
////            $totalMinutesForAllActivities += $activity->totalMinutesForWeek;
////        }
//
//        //todo: this made my transformer error
////        $activities[] = $this->getUntrackedTimeForWeek($totalMinutesForAllActivities, $date);
//
//        return $activities;
//    }

    /**
     * Instead of subtracting the totalMinutesForAllActivitesForWeek
     * from the total minutes in week,
     * subtract from the total minutes from Sunday
     * till the end of the current day.
     * Actually, that didn't turn out well. So just subtract from
     * the total minutes in a week.
     * @param $totalMinutesForAllActivitiesForWeek
     * @param $date
     * @return array
     */
    private function getUntrackedTimeForWeek($totalMinutesForAllActivitiesForWeek, $date)
    {
        //Sunday === 0, Monday === 1, etc
        $dayNumber = Carbon::createFromFormat('Y-m-d', $date)->dayOfWeek;

//        dd(24 * 60 * ($dayNumber + 1), $totalMinutesForAllActivitiesForWeek);

//        $untrackedTotalMinutesForWeek = 24 * 60 * ($dayNumber + 1) - $totalMinutesForAllActivitiesForWeek;
        $untrackedTotalMinutesForWeek = 24 * 60 * 7 - $totalMinutesForAllActivitiesForWeek;

        return [
            'name' => 'untracked',
            'totalMinutesForWeek' => $untrackedTotalMinutesForWeek,
        ];
    }

    /**
     *
     * @param $totalMinutesForAllActivitiesForDay
     * @return array
     */
    private function getUntrackedTimeForDay($totalMinutesForAllActivitiesForDay)
    {
        $untrackedTotalMinutesForDay = 24 * 60 - $totalMinutesForAllActivitiesForDay;
        $untrackedHoursForDay = floor($untrackedTotalMinutesForDay / 60);

        return [
            'name' => 'untracked till end of day',
            'totalMinutesForDay' => $untrackedTotalMinutesForDay,
            'hoursForDay' => $untrackedHoursForDay,
            'minutesForDay' => $untrackedTotalMinutesForDay % 60
        ];
    }

}