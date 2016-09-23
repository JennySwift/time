<?php

namespace App\Models;

use App\Traits\OnDate;
use App\Traits\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timer
 * @package App\Models\Timer
 */
class Timer extends Model
{
    use OwnedByUser, OnDate;

    /**
     * @var string
     */
    protected $table = 'timers';

    /**
     * @var array
     */
    protected $fillable = ['start', 'finish'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }

    /**
     *
     * @param bool $fakeStart
     * @return int
     */
    public function getStartRelativeHeight($fakeStart = false)
    {
        if ($fakeStart) {
            return 0;
        }

        return Carbon::createFromFormat('Y-m-d H:i:s',
            $this->start)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->hour(0)->minute(0));
    }

    /**
     *
     * @return int
     */
    public function getFinishRelativeHeight()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',
            $this->finish)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->hour(0)->minute(0));
    }

    /**
     *
     * @return bool|static
     */
    public function getFinish()
    {
        if (!$this->finish) return false;

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
    }

    /**
     *
     * @return null|string
     */
    public function getFinishDateAttribute()
    {
        if ($this->finish) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->format('Y-m-d');
        }

        return null;
    }

    /**
     *
     * @return int
     */
    public function getTotalMinutesAttribute()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);

        return $finish->diffInMinutes($start);
    }

    /**
     * Not the total minutes. If total minutes is 90, for calculating
     * 1 hour, 30 mins.
     */
    public function getMinutesAttribute()
    {
        return $this->totalMinutes % 60;
    }

    /**
     *
     * @return array
     */
    public function getDurationForDayAttribute()
    {
        return [
            'hours' => $this->hoursForDay,
            'minutes' => $this->minutesForDay
        ];
    }

    /**
     * If total minutes is 90, hours is 1.
     * @return int
     */
    public function getHoursAttribute()
    {
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);

        return $finish->diffInHours(Carbon::createFromFormat('Y-m-d H:i:s', $this->start));
    }

    /**
     * If the timer was started the day before,
     * only start counting the total from 12:00am.
     * Or only count until 12:00am, depending on if the day is the
     * start of the finish of the timer.
     * @param Carbon $dateTime = start of day
     * @return int
     */
    public function getTotalMinutesForDay(Carbon $dateTime)
    {
        //If timer is still going, make the duration for the timer 0 minutes
        if (!$this->finish) {
            return 0;
        }

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
//        var_dump('datetime: ' . $dateTime, 'start: ' . $start, 'finish: ' . $finish);

        if ($start->isSameDay($finish)) {
            return $finish->diffInMinutes($start);
        }
        else {
            if ($start->isSameDay($dateTime)) {
                //Make the finish at the end of the day
                //instead of the next day
                $finish = $dateTime->copy()->addDay()->startOfDay();
//                dd($finish, $start, $finish->diffInMinutes($start));
            }
            else {
                if ($finish->isSameDay($dateTime)) {
                    //Make the start at the beginning of the day instead of the previous day
                    $start = $dateTime->copy()->startOfDay();
//                dd($finish, $start, $finish->diffInMinutes($start));
                }
            }

            return $finish->diffInMinutes($start);
        }
    }

    /**
     * For calculating the time spent sleeping in 24 hours,
     * from midnight till midnight.
     * If it goes overnight it will not take that into account.
     * For the graph. Used in TimersRepository.
     * @param bool $nullValue
     * @return int
     */
    public function getDurationInMinutesDuringOneDay($nullValue = false)
    {
        if (!$nullValue) {
            //Start and finish times are on the same day
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);

            return $finish->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->start));
        }
        else {
            if ($nullValue === 'start') {
                //The entry was finished on one day and started on an earlier day,
                //so calculate the time from the finish till the most recent midnight
                $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
                $midnight = clone $finish;
                $midnight = $midnight->hour(0)->minute(0);

                return $finish->diffInMinutes($midnight);
            }
            else {
                if ($nullValue === 'finish') {
                    //The entry was started on one day and finished on a later day,
                    //so calculate the time from the start till midnight
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
                    $midnight = clone $start;
                    $midnight = $midnight->hour(24)->minute(0);

                    return $start->diffInMinutes($midnight);
                }
            }
        }

    }
}
