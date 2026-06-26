<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Offer;
use App\Notifications\OfferMade;
use Illuminate\Http\Request;

class ListingOfferController extends Controller
{
    public function store(Listing $listing, Request $request)
    {
        $this->authorize('view', $listing);

        $offer = $listing->offers()->save(
            Offer::make(
                $request->validate([
                    'amount' => 'required|integer|min:1|max:20000000',
                ])
            )->bidder()->associate($request->user())
        );

        $listing->owner->notify(new OfferMade($offer));

        return response()->json([
            'message' => 'Offer was made!',
            'offer' => $offer,
        ], 201);
    }
}
