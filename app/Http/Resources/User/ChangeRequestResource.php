<?php

namespace App\Http\Resources\User;

use App\B5Digital\Transformers\DateFormatter;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User **/
class ChangeRequestResource extends JsonResource
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
            'user_id' => $this->user_id,
            'draft_email' => $this->draft_email,
            'is_active' => $this->is_active,
            'is_expired' => $this->expire_at->lt(Carbon::now()),
            'expire_at' => DateFormatter::make($this->expire_at),
            'created_at' => DateFormatter::make($this->created_at),
        ];
    }
}
