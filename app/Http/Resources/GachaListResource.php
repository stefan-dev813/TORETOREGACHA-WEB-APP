<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GachaListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $count_rest = $this->count_card-$this->count; 
        $ableCount = $this->ableCount();
        
        return [
            'id'=>$this->id,
            'point'=>$this->point,
            'count_card'=>$this->count_card,
            'count'=>$this->count,
            'count_rest'=>$count_rest,
            'ableCount'=>$ableCount,
            // 'productCount' => $productCount,
            'image'=>getGachaImageUrl($this->image),
            'thumbnail'=>getGachaThumbnailUrl($this->thumbnail),
            'status'=>$this->status,
        ];
    }
}
