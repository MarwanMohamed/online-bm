<?php

namespace App\Traits\Crud;

use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Eloquent\Model;

trait Update
{
    public function update(int $id, array $data)
    {
        $model = $this->findOrFail($id);
        $this->updateQuery($model, $data);

        if (isset($this->subModel)) {
            $subModel = class_basename(get_class(new $this->subModel));
            $this->updateSubQuery($model->{$subModel}, $data);
        }

        return $model->refresh();
    }

    public function updateQuery($model, array $data)
    {
        $model->update($this->updateAttrs($data));
    }

    public function updateAttrs(array $data): array
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
     * @param $subModel
     * @param $data
     * @return mixed
     */
    public function updateSubQuery($subModel, $data)
    {
        $requestArr = $this->updateSubAttrs($subModel, $data);

        return $subModel->update($requestArr);
    }

    /**
     * @param $subModel
     * @param $data
     * @return array
     */
    public function updateSubAttrs($subModel, $data): array
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
