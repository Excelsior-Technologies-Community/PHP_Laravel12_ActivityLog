<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProductController extends Controller
{
    /**
     * Create a new product and generate activity log.
     */
    public function create()
    {
        Product::create([
            'name' => 'Laptop',
            'price' => 50000
        ]);

        return "Product Created";
    }

    /**
     * Update the first available product and generate activity log.
     */
    public function update()
    {
        $product = Product::first();

        if (!$product) {
            return "No Product Found To Update";
        }

        $product->update([
            'price' => 55000
        ]);

        return "Product Updated";
    }

    /**
     * Delete the first available product and generate activity log.
     */
    public function delete()
    {
        $product = Product::first();

        if ($product) {
            $product->delete();

            return "Product Deleted Successfully";
        }

        return "No Product Found To Delete";
    }

    /**
     * Activity Log Dashboard.
     */
    public function dashboard()
    {
        $totalActivities = Activity::count();

        $createdActivities = Activity::where('event', 'created')->count();

        $updatedActivities = Activity::where('event', 'updated')->count();

        $deletedActivities = Activity::where('event', 'deleted')->count();

        $recentActivities = Activity::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('activity-dashboard', compact(
            'totalActivities',
            'createdActivities',
            'updatedActivities',
            'deletedActivities',
            'recentActivities'
        ));
    }

    /**
     * Advanced Activity Log Search and Filtering.
     */
    public function logs(Request $request)
    {
        $query = Activity::query();

        // Search by description, event or subject ID
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_id', 'like', "%{$search}%");
            });
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by starting date
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // Filter by ending date
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Latest activity first with pagination
        $logs = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('logs', compact('logs'));
    }

    /**
     * Display detailed information about one activity.
     */
    public function showLog($id)
    {
        $log = Activity::findOrFail($id);

        return view('activity-details', compact('log'));
    }

    /**
     * Export filtered activity logs as CSV.
     */
public function exportLogs(Request $request)
{
    $query = Activity::query();

    // Search by description, event, or subject ID
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('event', 'like', "%{$search}%")
              ->orWhere('subject_id', 'like', "%{$search}%");
        });
    }

    // Filter by event
    if ($request->filled('event')) {
        $query->where('event', $request->event);
    }

    // Filter by starting date
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    // Filter by ending date
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // Get filtered activity logs
    $logs = $query->orderBy('id', 'asc')->get();

    // Generate CSV filename
    $filename = 'activity-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

    return response()->streamDownload(function () use ($logs) {

        $handle = fopen('php://output', 'w');

        // CSV Header
        fputcsv($handle, [
            'ID',
            'Log Name',
            'Description',
            'Event',
            'Subject Type',
            'Subject ID',
            'Date'
        ]);

        // CSV Data
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->log_name,
                $log->description,
                $log->event,
                $log->subject_type,
                $log->subject_id,
                $log->created_at?->format('d-m-Y H:i'),
            ]);
        }

        fclose($handle);

    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
}
