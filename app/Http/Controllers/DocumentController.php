<?php

namespace App\Http\Controllers;

use App\Actions\DownloadDocumentAction;
use App\Actions\PreviewDocumentAction;
use App\Actions\UploadDocumentAction;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\DataObjects\DocumentDataObject;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Document::class);

        return DocumentResource::collection(Document::latest()->paginate(25));
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

    public function show(Document $document): DocumentResource
    {
        $this->authorize('view', $document);

        return new DocumentResource($document);
    }

    public function update(DocumentRequest $request, Document $document): DocumentResource
    {
        $this->authorize('update', $document);

        $document->update($request->validated());

        return new DocumentResource($document);
    }

    public function destroy(Document $document): Response
    {
        $this->authorize('delete', $document);

        $document->delete();

        return response()->noContent();
    }

    public function download(Document $document, DownloadDocumentAction $download): StreamedResponse
    {
        $this->authorize('viewAny', $document);

        return $download($document);
    }

    public function preview(Document $document, PreviewDocumentAction $preview): StreamedResponse
    {
        $this->authorize('view', $document);

        return $preview($document);
    }
}
