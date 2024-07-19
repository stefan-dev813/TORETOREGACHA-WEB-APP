<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProductListResource extends JsonResource
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
            'dp'=>$this->dp,
            'rare'=>$this->rare,
            'marks'=>$this->marks,
            'lost_type'=>$this->lost_type,
            // 'emission_percentage'=>$this->emission_percentage,
            'image'=>getProductImageUrl($this->image),
            'is_last'=>$this->is_last,
            'gacha_id'=>$this->gacha_id,

            'category_id'=>$this->category_id,
            'product_type'=>$this->product_type,
            'status_product'=>$this->status_product,

            'status'=>$this->status,
            'is_lost_product'=>$this->is_lost_product,
            'updated_at'=>$this->updated_at->diffForHumans(),
            'updated_at_time'=>$this->updated_at->format('Y年m月d日 H時i分')
        ];
    }
}
