<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtorListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    public function index(Request $request)
    {
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ...$request->only(['by', 'order']),
        ];

        return Auth::user()
            ->listings()
            ->filter($filters)
            ->withCount('images')
            ->withCount('offers')
            ->paginate(5);
    }

    public function show(Listing $listing)
    {
        return $listing->load('offers', 'offers.bidder');
    }

    public function store(Request $request)
    {
        $listing = $request->user()->listings()->create(
            $request->validate([
                'title' => 'required|string|max:255',
                'bedrooms' => 'required|integer|min:0|max:20',
                'bathrooms' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:50|max:10000',
                'city' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'street_number' => 'nullable|string|max:255',
                'price' => 'required|integer|min:0',
            ])
        );

        return response()->json([
            'message' => 'Listing created successfully.',
            'listing' => $listing,
        ], 201);
    }

    public function update(Request $request, Listing $listing)
    {
        $listing->update(
            $request->validate([
                'title' => 'required|string|max:255',
                'bedrooms' => 'required|integer|min:0|max:20',
                'bathrooms' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:50|max:10000',
                'city' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'street_number' => 'nullable|string|max:255',
                'price' => 'required|integer|min:0',
            ])
        );

        return response()->json([
            'message' => 'Listing updated successfully.',
            'listing' => $listing,
        ]);
    }

    public function destroy(Listing $listing)
    {
        $listing->deleteOrFail();

        return response()->json([
            'message' => 'Listing deleted successfully.',
        ]);
    }

    public function restore(Listing $listing)
    {
        $this->authorize('restore', $listing);
        $listing->restore();

        return response()->json([
            'message' => 'Listing restored successfully.',
        ]);
    }

    public function accept(Offer $offer)
    {
        $listing = $offer->listing;
        $this->authorize('update', $listing);

        $offer->update(['accepted_at' => now()]);
        $listing->sold_at = now();
        $listing->save();

        $listing->offers()->except($offer)
            ->update(['rejected_at' => now()]);

        return response()->json([
            'message' => "Offer #{$offer->id} accepted, other offers rejected",
        ]);
    }
}
