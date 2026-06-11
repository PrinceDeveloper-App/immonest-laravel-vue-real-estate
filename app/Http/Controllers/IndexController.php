<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        //dd(Listing::all());
        //dd(Auth::user());
        return inertia('Index/Index',
            [
                'message' => 'Welcome to LaraZillow'
            ]
        );
    }
    public function show()
    {
        return inertia('Index/Show',
            [
                'message' => 'Welcome to the Show Page'
            ]
        );
    }
}
