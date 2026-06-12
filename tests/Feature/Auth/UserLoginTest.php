<?php
use App\Models\User;

test('user can login', function () {

    $user = User::factory()->create([
    'password' => 'password',
]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('listing.index'));

    $this->assertAuthenticatedAs($user);
});