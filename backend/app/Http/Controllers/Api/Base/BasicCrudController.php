<?php

namespace App\Http\Controllers\Api\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;
use EloquentFilter\Filterable;

abstract class BasicCrudController extends Controller
{
    protected $defaultPerPage = 15;
    protected $id;

    abstract protected function model();

    abstract protected function rulesStore();

    abstract protected function rulesUpdate();

    abstract protected function resource();

    abstract protected function resourceCollection();

    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', $this->defaultPerPage);
        $page = (int) $request->get('perPage');
        $hasFilter = in_array(Filterable::class, class_uses($this->model()));
        $query = $this->queryBuilder();

        if ($hasFilter) {
            $query = $query->filter($request->all());
        }
        if ($page === -1 || $perPage === -1 || !$this->defaultPerPage) {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }
        $resourceCollectionClass = $this->resourceCollection();
        $refClass = new ReflectionClass($this->resourceCollection());
        return $refClass->isSubclassOf(ResourceCollection::class)
            ? new $resourceCollectionClass($data)
            : $resourceCollectionClass::collection($data);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $obj = $this->queryBuilder()->create($validatedData);
        $obj->refresh();
        $resource = $this->resource();
        return new $resource($obj);
    }

    protected function findOrFail($id, $with = null)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        if ($with) {
            return $this->queryBuilder()->with($with)->where($keyName, $id)->firstOrFail();
        }
        return $this->queryBuilder()->where($keyName, $id)->firstOrFail();
    }

    public function show($id)
    {
        $obj = $this->findOrFail($id);
        $resource = $this->resource();
        return new $resource($obj);
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $this->id = $id;
        $validatedData = $this->validate(
            $request,
            $request->isMethod('PUT') ? $this->rulesUpdate() : $this->rulesPatch()
        );
        $obj->update($validatedData);
        $resource = $this->resource();
        return new $resource($obj);
    }

    protected function rulesPatch()
    {
        return array_map(function ($rules) {
            if (is_array($rules)) {
                $exists = in_array('required', $rules);
                if ($exists) {
                    array_unshift($rules, 'sometimes');
                }
            } else {
                return str_replace('required', 'sometimes|required', $rules);
            }
            return $rules;
        }, $this->rulesUpdate());
    }

    public function destroy($id)
    {
        $obj = $this->findOrFail($id);
        $obj->delete();
        return response()->noContent(); //204 - no content
    }

    public function destroyCollection(Request $request)
    {
        $data = $this->validateIds($request);
        $this->model()::whereIn('id', $data['ids'])->delete();
        return response()->noContent();
    }

    protected function validateIds(Request $request)
    {
        $model = $this->model();
        $ids = explode(',', $request->get('ids'));

        $validator = \Validator::make(
            [
                'ids' => $ids
            ],
            [
                'ids' => 'required|exists:' . (new $model)->getTable() . ',id'
            ]
        );
        return $validator->validate();
    }

    protected function queryBuilder(): Builder
    {
        return $this->model()::query();
    }
}
