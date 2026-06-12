<?php
test('authenticated user can create listing', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('listing.store'), [
        'bedrooms' => 3,
        'bathrooms' => 2,
        'area' =>1200,
        'city' => 'Troisdorf',
        'code' => 'ABCD',
        'street' => 'GOTEN',
        'street_number' => 'ABGCF',
        'price' => 250000,
    ]);

    $response->assertRedirect(route('listing.index'));

    $this->assertDatabaseHas('listings', [
        'city' => 'Troisdorf',
    ]);
});