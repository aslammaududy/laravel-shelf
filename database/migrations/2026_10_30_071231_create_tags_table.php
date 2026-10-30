<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        DB::statement('CREATE UNIQUE INDEX tags_name_lower_unique ON tags (lower(name))');
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
