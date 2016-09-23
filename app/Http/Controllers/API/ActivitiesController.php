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
                $params['forDay'] = true;
                $activities = Activity::forCurrentUser()->onDate($date)->get();
            }

        }

        return $this->respond($activities, new ActivityTransformer($params), 200);
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

        return $this->respond($activity, new ActivityTransformer, 201);
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

        return $this->respond($activity, new ActivityTransformer, 200);
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
