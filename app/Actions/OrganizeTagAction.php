<?php

namespace App\Actions;

use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrganizeTagAction
{
    public function __invoke(array $tags)
    {
        $organizedTags = Arr::map($tags, function ($tag) {
            return Str::of($tag)->trim()->title()->toString();
        });

        $organizedTags = array_unique($organizedTags);

        $updatedTags = Tag::query()->whereIn('name', $organizedTags)->get();

        if (count($updatedTags)) {
            $updatedTags->each(function ($tag) {
                $tag->update(['name' => $tag->name]);
            });
        } else {
            foreach ($organizedTags as $organizedTag) {
                $updatedTags[] = Tag::create(['name' => $organizedTag]);
            }
        }

        return $updatedTags->pluck('id');
    }
}
