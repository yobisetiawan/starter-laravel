<?php

namespace App\Http\Resources\V1\Example;

use App\Http\Resources\V1\Base\FileInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Sample2Resource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'sample' => new SampleResource($this->whenLoaded('sample'))
        ];
    }
}
