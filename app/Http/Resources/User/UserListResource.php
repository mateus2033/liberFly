<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Address\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $users      = $this->resource;
        $userAux    = [];
        $userResult = [];

        foreach ($users as $user) {
            $userAux['id']   = $user->id;
            $userAux['name'] = $user->name;
            $userAux['email'] = $user->email;
            $userResult[] = $userAux;
        }
        return $userResult;
    }
}
