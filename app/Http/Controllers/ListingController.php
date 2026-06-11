<?php

namespace App\Http\Controllers;

use App\Models\Listing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'priceFrom',
            'priceTo',
            'beds',
            'baths',
            'areaFrom',
            'areaTo'
        ]);
        return inertia(
            'Listing/Index',
            [
                'filters' => $filters,
                'listings' => Listing::MostRecent()
                    ->Filter($filters)
                    ->paginate(10)
                    ->withQueryString()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$this->authorize('create', Listing::class);
        return inertia('Listing/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->user()->listings()->create(
            $request->validate([
                'bedrooms' => 'required | integer | min:0 | max:20',
                'bathrooms' => 'required | integer | min:0 | max:20',
                'area' => 'required | integer | min:50 | max:10000',
                'city' => 'required | string | max:255',
                'code' => 'required | string | max:255',
                'street' => 'required | string | max:255',
                'street_number' => 'nullable | string | max:255',
                'price' => 'required | integer | min:0',
            ])
        );
        return redirect()->route('listing.index')
            ->with('success', 'Listing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // if(Auth()->user()->cannot('view', $listing)) {
        //     abort(403);
        // }
        // $this->authorize('view', $listing);
        return inertia(
            'Listing/Show',
            [
                'listing' => $listing
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return inertia(
            'Listing/Edit',
            [
                'listing' => $listing
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        $listing->update(
            $request->validate([
                'bedrooms' => 'required | integer | min:0 | max:20',
                'bathrooms' => 'required | integer | min:0 | max:20',
                'area' => 'required | integer | min:50 | max:10000',
                'city' => 'required | string | max:255',
                'code' => 'required | string | max:255',
                'street' => 'required | string | max:255',
                'street_number' => 'nullable | string | max:255',
                'price' => 'required | integer | min:0',
            ])
        );
        return redirect()->route('listing.index')
            ->with('success', 'Listing updated successfully.');
    }
}
