<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TimerTransformer;
use App\Models\Activity;
use App\Models\Timer;
use App\Repositories\GraphsRepository;
use App\Repositories\TimersRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TimersController extends Controller
{
    /**
     * @var GraphsRepository
     */
    private $graphsRepository;

    /**
     * TimersController constructor.
     * @param GraphsRepository $graphsRepository
     */
    public function __construct(GraphsRepository $graphsRepository)
    {
        $this->graphsRepository = $graphsRepository;
    }

    /**
     * GET /api/timers
     * @param Request $request
     * @return \App\Http\Controllers\Response|static
     */
    public function index(Request $request)
    {
        //This bit is for the graphs
        if ($request->has('byDate')) {
            $timers = Timer::forCurrentUser()->get();

            return $this->graphsRepository->getTimersInDateRange($timers);
        }

        else {
            //Return the timers for the date
            $timers = Timer::forCurrentUser()->onDate($request->get('date'))->get();

            return $this->respond($timers, new TimerTransformer(['date' => $request->get('date')]), 200);
        }
    }

    /**
     * GET /api/timers/{timers}
     * @param Request $request
     * @param Timer $timer
     * @return Response
     */
    public function show(Request $request, Timer $timer)
    {
        return $this->respond($timer, new TimerTransformer, 200);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $timer = new Timer($request->only([
            'start',
            'finish'
        ]));

        $timer->user()->associate(Auth::user());

        $activity = Activity::find($request->get('activity_id'));
        if (!$activity) {
            $activity = Activity::forCurrentUser()->where('name', 'sleep')->first();
        }

        $timer->activity()->associate($activity);
        $timer->save();

        return $this->respond($timer, new TimerTransformer(['date' => $this->calculateFinishDate($timer)]), 201);
    }

    /**
     *
     * @param Request $request
     * @param Timer $timer
     * @return Response
     */
    public function update(Request $request, Timer $timer)
    {
        //Create an array with the new fields merged
        $data = array_compare($timer->toArray(), $request->only([
            'start',
            'finish'
        ]));

        $timer->update($data);

        if ($request->has('activity_id')) {
            $timer->activity()->associate(Activity::findOrFail($request->get('activity_id')));
            $timer->save();
        }

        $finishDate = $this->calculateFinishDate($timer);

        return $this->respond($timer, new TimerTransformer(['date' => $finishDate]), 200);
    }

    /**
     *
     * @param Timer $timer
     * @return null|string
     */
    private function calculateFinishDate(Timer $timer)
    {
        if ($timer->finish) {
            $finishDate = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish)->format('Y-m-d');
        }
        else {
            $finishDate = null;
        }

        return $finishDate;
    }

    /**
     *
     * @return \App\Http\Controllers\Response|Response
     */
    public function checkForTimerInProgress()
    {
        $timerInProgress = Timer::forCurrentUser()->whereNull('finish')->first();

        if ($timerInProgress) {
            return $this->respond($timerInProgress, new TimerTransformer, 200);
        }

        return response([], Response::HTTP_OK);
    }

    /**
     *
     * @param Timer $timer
     * @return Response
     * @throws \Exception
     */
    public function destroy(Timer $timer)
    {
        $timer->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

}
