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

        $created = Activity::where('event', 'created')->count();

        $updated = Activity::where('event', 'updated')->count();

        $deleted = Activity::where('event', 'deleted')->count();

        $recentActivities = Activity::latest()
            ->paginate(5);

        return view('activity-dashboard', [
            'totalActivities' => $totalActivities,
            'created' => $created,
            'updated' => $updated,
            'deleted' => $deleted,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Build reusable activity log query.
     */
    private function filteredActivityQuery(Request $request)
    {
        $query = Activity::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_id', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhere('causer_id', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Event Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        /*
        |--------------------------------------------------------------------------
        | Log Name Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        /*
        |--------------------------------------------------------------------------
        | Subject Type Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        /*
        |--------------------------------------------------------------------------
        | Causer / User Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Presets
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_preset')) {

            switch ($request->date_preset) {

                case 'today':
                    $query->whereDate(
                        'created_at',
                        now()->toDateString()
                    );
                    break;

                case 'yesterday':
                    $query->whereDate(
                        'created_at',
                        now()->subDay()->toDateString()
                    );
                    break;

                case 'last_7_days':
                    $query->whereBetween('created_at', [
                        now()->subDays(6)->startOfDay(),
                        now()->endOfDay()
                    ]);
                    break;

                case 'last_30_days':
                    $query->whereBetween('created_at', [
                        now()->subDays(29)->startOfDay(),
                        now()->endOfDay()
                    ]);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Custom From Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Custom To Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        $sortBy = $request->get('sort_by', 'created_at');

        $allowedSorts = [
            'id',
            'created_at',
            'event',
            'description',
            'log_name'
        ];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $sortDirection = $request->get(
            'sort_direction',
            'desc'
        );

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query;
    }

    /**
     * Advanced Activity Log Search and Filtering.
     */
    public function logs(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Per Page
        |--------------------------------------------------------------------------
        */
        $perPage = (int) $request->get('per_page', 5);

        $allowedPerPage = [
            5,
            10,
            25,
            50,
            100
        ];

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Logs
        |--------------------------------------------------------------------------
        */
        $logs = $this->filteredActivityQuery($request)
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown Data
        |--------------------------------------------------------------------------
        */
        $logNames = Activity::query()
            ->whereNotNull('log_name')
            ->where('log_name', '!=', '')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        $subjectTypes = Activity::query()
            ->whereNotNull('subject_type')
            ->where('subject_type', '!=', '')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        $causerIds = Activity::query()
            ->whereNotNull('causer_id')
            ->distinct()
            ->orderBy('causer_id')
            ->pluck('causer_id');

        return view('logs', compact(
            'logs',
            'logNames',
            'subjectTypes',
            'causerIds',
            'perPage'
        ));
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
        $logs = $this->filteredActivityQuery($request)
            ->get();

        $filename = 'activity-logs-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($logs) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */
                fputcsv($handle, [
                    'ID',
                    'Log Name',
                    'Description',
                    'Event',
                    'Subject Type',
                    'Subject ID',
                    'Causer Type',
                    'Causer ID',
                    'Date'
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Data
                |--------------------------------------------------------------------------
                */
                foreach ($logs as $log) {

                    fputcsv($handle, [
                        $log->id,
                        $log->log_name,
                        $log->description,
                        $log->event,
                        $log->subject_type,
                        $log->subject_id,
                        $log->causer_type,
                        $log->causer_id,
                        $log->created_at?->format(
                            'd-m-Y H:i'
                        )
                    ]);
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' =>
                'text/csv; charset=UTF-8'
            ]
        );
    }

    /**
     * Export filtered activity logs as JSON.
     */
    public function exportJson(Request $request)
    {
        $logs = $this->filteredActivityQuery($request)
            ->get();

        $data = $logs->map(function ($log) {

            return [
                'id' => $log->id,
                'log_name' => $log->log_name,
                'description' => $log->description,
                'event' => $log->event,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'causer_type' => $log->causer_type,
                'causer_id' => $log->causer_id,
                'properties' => $log->properties,
                'created_at' => $log->created_at?->format(
                    'Y-m-d H:i:s'
                ),
            ];
        });

        $filename = 'activity-logs-' .
            now()->format('Y-m-d-H-i-s') .
            '.json';

        return response()->streamDownload(
            function () use ($data) {

                echo json_encode(
                    $data,
                    JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES
                );
            },
            $filename,
            [
                'Content-Type' =>
                'application/json; charset=UTF-8'
            ]
        );
    }

    /**
     * Delete a single activity log.
     */
    public function destroyLog($id)
    {
        $log = Activity::findOrFail($id);

        $log->delete();

        return redirect()
            ->route('activity.logs')
            ->with(
                'success',
                'Activity log deleted successfully.'
            );
    }

    /**
     * Delete all activity logs.
     */
    public function clearLogs()
    {
        $count = Activity::count();

        Activity::query()->delete();

        return redirect()
            ->route('activity.logs')
            ->with(
                'success',
                $count . ' activity log(s) cleared successfully.'
            );
    }
}
