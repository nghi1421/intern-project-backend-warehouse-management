<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public function __construct($resource, protected bool $isPagination = false)
    {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->isPagination ?
            [
                'data' => $this->collection,
                'meta' => [
                    'current_page' => $this->currentPage(),
                    'total_items' => $this->total(),
                    'per_page' => $this->perPage(),
                    'total_pages' => $this->lastPage()
                ],
                'links' => [
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                    'self' => $this->url($this->currentPage())
                ]
            ] :
            parent::toArray($request);;
    }
}
