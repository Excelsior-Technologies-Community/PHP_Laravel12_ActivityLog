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


    {{-- Recent Activity --}}
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