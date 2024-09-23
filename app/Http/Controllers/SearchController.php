<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\SearchLog;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $filters = $request->query();

        // Log the search filters
        SearchLog::create(['filters' => json_encode($filters)]);

        $search = new Search();
        $apartments = $search->filter($filters);

        // Check if the result is empty
        if ($apartments->isEmpty()) {
            return response()->json(['message' => 'No matches found'], 404);
        }

        return response()->json($apartments);
    }
}
