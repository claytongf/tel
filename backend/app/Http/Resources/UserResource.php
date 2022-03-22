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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'pis' => $this->pis,
            'cpf' => $this->cpf,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'street_address' => $this->street_address,
            'street_number' => $this->street_number,
            'street_compl' => $this->street_compl,
        ];
    }
}
