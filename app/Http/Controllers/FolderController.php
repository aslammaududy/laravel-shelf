<?php

namespace App\Http\Controllers;

use App\Http\Requests\FolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FolderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Folder::class);

        return FolderResource::collection(Folder::all());
    }

    public function store(FolderRequest $request)
    {
        $this->authorize('create', Folder::class);

        return new FolderResource(Folder::create($request->validated()));
    }

    public function show(Folder $folder)
    {
        $this->authorize('view', $folder);

        return new FolderResource($folder);
    }

    public function update(FolderRequest $request, Folder $folder)
    {
        $this->authorize('update', $folder);

        $folder->update($request->validated());

        return new FolderResource($folder);
    }

    public function destroy(Folder $folder)
    {
        $this->authorize('delete', $folder);

        $folder->delete();

        return response()->json();
    }
}
