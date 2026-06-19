<?php

use App\Models\Listing;
use App\Models\User;
use App\Notifications\OfferMade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

uses(RefreshDatabase::class);

// --- Notification creation on offer submission ---

test('notification is created for listing owner when offer is submitted', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    expect($owner->notifications)->toHaveCount(1);
    expect($owner->notifications->first()->type)->toBe(OfferMade::class);
});

test('notification stores correct offer data', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 750_000]
    );

    $notification = $owner->notifications()->first();

    expect($notification->data)->toMatchArray([
        'listing_id' => $listing->id,
        'amount' => 750_000,
        'bidder_id' => $bidder->id,
    ]);
    expect($notification->data)->toHaveKey('offer_id');
});

test('notification is not created when offer validation fails', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => '']
    );

    expect($owner->notifications)->toHaveCount(0);
});

// --- Notifications stored and retrieved correctly ---

test('authenticated user can view their notifications page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('notification.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications')
    );
});

test('guest cannot view notifications page', function () {
    $response = $this->get(route('notification.index'));

    $response->assertRedirect(route('login'));
});

test('notifications are paginated', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    for ($i = 0; $i < 12; $i++) {
        $this->actingAs($bidder)->post(
            route('listing.offer.store', $listing),
            ['amount' => 100_000 + $i]
        );
    }

    $response = $this->actingAs($owner)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications.data', 10)
            ->has('notifications.links')
    );
});

test('user only sees their own notifications', function () {
    $owner1 = User::factory()->create();
    $owner2 = User::factory()->create();
    $bidder = User::factory()->create();
    $listing1 = Listing::factory()->create(['by_user_id' => $owner1->id]);
    $listing2 = Listing::factory()->create(['by_user_id' => $owner2->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing1),
        ['amount' => 500_000]
    );
    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing2),
        ['amount' => 600_000]
    );

    $response = $this->actingAs($owner1)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications.data', 1)
    );
});

// --- Mark-as-read with authorization ---

test('owner can mark their notification as read', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $notification = $owner->notifications()->first();
    expect($notification->read_at)->toBeNull();

    $response = $this->actingAs($owner)->put(
        route('notification.seen', $notification)
    );

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Notification marked as read');
    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('user cannot mark another users notification as read', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $notification = $owner->notifications()->first();

    $response = $this->actingAs($otherUser)->put(
        route('notification.seen', $notification)
    );

    $response->assertForbidden();
    expect($notification->fresh()->read_at)->toBeNull();
});

test('guest cannot mark notification as read', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $offer = new \App\Models\Offer(['amount' => 500_000]);
    $offer->bidder()->associate($bidder);
    $listing->offers()->save($offer);
    $owner->notify(new OfferMade($offer));

    $notification = $owner->notifications()->first();

    $response = $this->put(
        route('notification.seen', $notification)
    );

    $response->assertRedirect(route('login'));
    expect($notification->fresh()->read_at)->toBeNull();
});

// --- UI states (with and without notifications) ---

test('empty state is shown when user has no notifications', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications.data', 0)
    );
});

test('notifications page shows unread notifications without read_at', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $response = $this->actingAs($owner)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications.data', 1)
            ->where('notifications.data.0.read_at', null)
    );
});

test('notifications page reflects read status after marking as read', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    $notification = $owner->notifications()->first();
    $this->actingAs($owner)->put(route('notification.seen', $notification));

    $response = $this->actingAs($owner)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->has('notifications.data', 1)
            ->where('notifications.data.0.read_at', fn ($value) => $value !== null)
    );
});

test('notification contains offer amount and listing reference', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 1_200_000]
    );

    $response = $this->actingAs($owner)->get(route('notification.index'));

    $response->assertInertia(fn ($page) =>
        $page->component('Notifications/Index')
            ->where('notifications.data.0.data.amount', 1_200_000)
            ->where('notifications.data.0.data.listing_id', $listing->id)
    );
});
