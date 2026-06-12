<?php
test('user can update their listing', function () {
    $user = \App\Models\User::factory()->create();

    $listing = \App\Models\Listing::factory()->create([
        'by_user_id' => $user->id,
        'city' => 'Bonn',
    ]);

    $this->actingAs($user);

    $response = $this->put(route('listing.update', $listing), [
        'city' => 'Cologne',
        'bedrooms' => 4,
        'bathrooms' => 2,
        'area' => 120,
        'code' => '50045',
        'street' => 'Main Street',
        'street_number' => '12A',
        'price' => 300000,
    ]);

    $response->assertRedirect(route('listing.index'));

    $this->assertDatabaseHas('listings', [
        'id' => $listing->id,
        'city' => 'Cologne',
    ]);
});
