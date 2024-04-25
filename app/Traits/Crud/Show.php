<?php

namespace App\Traits\Crud;

use App\Exceptions\ItemNotFoundException;

trait Show
{
    public function find($id)
    {
        return $this->model()::find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->model()::findOrFail($id);
    }

    public function findOneBy(array $criteria)
    {
        return $this->model()::firstWhere($criteria);
    }

    public function findBy(array $criteria)
    {
        return $this->model()::where($criteria)->get();
    }
}
