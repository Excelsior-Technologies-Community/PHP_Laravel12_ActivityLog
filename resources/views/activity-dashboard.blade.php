<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Activity Log Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f8f9fa;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        .activity-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .pagination {
            gap: 5px;
        }

        .pagination .page-link {
            border-radius: 8px !important;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-item.active .page-link {
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="container py-5">

    {{-- Header --}}
    <div class="dashboard-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <h1 class="mb-2">Activity Log Dashboard</h1>

                <p class="mb-0">
                    Monitor product activity and system changes.
                </p>
            </div>

            <div>
                <a
                    href="{{ url('/logs') }}"
                    class="btn btn-light"
                >
                    View All Logs
                </a>
            </div>

        </div>
    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        {{-- Total Activities --}}
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Total Activities
                    </h6>

                    <div class="stat-number">
                        {{ $totalActivities }}
                    </div>

                    <small class="text-muted">
                        All recorded activities
                    </small>

                </div>
            </div>
        </div>


        {{-- Created --}}
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Products Created
                    </h6>

                    <div class="stat-number">
                        {{ $created }}
                    </div>

                    <small class="text-muted">
                        Creation activities
                    </small>

                </div>
            </div>
        </div>


        {{-- Updated --}}
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Products Updated
                    </h6>

                    <div class="stat-number">
                        {{ $updated }}
                    </div>

                    <small class="text-muted">
                        Modification activities
                    </small>

                </div>
            </div>
        </div>


        {{-- Deleted --}}
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Products Deleted
                    </h6>

                    <div class="stat-number">
                        {{ $deleted }}
                    </div>

                    <small class="text-muted">
                        Deletion activities
                    </small>

                </div>
            </div>
        </div>

    </div>


    {{-- Security Threat & Suspicious Activity Detector --}}
    @if(isset($securityThreats))
        <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4">🚨</span>
                    <div>
                        <h5 class="mb-0 text-white">Suspicious Activity & Security Threat Detector</h5>
                        <small class="text-secondary">Real-time telemetry analysis of system log anomalies</small>
                    </div>
                </div>
                <div>
                    @if($securityThreats['threat_level'] === 'HIGH THREAT')
                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">CRITICAL THREAT DETECTED</span>
                    @elseif($securityThreats['threat_level'] === 'MODERATE WARNING')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">MODERATE ANOMALY DETECTED</span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">SYSTEM SECURE</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded-3 text-center">
                            <small class="text-muted d-block mb-1">Deletions in Last Hour</small>
                            <span class="fs-3 fw-bold {{ $securityThreats['deletions_last_hour'] >= 3 ? 'text-danger' : 'text-dark' }}">
                                {{ $securityThreats['deletions_last_hour'] }}
                            </span>
                            @if($securityThreats['suspicious_bulk_deletes'])
                                <span class="badge bg-danger-subtle text-danger d-block mt-2">⚠️ High Frequency Deletion Warning</span>
                            @else
                                <span class="badge bg-success-subtle text-success d-block mt-2">Normal Deletion Rate</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded-3 text-center">
                            <small class="text-muted d-block mb-1">Price Anomalies Detected</small>
                            <span class="fs-3 fw-bold {{ $securityThreats['price_anomalies_count'] > 0 ? 'text-warning' : 'text-dark' }}">
                                {{ $securityThreats['price_anomalies_count'] }}
                            </span>
                            @if($securityThreats['price_anomalies_count'] > 0)
                                <span class="badge bg-warning-subtle text-warning d-block mt-2">⚠️ Large >20% Price Shift Logs</span>
                            @else
                                <span class="badge bg-success-subtle text-success d-block mt-2">Price Consistency Stable</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded-3 text-center">
                            <small class="text-muted d-block mb-1">Telemetry Status</small>
                            <span class="fs-3 fw-bold text-primary">Active Guard</span>
                            <span class="badge bg-info-subtle text-info d-block mt-2">100% Real-Time Tracking</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card activity-card">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                <div>
                    <h4 class="mb-1">
                        Recent Activity
                    </h4>

                    <p class="text-muted mb-0">
                        Latest product and system activities.
                    </p>
                </div>

                <a
                    href="{{ url('/logs') }}"
                    class="btn btn-primary"
                >
                    Manage Logs
                </a>

            </div>

        </div>


        <div class="card-body px-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Event</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentActivities as $activity)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    <strong>
                                        {{ $activity->id }}
                                    </strong>
                                </td>


                                {{-- Description --}}
                                <td>
                                    {{ $activity->description }}
                                </td>


                                {{-- Event --}}
                                <td>

                                    @php
                                        $eventClass = match($activity->event) {
                                            'created' => 'bg-success',
                                            'updated' => 'bg-warning text-dark',
                                            'deleted' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $eventClass }}">
                                        {{ ucfirst($activity->event ?? 'Unknown') }}
                                    </span>

                                </td>


                                {{-- Subject --}}
                                <td>

                                    @if($activity->subject_id)
                                        {{ class_basename($activity->subject_type) }}
                                        #{{ $activity->subject_id }}
                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- Date --}}
                                <td>
                                    {{ $activity->created_at?->format('d M Y, h:i A') }}
                                </td>


                                {{-- Action --}}
                                <td>

                                    <a
                                        href="{{ url('/logs/' . $activity->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Details
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <h5>
                                            No activities recorded yet.
                                        </h5>

                                        <p class="mb-0">
                                            Product activity will appear here.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Numeric Pagination Only --}}
            @if($recentActivities->hasPages())

                <div class="d-flex justify-content-center mt-4">

                    <nav aria-label="Activity pagination">

                        <ul class="pagination mb-0">

                            @foreach(
                                $recentActivities->getUrlRange(
                                    1,
                                    $recentActivities->lastPage()
                                )
                                as $page => $url
                            )

                                <li
                                    class="page-item {{ $page == $recentActivities->currentPage() ? 'active' : '' }}"
                                >

                                    <a
                                        class="page-link"
                                        href="{{ $url }}"
                                    >
                                        {{ $page }}
                                    </a>

                                </li>

                            @endforeach

                        </ul>

                    </nav>

                </div>

            @endif

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>