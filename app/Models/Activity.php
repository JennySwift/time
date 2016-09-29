<?php

namespace App\Models;

use App\Traits\OnDate;
use App\Traits\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Models\Timers
 */
class Activity extends Model
{
    use OwnedByUser, OnDate;
    /**
     * @var string
     */
    protected $table = 'activities';

    /**
     * @var array
     */
    protected $fillable = ['name', 'color'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timers()
    {
        return $this->hasMany('App\Models\Timer');
    }

    /**
     *
     * @param $startOfWeek
     * @param $endOfWeek
     * @return mixed
     */
    public function timersForWeek($startOfWeek, $endOfWeek)
    {
        $timers = $this->hasMany('App\Models\Timer')
            ->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->where(function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->where('start', '>', $startOfWeek)
                        ->where('start', '<', $endOfWeek);
                });
                $q->orWhere(function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->where('finish', '>', $startOfWeek)
                        ->where('finish', '<', $endOfWeek);
                });
            })
            ->whereNotNull('finish')
            ->get();

        return $timers;
    }

    /**
     * Get total minutes on an activity for all time
     * @return int
     */
    public function getTotalMinutesAttribute()
    {
        $total = 0;
        foreach ($this->timers as $timer) {
            if ($timer->finish) {
                $total += $timer->totalMinutes;
            }
        }

        return $total;
    }

    /**
     * Calculate how many minutes have been spent on the activity
     * for the day
     * @param Carbon $startOfDay
     * @param Carbon $endOfDay
     * @return int
     */
    public function calculateTotalMinutesForDay(Carbon $startOfDay, Carbon $endOfDay)
    {
        $total = 0;
        $timers = $this->timers()->onDate($startOfDay->copy()->format('Y-m-d'))->get();

        foreach ($timers as $timer) {
            $total += $timer->getTotalMinutesForDay($startOfDay);
        }

        return $total;
    }

    /**
     * Calculate how many minutes have been spent on the activity
     * for the week
     * @param $date
     * @return int
     */
    public function getTotalMinutesForWeek($date)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $startOfWeek = Carbon::createFromFormat('Y-m-d', $date)->startOfWeek();
        $endOfWeek = Carbon::createFromFormat('Y-m-d', $date)->endOfWeek();

        $total = 0;

        foreach($this->timersForWeek($startOfWeek, $endOfWeek) as $timer) {
            $total+= $timer->totalMinutes;

            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish);
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $timer->start);

            //Subtract any time the timer ran for before the start of the week
            if ($start < $startOfWeek) {
                $total-=$startOfWeek->diffInMinutes($start);
            }

            //Subtract any time the timer continues after the end of the week
            if ($finish > $endOfWeek) {
                $total-=$endOfWeek->diffInMinutes($finish);
            }
        }

        return $total;
    }

    /**
     * If the week isn't over yet, instead of dividing by 7, do the
     * division according to how many days of the week have happened
     * So if today is Tuesday, divide by 3, for example.
     * @param $date
     * @param $totalMinutesForWeek
     * @return float
     */
    public function calculateAverageMinutesPerDayForWeek($date, $totalMinutesForWeek)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        if (Carbon::today() < $date->copy()->endOfWeek()) {
            //The week isn't over yet.
            return round($totalMinutesForWeek / (Carbon::today()->dayOfWeek + 1));
        }
        else {
            return round($totalMinutesForWeek / 7);
        }

    }

    /**
     *
     * @param $query
     * @param $dateString
     * @return mixed
     */
    public function scopeOnDate($query, $dateString)
    {
        return $query->whereHas('timers', function ($q) use ($dateString)
        {
            $q->onDate($dateString);
        });

    }
}
