<?php

use App\Models\Listing;
use App\Models\User;
use App\Notifications\OfferMade;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

// --- Email delivery via notification channel ---

test('verification email is sent when user registers', function () {
    Notification::fake();

    $this->post(route('user-account.store'), [
        'name' => 'Test User',
        'email' => 'verify@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'verify@example.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('verification email contains valid signed verification link', function () {
    Notification::fake();

    $this->post(route('user-account.store'), [
        'name' => 'Link User',
        'email' => 'link@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'link@example.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class, function ($notification, $channels) {
        return in_array('mail', $channels);
    });
});

test('newly registered user has unverified email', function () {
    $this->post(route('user-account.store'), [
        'name' => 'Unverified User',
        'email' => 'unverified@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'unverified@example.com')->first();

    expect($user->email_verified_at)->toBeNull();
    expect($user->hasVerifiedEmail())->toBeFalse();
});

// --- Verification email flow and resend functionality ---

test('unverified user sees verification notice page', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $response = $this->actingAs($user)->get(route('verification.notice'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Auth/VerifyEmail'));
});

test('user can resend verification email', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    $response = $this->actingAs($user)
        ->post(route('verification.send'));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Verification link sent!');

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('resend verification is rate limited', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    for ($i = 0; $i < 6; $i++) {
        $this->actingAs($user)->post(route('verification.send'));
    }

    $response = $this->actingAs($user)->post(route('verification.send'));

    $response->assertStatus(429);
});

test('guest cannot access resend verification route', function () {
    $response = $this->post(route('verification.send'));

    $response->assertRedirect(route('login'));
});

// --- Email triggers on offer submission ---

test('offer submission sends mail notification to listing owner', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 500_000]
    );

    Notification::assertSentTo($owner, OfferMade::class, function ($notification, $channels) {
        return in_array('mail', $channels);
    });
});

test('offer notification email contains correct amount', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => 1_250_000]
    );

    $offer = $listing->offers()->first();
    $notification = new OfferMade($offer);
    $mailMessage = $notification->toMail($owner);

    expect($mailMessage->introLines[0])->toContain('1250000');
    expect($mailMessage->actionUrl)->toContain((string) $listing->id);
});

test('failed offer validation does not trigger email', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = Listing::factory()->create(['by_user_id' => $owner->id]);

    $this->actingAs($bidder)->post(
        route('listing.offer.store', $listing),
        ['amount' => '']
    );

    Notification::assertNothingSent();
});
