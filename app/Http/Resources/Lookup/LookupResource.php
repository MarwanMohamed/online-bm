<?php

namespace App\Http\Resources\Lookup;

use App\B5Digital\Transformers\DateFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Lookup\Lookup **/
class LookupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'value' => $this->value,
            'model_type' => $this->model_type,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'is_system' => $this->is_system,
            'created_at' => DateFormatter::make($this->created_at),
        ];
    }
}
