<?php
test('user cannot delete others listing', function () {
    $user1 = \App\Models\User::factory()->create();
    $user2 = \App\Models\User::factory()->create();

    $listing = \App\Models\Listing::factory()->create([
        'by_user_id' => $user1->id,
    ]);

    $this->actingAs($user2);

    $response = $this->delete(route('realtor.listing.destroy', $listing));

    $response->assertStatus(403);
});