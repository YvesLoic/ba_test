<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class ProductResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'image' => $this->image == null ? null : $request->getSchemeAndHttpHost() . '' . $this->image,
            'published' => $this->published,
            'autor' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
                'phone' => $this->creator->phone,
                'rule' => $this->creator->rule,
            ],
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
        // return parent::toArray($request);
    }
}
