<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Base\BasicCrudController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;
use EloquentFilter\Filterable;

class UserController extends BasicCrudController
{
    private $rules;

    public function __construct()
    {
        $this->rules = [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'min:3', 'max:255', "unique:users,email,{$this->id},id"],
            'password' => ['required', 'string', 'confirmed', 'min:6', 'max:20'],
            'pis' => ['required'],
            'cpf' => ['required', new Cpf],
            'country' => ['nullable'],
            'state' => ['nullable'],
            'city' => ['nullable'],
            'zipcode' => ['nullable'],
            'street_address' => ['nullable'],
            'street_number' => ['nullable'],
            'street_compl' => ['nullable'],
        ];
    }

    protected function model()
    {
        return User::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        $this->rules['email'] = ['required', 'min:3', 'max:255', "unique:users,email,{$this->id},id"];
        $this->rules['password'] = ['nullable', 'string', 'confirmed', 'min:6', 'max:20'];
        return $this->rules;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }

    protected function resource()
    {
        return UserResource::class;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', $this->defaultPerPage);
        $page = (int) $request->get('page');
        $hasFilter = in_array(Filterable::class, class_uses($this->model()));
        $query = $this->queryBuilder();

        if ($hasFilter) {
            $query = $query->filter($request->all());
        }
        if ($page === -1) {
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
        $validatedData['password'] = bcrypt($validatedData['password']);
        $obj = $this->model()::create($validatedData);
        $obj->refresh();
        if ($request->hasFile('image') && $request->image->isValid()) {
            $obj->photos()->create(['image' => $request->image->store("users/{$obj['slug']}")]);
        }
        $resource = $this->resource();
        return new $resource($obj);
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $this->id = $id;
        $validatedData = $this->validate($request, $this->rulesUpdate());
        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        $obj->update($validatedData);
        if ($request->hasFile('image') && $request->image->isValid()) {
            if ($photo = $obj->photos()->first()) {
                Storage::delete($photo->image);
                $photo->update(['image' => $request->image->store("users/{$obj['slug']}")]);
            } else {
                $obj->photos()->create(['image' => $request->image->store("users/{$obj['slug']}")]);
            }
        }
        $resource = $this->resource();
        return new $resource($obj);
    }

    public function destroy($id)
    {
        if (auth()->user()->id == $id) {
            return response('Você não pode deletar seu próprio usuário', 401);
        }
        $obj = $this->findOrFail($id);
        $obj->delete();
        return response()->noContent(); //204 - no content
    }
}
