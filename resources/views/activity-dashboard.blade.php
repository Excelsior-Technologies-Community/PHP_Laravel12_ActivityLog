<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Activity Log Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

<div class="container py-5">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                Activity Log Dashboard
            </h2>

            <p class="text-muted mb-0">
                Monitor product activity and system changes.
            </p>

        </div>


        <a
            href="{{ route('activity.logs') }}"
            class="btn btn-dark">

            View All Logs

        </a>

    </div>


    <!-- Statistics Cards -->

    <div class="row g-4 mb-5">


        <!-- Total -->

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Activities
                    </h6>

                    <h2 class="fw-bold">
                        {{ $totalActivities }}
                    </h2>

                    <p class="mb-0 text-muted">
                        All recorded activities
                    </p>

                </div>

            </div>

        </div>


        <!-- Created -->

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Products Created
                    </h6>

                    <h2 class="fw-bold text-success">
                        {{ $createdActivities }}
                    </h2>

                    <p class="mb-0 text-muted">
                        Creation activities
                    </p>

                </div>

            </div>

        </div>


        <!-- Updated -->

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Products Updated
                    </h6>

                    <h2 class="fw-bold text-warning">
                        {{ $updatedActivities }}
                    </h2>

                    <p class="mb-0 text-muted">
                        Modification activities
                    </p>

                </div>

            </div>

        </div>


        <!-- Deleted -->

        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Products Deleted
                    </h6>

                    <h2 class="fw-bold text-danger">
                        {{ $deletedActivities }}
                    </h2>

                    <p class="mb-0 text-muted">
                        Deletion activities
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- Recent Activity -->

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Recent Activity
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

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

                            <td>
                                {{ $activity->id }}
                            </td>


                            <td>
                                {{ $activity->description }}
                            </td>


                            <td>

                                @if($activity->event === 'created')

                                    <span class="badge bg-success">
                                        Created
                                    </span>

                                @elseif($activity->event === 'updated')

                                    <span class="badge bg-warning text-dark">
                                        Updated
                                    </span>

                                @elseif($activity->event === 'deleted')

                                    <span class="badge bg-danger">
                                        Deleted
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $activity->event ?? 'N/A' }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $activity->subject_id ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $activity->created_at->format('d M Y, h:i A') }}
                            </td>


                            <td>

                                <a
                                    href="{{ route('activity.show', $activity->id) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View Details

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4">

                                No activities recorded yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>

</body>

</html>