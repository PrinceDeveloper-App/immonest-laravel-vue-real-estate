<?php
use App\Models\Listing;

test('user can view listings', function () {
    $listings = Listing::factory()->count(3)->create();

    $response = $this->get(route('listing.index'));

    $response->assertOk();

    // Optional: ensure data is passed to view/Inertia
    $response->assertInertia(fn ($page) =>
        $page->has('listings', 13)
    );
});