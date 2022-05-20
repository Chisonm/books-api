<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BooksResource extends JsonResource
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
            'title' => $this->title,
            'authors' => $this->authors,
            'isbn' => $this->isbn,
            'publisher' => $this->publisher,
            'country' => $this->country,
            'number_of_pages' => $this->number_of_pages,
            'released' => $this->released,
            'comment_count' => $this->commentsCount(),
            'comments' => $this->comments ?? null,
        ];
    }
}
