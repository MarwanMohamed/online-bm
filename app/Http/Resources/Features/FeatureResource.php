<?php

namespace App\Http\Resources\Features;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data= json_decode(json_encode($this->resource), true);
        $data['accessible']= !is_null($this->active_at);
        return  $data;
    }
}
