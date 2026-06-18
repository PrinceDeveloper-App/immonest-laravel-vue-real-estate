<?php

use App\Models\Listing;
use App\Models\User;

test('authenticated user can submit an offer', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Offer was made!');

    $this->assertDatabaseHas('offers', [
        'listing_id' => $listing->id,
        'bidder_id' => $user->id,
        'amount' => 500_000,
    ]);
});

test('guest cannot submit an offer', function () {
    $listing = Listing::factory()->create();

    $response = $this->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('offers', [
        'listing_id' => $listing->id,
    ]);
});

test('offer amount is required', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->post(
        route('listing.offer.store', $listing),
        ['amount' => '']
    );

    $response->assertSessionHasErrors('amount');
    $this->assertDatabaseMissing('offers', [
        'listing_id' => $listing->id,
    ]);
});

test('offer amount must be an integer', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->post(
        route('listing.offer.store', $listing),
        ['amount' => 'not-a-number']
    );

    $response->assertSessionHasErrors('amount');
    $this->assertDatabaseMissing('offers', [
        'listing_id' => $listing->id,
    ]);
});

test('offer amount must be at least 1', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->post(
        route('listing.offer.store', $listing),
        ['amount' => 0]
    );

    $response->assertSessionHasErrors('amount');
    $this->assertDatabaseMissing('offers', [
        'listing_id' => $listing->id,
    ]);
});

test('offer amount cannot exceed 20 million', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->post(
        route('listing.offer.store', $listing),
        ['amount' => 20_000_001]
    );

    $response->assertSessionHasErrors('amount');
    $this->assertDatabaseMissing('offers', [
        'listing_id' => $listing->id,
    ]);
});

test('show page loads correctly for authenticated user with offer widget', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->create();

    $response = $this->actingAs($user)->get(
        route('listing.show', $listing)
    );

    $response->assertOk();
    $response->assertInertia(fn ($page) =>
        $page->component('Listing/Show')
            ->has('listing')
            ->where('listing.id', $listing->id)
            ->where('listing.price', $listing->price)
    );
});

test('show page loads correctly for guest without offer widget', function () {
    $listing = Listing::factory()->create();

    $response = $this->get(route('listing.show', $listing));

    $response->assertOk();
    $response->assertInertia(fn ($page) =>
        $page->component('Listing/Show')
            ->has('listing')
    );
});
