<?php
namespace App\Http\Resources\User;

use App\Http\Resources\Address\AddressResource;
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
            'id'  => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'age' => $this->age,
            'cpf' => $this->cpf,
            'address' => new AddressResource($this->address)
        ];
    }
}
