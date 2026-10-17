<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->foreignUuid('parent_id')->nullable()->index()->constrained('folders')->restrictOnDelete();
            $table->string('name');
            $table->foreignUuid('created_by')->nullable()->index()->constrained('users');
            $table->timestamps();
            $table->unique(['parent_id', 'name'])->nullsNotDistinct();
        });

        DB::table('folders')->insert([
            'id' => '01a13e2c-4342-704f-b55b-e7c90248e3cd',
            'parent_id' => null,
            'name' => 'Inbox',
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
