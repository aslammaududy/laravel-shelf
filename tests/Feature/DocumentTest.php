<?php

use App\Models\Document;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\UploadedFile;

$user = null;
$token = '';
$folder_id = '';

beforeEach(function () use (&$user, &$token, &$folder_id) {
    $user = User::factory()->create([
        'email' => 'aslam@mail.com',
        'password' => 'password',
    ]);
    $token = $user->createToken('login')->plainTextToken;

    $folder_id = Folder::create([
        'name' => 'test',
        'created_by' => $user->id,
    ])->id;
});

test('Document uploaded successfully', function () use (&$user, &$token, &$folder_id) {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('image.jpg');

    $response = $this->withToken($token)->postJson(route('documents.store'), [
        'folder_id' => $folder_id,
        'title' => fake()->title(),
        'file' => $file,
        'doc_type' => 'general',
        'document_date' => now(),
    ]);

    $response->assertStatus(201);

    $document = Document::where('folder_id', $folder_id)->first();

    $this->assertNotNull($document);
});
