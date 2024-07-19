<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DeliveryProductResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'point'=>$this->point,
            'rare'=>$this->rare,
            'image'=>getProductImageUrl($this->image),
            'status'=>$this->status,
            'address'=>$this->address,
            'updated_at'=>$this->updated_at->diffForHumans(),
            'updated_at_time'=>$this->updated_at->format('Y年m月d日 H時i分')
        ];
    }
}
