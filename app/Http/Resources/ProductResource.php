<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
          'price' => $this->price,
          'stock' => $this->stock,
          'description' => $this->description,
          'discount' => $this->discount,
          'category_id' => $this->category_id,
          'images' => $this->images,
          'sold' => $this->sold
        ];
    }
}
