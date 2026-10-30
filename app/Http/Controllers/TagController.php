<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function __invoke(TagRequest $request): AnonymousResourceCollection
    {
        $q = $request->validated('q');
        $tags = Tag::query()
            ->when($q, fn (Builder $query) => $query->whereLike('name', "$q%"))
            ->orderBy('name')
            ->limit(20)
            ->get();

        return TagResource::collection($tags);
    }
}
