<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Document::class);

        return DocumentResource::collection(Document::all());
    }

    public function store(DocumentRequest $request)
    {
        $this->authorize('create', Document::class);

        return new DocumentResource(Document::create($request->validated()));
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
