<?php

namespace App\Models;

use App\Traits\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Models\Timers
 */
class Activity extends Model
{
    use OwnedByUser;
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
     * Get total minutes on an activity for all time
     * @return int
     */
    public function getTotalMinutesAttribute()
    {
        $total = 0;
        foreach ($this->timers()->get() as $timer) {
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
     * @param Carbon $startOfWeek
     * @param Carbon $endOfWeek
     * @return int
     */
    public function calculateTotalMinutesForWeek(Carbon $startOfWeek, Carbon $endOfWeek)
    {
        $total = 0;
        $day = $endOfWeek->copy();

        while ($day >= $startOfWeek) {
            $total += $this->calculateTotalMinutesForDay($day->copy()->startOfDay(), $day->copy()->endOfDay());
            $day = $day->subDay();
        }

        return $this->totalMinutesForWeek = $total;
    }

    /**
     * If the week isn't over yet, instead of dividing by 7, do the
     * division according to how many days of the week have happened
     * So if today is Tuesday, divide by 3, for example.
     * @param $date
     * @return float
     */
    public function calculateAverageMinutesPerDayForWeek($date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        if (Carbon::today() < $date->copy()->endOfWeek()) {
            //The week isn't over yet.
            return round($this->totalMinutesForWeek / (Carbon::today()->dayOfWeek + 1));

        }
        else {
            return round($this->totalMinutesForWeek / 7);
        }

    }
}
