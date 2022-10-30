<?php

namespace App\Http\Resources\V1\Base;

use App\Http\Resources\V1\Profile\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FileInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'url' => !empty($this->url) ? $this->url . '?up=' . Carbon::parse($this->updated_at)->timestamp : null,
            'slug' => $this->slug,
            'ref_table' => $this->ref_table,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            $this->mergeWhen($this->ref_type == User::class, [
                'user' => new UserResource($this->whenLoaded('refable'))
            ]),

        ];
    }
}
