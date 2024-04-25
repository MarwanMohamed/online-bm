<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $baseRepository)
    {
        $this->repository = $baseRepository;
    }

    public function setModel($model)
    {
        $this->repository->setModel($model);
    }

    public function datatable(Request $request)
    {
        return $this->repository
            ->setDatatableResponse(true)
            ->setPaginate(true)
            ->index($request);
    }

    public function listAll(Request $request)
    {
        return $this->repository
            ->setDatatableResponse(false)
            ->setPaginate(false)
            ->index($request);
    }

    public function store(array $data)
    {
        $model = $this->repository->store($data);
        return $model;
    }

    public function find(int $id)
    {
        $model = $this->repository->find($id);
        return $model;
    }

    public function findOrFail(int $id)
    {
        $model = $this->repository->findOrFail($id);
        return $model;
    }

    public function update(int $id, array $data)
    {
        $model = $this->repository->update($id, $data);
        return $model;
    }

    public function destroy(int $id)
    {
        $this->repository->destroy($id);
    }
}
