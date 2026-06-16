<?php

namespace App\Http\Controllers;

use App\Models\Listing;
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
       // dd($request->boolean('deleted'));
       $filters = [
        'deleted' => $request->boolean('deleted'),
        ...$request->only(['by', 'order'])
       ];
        return inertia(
            'Realtor/Index',
            [
                'filters' => $filters,
                'listings' => Auth::user()
            ->listings()
            //->mostRecent()
            ->filter($filters)
            ->withCount('images')
            ->paginate(5)
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
        return inertia('Realtor/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->user()->listings()->create(
            $request->validate([
                'title' => 'required | string | max:255',
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
        return redirect()->route('realtor.listing.index')
            ->with('success', 'Listing created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return inertia(
            'Realtor/Edit',
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
                'title' => 'required | string | max:255',
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
        return redirect()->route('realtor.listing.index')
            ->with('success', 'Listing updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->deleteOrFail();
        return redirect()->route('realtor.listing.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function restore(Listing $listing)
    {
        $this->authorize('restore', $listing);
        $listing->restore();
        return redirect()->route('realtor.listing.index')
            ->with('success', 'Listing restored successfully.');
    }
}
