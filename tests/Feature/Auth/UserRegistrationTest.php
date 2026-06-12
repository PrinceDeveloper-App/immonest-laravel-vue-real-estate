<?php
test('user can register successfully', function () {
    $response = $this->post(route('user-account.store'), [
    'name' => 'John Doe',
    'email' => 'johntest@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
]);

$response->assertRedirect(route('listing.index'));    
$this->assertDatabaseHas('users', [
        'email' => 'johntest@example.com',
    ]);
});