<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;  // Assuming you have a Property model
use App\Models\Inquiry;   // Assuming you have an Inquiry model
use App\Services\ReportService; // Hypothetical service for reports

class DashboardController extends Controller
{
    // Get a list of properties for the dashboard
    public function getProperties(Request $request)
    {
        // Example: Filter by user or fetch all
        $properties = Property::all(); // Customize query as needed (e.g., add filtering)
        return response()->json([
            'status' => 'success',
            'message' => 'Properties fetched successfully',
            'properties' => $properties
        ] , 200);
    }

    // Get statistics for the dashboard (e.g., most viewed, inquiries, sales)
    public function getStatistics()
    {
        $statistics = [
            'total_properties' => Property::count(),
            'total_inquiries' => Inquiry::count(),
            'most_viewed_property' => Property::orderBy('views', 'desc')->first()
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Statistics fetched successfully',
            'statistics' => $statistics
        ], 200);
    }

    // Get list of inquiries
    public function getInquiries()
    {
        $inquiries = Inquiry::all(); // Customize query as needed
        return response()->json([
            'status' => 'success',
            'message' => 'Inquiries fetched successfully',
            'inquiries' => $inquiries
        ], 200);
    }






}
