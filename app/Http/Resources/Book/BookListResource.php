<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $books = $this->resource;
        $bookAux = [];
        $bookResult = [];

        foreach ($books as $book) {
            $bookAux['id']   = $book->id;
            $bookAux['name'] = $book->name;
            $bookAux['author'] = $book->author;
            $bookAux['edition'] = $book->edition;
            $bookAux['publishing'] = $book->publishing_company;
            $bookAux['user'] = new UserResource($book->user);
            $bookResult[] = $bookAux;
        }

        return $bookResult;
    }
}
