<?php
test('user can delete their listing', function () {
    $user = \App\Models\User::factory()->create();

    $listing = \App\Models\Listing::factory()->create([
        'by_user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = $this->delete(route('realtor.listing.destroy', $listing));

    $response->assertRedirect(route('realtor.listing.index'));

    $this->assertSoftDeleted('listings', [
        'id' => $listing->id,
    ]);
});
