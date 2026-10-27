<?php

namespace App\Http\Controllers;

use App\Actions\CreateFolderAction;
use App\Actions\DeleteFolderAction;
use App\Actions\UpdateFolderAction;
use App\Http\Requests\FolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\DataObjects\FolderDataObject;
use App\Models\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FolderController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Folder::class);

        return FolderResource::collection(Folder::with('createdBy')->orderBy('name')->paginate(25));
    }

    public function store(FolderRequest $request, CreateFolderAction $createFolderAction): JsonResponse
    {
        $this->authorize('create', Folder::class);

        $folder = $createFolderAction(FolderDataObject::fromRequest($request));

        return (new FolderResource($folder->load('createdBy')))
            ->response()
            ->header('Location', route('folders.show', $folder));
    }

    public function show(Folder $folder): FolderResource
    {
        $this->authorize('view', $folder);

        return new FolderResource($folder->load('createdBy'));
    }

    public function update(UpdateFolderRequest $request, Folder $folder, UpdateFolderAction $updateFolderAction): FolderResource
    {
        $this->authorize('update', $folder);

        $updatedFolder = $updateFolderAction(FolderDataObject::fromRequest($request), $folder);

        return new FolderResource($updatedFolder->load('createdBy'));
    }

    public function destroy(Folder $folder, DeleteFolderAction $deleteFolderAction): Response
    {
        $this->authorize('delete', $folder);

        $deleteFolderAction($folder);

        return response()->noContent();
    }
}
