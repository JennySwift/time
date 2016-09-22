<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;


class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @param Model               $model
     * @param TransformerAbstract $transformer
     * @param null                $key
     * @return Item
     */
    function createItem($model, TransformerAbstract $transformer, $key = null)
    {
        return new Item($model, $transformer, $key);
    }

    /**
     *
     * @param $resource
     * @param TransformerAbstract $transformer
     * @param $responseCode
     * @param array|null $includes
     * @param Request $request
     * @return Response
     */
    public function respond($resource, TransformerAbstract $transformer, $responseCode, array $includes = null, Request $request = null)
    {
        if ($resource instanceof EloquentCollection) {
            $resource = $this->transform($this->createCollection($resource, $transformer), $includes, $request)['data'];
        }
        else {
            $resource = $this->transform($this->createItem($resource, $transformer), $includes, $request)['data'];
        }

        return response($resource, $responseCode);
    }

    /**
     * For Fractal transformer
     * @param $resource
     * @param null $includes
     * @return array
     */
    public function transform($resource, $includes = null)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

//        if (isset($_GET['include'])) {
//            $manager->parseIncludes($_GET['include']);
//        }

        if ($includes) {
            $manager->parseIncludes($includes);
        }

//        $manager->parseIncludes(request()->get('includes', []));

        //This seems to be causing an error with my __construct method in ProvidersController.php:
        //'Call to a member function has() on null'
//        if ($this->request->has('include')) {
//            $manager->parseIncludes($this->request->include);
//        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection($model, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($model, $transformer, $key);
    }
}
