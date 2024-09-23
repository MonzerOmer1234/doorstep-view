<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function search(Request $request)
    {
        // Convert the request input into an array
        $filters = $request->all();
        
        // Call the filter method with the array
        $apartments = $this->search->filter($filters);

        return response()->json($apartments);
    }
}
