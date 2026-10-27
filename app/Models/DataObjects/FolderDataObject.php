<?php

namespace App\Models\DataObjects;

use App\Http\Requests\FolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Traits\Arrayable;
use Illuminate\Support\Arr;

class FolderDataObject
{
    use Arrayable;

    public function __construct(
        public ?string $parent_id,
        public ?string $name,
        public string $created_by,
        public bool $is_movable = false // the folder can be moved when the body has parent_id even if the value is null
    ) {}

    public static function fromRequest(FolderRequest|UpdateFolderRequest $request): FolderDataObject
    {
        return new self(
            parent_id: $request->parent_id,
            name: $request->name,
            created_by: $request->user()->id,
            is_movable: $request instanceof UpdateFolderRequest && $request->has('parent_id'),
        );
    }

    // check the Arrayable trait for this function definition
    public function toArray(): array
    {
        return Arr::except(get_object_vars($this), ['is_movable']);
    }
}
