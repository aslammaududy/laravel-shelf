<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('folder_id')->index()->constrained('folders')->restrictOnDelete();
            $table->string('title');
            $table->text('original_filename');
            $table->string('mime_type');
            $table->bigInteger('size_bytes');
            $table->char('sha256', 64)->index();
            $table->text('storage_key');
            $table->enum('doc_type', ['contract', 'invoice', 'receipt', 'hr_record', 'general'])->default('general');
            $table->date('document_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('content_text')->nullable();
            $table->tsvector('search_vector')->storedAs(
                "to_tsvector('simple', coalesce(title, '') || ' ' || coalesce(notes, '') || ' ' || coalesce(content_text, ''))"
            );
            $table->foreignUuid('uploaded_by')->index()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index('search_vector', null, 'gin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
