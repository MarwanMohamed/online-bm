<?php

namespace App\Traits\Crud;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait Store
{
    /**
     * @param $data
     * @return mixed
     * @throws ValidationException
     */
    public function store(array $data = [])
    {
        $model = $this->storeQuery($data);

        if (isset($this->subModel)) {
            $this->storeSubQuery($data, $model->id);
        }

        return $model;
    }

    public function storeQuery($data)
    {
        return ($this->model)::create($this->storeAttrs($data));
    }

    /**
     * @param $data
     * @return array
     */
    public function storeAttrs($data): array
    {
        $requestArr = [];
        foreach ($data as $key => $value) {
            if (Schema::hasColumn(app(get_class(new $this->model))->getTable(), $key)) {
                $requestArr = array_merge($requestArr, [$key => $value]);
            }
        }

        return $requestArr;
    }

    /**
     * @param $data
     * @param $modelId
     * @return mixed
     */
    public function storeSubQuery($data, $modelId)
    {
        $requestArr = $this->storeSubAttrs($data, $modelId);
        return ($this->subModel)::create($requestArr);
    }

    /**
     * @param $data
     * @param $modelId
     * @param $subModel
     * @return array
     */
    public function storeSubAttrs($data, $modelId = null, $subModel = null): array
    {
        $requestArr = [];
        foreach ($data as $key => $value) {
            if (Schema::hasColumn(app(get_class(new $subModel))->getTable(), $key)) {
                $requestArr = array_merge($requestArr, [$key => $value]);
            }
        }

        return $requestArr;
    }
}
