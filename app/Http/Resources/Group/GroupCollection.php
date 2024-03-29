<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return GroupResource::collection($this->collection);
    }
}
