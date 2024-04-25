<?php

namespace App\Traits\Crud;

trait Destroy
{
    public function destroy($id)
    {
        $model = $this->findOrFail($id);

        if (isset($this->subModel)) {
            $subModel = class_basename(get_class(new $this->subModel));
            if ($model->{$subModel}) {
                $model->{$subModel}->delete();
            }
        }

        $model->delete();
    }
}
