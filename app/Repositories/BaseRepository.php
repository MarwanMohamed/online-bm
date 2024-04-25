<?php

namespace App\Repositories;

use App\Traits\Crud\Show;
use App\Traits\Crud\Index;
use App\Traits\Crud\Store;
use App\Traits\Crud\Update;
use App\Traits\Crud\Destroy;

class BaseRepository
{
    use Index, Store, Update, Show, Destroy;

    protected $model;
    protected $subModel;

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function model()
    {
        if (!$this->model) {
            throw new \Exception('Model not set.');
        }
        return $this->model;
    }

    public function setSubModel($subModel)
    {
        $this->subModel = $subModel;
        return $this;
    }
}
