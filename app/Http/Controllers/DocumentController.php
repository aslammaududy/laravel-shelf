<?php

namespace App\Http\Controllers;

use App\Actions\UploadDocumentAction;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\DataObjects\DocumentDataObject;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Document::class);

        return DocumentResource::collection(Document::all());
    }

    /**
     * @throws \Throwable
     */
    public function store(DocumentRequest $request, UploadDocumentAction $uploadDocumentAction): JsonResponse
    {
        $this->authorize('create', Document::class);

        $file = $request->file('file');

        $document = $uploadDocumentAction(DocumentDataObject::fromRequest($request), $file);

        return (new DocumentResource($document))
            ->response()
            ->header('Location', route('documents.show', $document));
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return new DocumentResource($document);
    }

    public function update(DocumentRequest $request, Document $document)
    {
        $this->authorize('update', $document);

        $document->update($request->validated());

        return new DocumentResource($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return response()->json();
    }
}
