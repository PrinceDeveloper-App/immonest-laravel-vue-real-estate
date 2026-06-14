<?php

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

// ── priceFrom ────────────────────────────────────────────────────────────────

test('priceFrom filters out listings below the minimum price', function () {
    $match1  = Listing::factory()->create(['price' => 200_000]);
    $match2  = Listing::factory()->create(['price' => 300_000]);
    $noMatch = Listing::factory()->create(['price' => 199_999]);

    $this->get(route('listing.index', ['priceFrom' => 200_000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

// ── priceTo ──────────────────────────────────────────────────────────────────

test('priceTo filters out listings above the maximum price', function () {
    $match1  = Listing::factory()->create(['price' => 100_000]);
    $match2  = Listing::factory()->create(['price' => 200_000]);
    $noMatch = Listing::factory()->create(['price' => 200_001]);

    $this->get(route('listing.index', ['priceTo' => 200_000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

// ── beds ─────────────────────────────────────────────────────────────────────

test('beds filter uses exact match when value is less than 6', function () {
    $match1  = Listing::factory()->create(['bedrooms' => 3]);
    $match2  = Listing::factory()->create(['bedrooms' => 3]);
    $noMatch = Listing::factory()->create(['bedrooms' => 2]);

    $this->get(route('listing.index', ['beds' => 3]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

test('beds filter uses greater-than-or-equal match when value is 6', function () {
    $match1  = Listing::factory()->create(['bedrooms' => 6]);
    $match2  = Listing::factory()->create(['bedrooms' => 7]);
    $noMatch = Listing::factory()->create(['bedrooms' => 5]);

    $this->get(route('listing.index', ['beds' => 6]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

// ── baths ────────────────────────────────────────────────────────────────────

test('baths filter uses exact match when value is less than 6', function () {
    $match1  = Listing::factory()->create(['bathrooms' => 2]);
    $match2  = Listing::factory()->create(['bathrooms' => 2]);
    $noMatch = Listing::factory()->create(['bathrooms' => 1]);

    $this->get(route('listing.index', ['baths' => 2]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

test('baths filter uses greater-than-or-equal match when value is 6', function () {
    $match1  = Listing::factory()->create(['bathrooms' => 6]);
    $match2  = Listing::factory()->create(['bathrooms' => 7]);
    $noMatch = Listing::factory()->create(['bathrooms' => 5]);

    $this->get(route('listing.index', ['baths' => 6]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

// ── areaFrom / areaTo ────────────────────────────────────────────────────────

test('areaFrom filters out listings with area below the minimum', function () {
    $match1  = Listing::factory()->create(['area' => 1000]);
    $match2  = Listing::factory()->create(['area' => 1500]);
    $noMatch = Listing::factory()->create(['area' => 999]);

    $this->get(route('listing.index', ['areaFrom' => 1000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

test('areaTo filters out listings with area above the maximum', function () {
    $match1  = Listing::factory()->create(['area' => 500]);
    $match2  = Listing::factory()->create(['area' => 1000]);
    $noMatch = Listing::factory()->create(['area' => 1001]);

    $this->get(route('listing.index', ['areaTo' => 1000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $noMatch->id)
            )
        );
});

// ── deleted (realtor only) ────────────────────────────────────────────────────
// Realtor controller scopes to the authenticated user's listings only,
// so count assertions are safe — seeded data belongs to different users.

test('realtor index hides soft-deleted listings by default', function () {
    $user = User::factory()->create();
    Listing::factory()->create(['by_user_id' => $user->id]);
    Listing::factory()->create(['by_user_id' => $user->id])->delete();

    $this->actingAs($user)
        ->get(route('realtor.listing.index'))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->has('listings.data', 1)
        );
});

test('realtor index shows soft-deleted listings when deleted filter is true', function () {
    $user = User::factory()->create();
    Listing::factory()->create(['by_user_id' => $user->id]);
    Listing::factory()->create(['by_user_id' => $user->id])->delete();

    $this->actingAs($user)
        ->get(route('realtor.listing.index', ['deleted' => true]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->has('listings.data', 2)
        );
});

// ── sort by price (realtor) ───────────────────────────────────────────────────

test('sort by price ascending returns listings in ascending order', function () {
    $user = User::factory()->create();
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 300_000]);
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 100_000]);
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 200_000]);

    $this->actingAs($user)
        ->get(route('realtor.listing.index', ['by' => 'price', 'order' => 'asc']))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data.0.price', 100_000)
                 ->where('listings.data.1.price', 200_000)
                 ->where('listings.data.2.price', 300_000)
        );
});

test('sort by price descending returns listings in descending order', function () {
    $user = User::factory()->create();
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 100_000]);
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 300_000]);
    Listing::factory()->create(['by_user_id' => $user->id, 'price' => 200_000]);

    $this->actingAs($user)
        ->get(route('realtor.listing.index', ['by' => 'price', 'order' => 'desc']))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data.0.price', 300_000)
                 ->where('listings.data.1.price', 200_000)
                 ->where('listings.data.2.price', 100_000)
        );
});

// ── combined filters ──────────────────────────────────────────────────────────

test('combined priceFrom and priceTo returns only listings within the range', function () {
    $match1  = Listing::factory()->create(['price' => 150_000]);
    $match2  = Listing::factory()->create(['price' => 250_000]);
    $tooLow  = Listing::factory()->create(['price' =>  99_999]);
    $tooHigh = Listing::factory()->create(['price' => 300_001]);

    $this->get(route('listing.index', ['priceFrom' => 100_000, 'priceTo' => 300_000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match1->id) &&
                collect($data)->contains('id', $match2->id) &&
                !collect($data)->contains('id', $tooLow->id) &&
                !collect($data)->contains('id', $tooHigh->id)
            )
        );
});

test('combined beds and areaFrom filters apply both constraints', function () {
    $match   = Listing::factory()->create(['bedrooms' => 3, 'area' => 1200]);
    $wrongBed = Listing::factory()->create(['bedrooms' => 2, 'area' => 1200]);
    $wrongArea = Listing::factory()->create(['bedrooms' => 3, 'area' =>  800]);

    $this->get(route('listing.index', ['beds' => 3, 'areaFrom' => 1000]))
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->where('listings.data', fn ($data) =>
                collect($data)->contains('id', $match->id) &&
                !collect($data)->contains('id', $wrongBed->id) &&
                !collect($data)->contains('id', $wrongArea->id)
            )
        );
});
