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
            ['listings' => Auth::user()
            ->listings()
            //->mostRecent()
            ->filter($filters)
            ->get()
            ]
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->deleteOrFail();
        return redirect()->back()
            ->with('success', 'Listing deleted successfully.');
    }
}
