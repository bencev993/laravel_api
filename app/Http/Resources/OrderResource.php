<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'state' => $this->state,
            'email' => $this->email,
            'phone' => $this->phone,
            'cart' => $this->cart,
            'payment_id' => $this->payment_id,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'status' => $this->status
        ];      
    }
}
