<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'priceFrom', 'priceTo',
            'beds', 'baths',
            'areaFrom', 'areaTo',
        ]);

        return Listing::mostRecent()
            ->filter($filters)
            ->withoutSold()
            ->paginate(10);
    }

    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);

        return $listing->load('images');
    }
}
