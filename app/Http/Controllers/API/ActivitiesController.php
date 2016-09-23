<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ActivityTransformer;
use App\Models\Activity;
use App\Repositories\ActivitiesRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivitiesController extends Controller
{
    /**
     * @var ActivitiesRepository
     */
    private $activitiesRepository;

    /**
     * ActivitiesController constructor.
     * @param ActivitiesRepository $activitiesRepository
     */
    public function __construct(ActivitiesRepository $activitiesRepository)
    {
        $this->activitiesRepository = $activitiesRepository;
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $activities = Activity::forCurrentUser()->get();

        $params = null;
        if ($request->has('date')) {
            $date = $request->get('date');
            $params = ['date' => $date];

            if ($request->has('forWeek')) {
                $params['forWeek'] = true;
            }

            if ($request->has('forDay')) {
                $startOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0);
                $endOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(24)->minute(0)->second(0);

                $params['forDay'] = true;
                $activities = $this->activitiesRepository->getActivitiesForDay($startOfDay, $endOfDay);
            }

        }

        $activities = $this->transform($this->createCollection($activities, new ActivityTransformer($params)))['data'];

        return response($activities, Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $activity = new Activity($request->only(['name', 'color']));
        $activity->user()->associate(Auth::user());
        $activity->save();

        $activity = $this->transform($this->createItem($activity, new ActivityTransformer))['data'];

        return response($activity, Response::HTTP_CREATED);
    }

    /**
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Activity $activity)
    {
        // Create an array with the new fields merged
        $data = array_compare($activity->toArray(), $request->only([
            'name',
            'color'
        ]));

        $activity->update($data);

        $activity = $this->transform($this->createItem($activity, new ActivityTransformer))['data'];

        return response($activity, Response::HTTP_OK);
    }

    /**
     *
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Activity $activity)
    {
        try {
            $activity->delete();

            return response([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Activity could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }

            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
