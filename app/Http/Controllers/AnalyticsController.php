<?php
namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    // Get total number of properties
    public function totalProperties()
    {
        $total = Property::count();

        return response()->json([
            'success' => true,
            'total_properties' => $total,
        ]);
    }

    // Get number of views per property
    public function propertyViews()
    {
        $views = Property::select('id', 'title', 'view_count')
            ->orderBy('view_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $views,
        ]);
    }

    // Get number of inquiries (assuming you have an Inquiry model)
    public function inquiries()
    {
        // Assuming you have an Inquiry model and it relates to Property
        $inquiryCounts = \DB::table('inquiries')
            ->select('property_id', \DB::raw('count(*) as inquiry_count'))
            ->groupBy('property_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inquiryCounts,
        ]);
    }

    // Get popular property types
    public function popularPropertyTypes()
    {
        $popularTypes = Property::select('property_type', \DB::raw('count(*) as count'))
            ->groupBy('property_type')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularTypes,
        ]);
    }

    // Get views over time (example for daily views)
    public function viewsOverTime()
    {
        // Assuming you are tracking daily views in a separate table or column
        $viewsOverTime = \DB::table('property_views') // replace with your actual views table
            ->select('date', \DB::raw('sum(view_count) as total_views'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $viewsOverTime,
        ]);
    }
}
