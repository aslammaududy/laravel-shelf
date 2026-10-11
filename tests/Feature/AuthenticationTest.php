<?php

test('login successfully with non lowercase email address', function () {
    \App\Models\User::factory()->create([
        'email' => 'aslam@mail.com',
        'password' => 'password',
    ]);

    $response = $this->postJson(route('login'), [
        'email' => 'aslam@Mail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200);
});

test('logout successfully revokes the token', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'aslam@mail.com',
        'password' => 'password',
    ]);
    $token = $user->createToken('login');

    $this->withToken($token->plainTextToken)->post(route('logout'))->assertNoContent();

    $this->assertModelMissing($token->accessToken);
});
