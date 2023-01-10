<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "rule" => $this->rule,
            "rules" => $this->getRoles($this),
            // "products" => $this->getProducts($this),
            "created_at" => $this->created_at,
            "deleted_at" => $this->deleted_at,
        ];
        // return parent::toArray($request);
    }

    private function getRoles($user)
    {
        $r = [];
        foreach ($user->rules as $rule) {
            array_push($r, $rule->name);
        }
        return $r;
    }

    private function getProducts($user)
    {
        $r = [];
        foreach ($user->products as $product) {
            array_push(
                $r,
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                    'created_at' => $product->created_at,
                ]
            );
        }
        return $r;
    }
}
