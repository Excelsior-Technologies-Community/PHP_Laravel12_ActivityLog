<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Activity Logs</title>

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
            Activity Logs
        </h2>

        <p class="text-muted mb-0">
            Search, filter, export and inspect application activity.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('activity.export', request()->query()) }}"
            class="btn btn-success">

            📥 Export CSV

        </a>

        <a
            href="{{ route('activity.dashboard') }}"
            class="btn btn-primary">

            Activity Dashboard

        </a>

    </div>

</div>


    <!-- Search & Filter Card -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Search & Filter Activity
            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('activity.logs') }}">

                <div class="row g-3">

                    <!-- Search -->
                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Description, event or subject ID"
                            value="{{ request('search') }}"
                        >

                    </div>


                    <!-- Event -->
                    <div class="col-md-2">

                        <label class="form-label">
                            Event
                        </label>

                        <select name="event"
                                class="form-select">

                            <option value="">
                                All Events
                            </option>

                            <option value="created"
                                {{ request('event') == 'created' ? 'selected' : '' }}>
                                Created
                            </option>

                            <option value="updated"
                                {{ request('event') == 'updated' ? 'selected' : '' }}>
                                Updated
                            </option>

                            <option value="deleted"
                                {{ request('event') == 'deleted' ? 'selected' : '' }}>
                                Deleted
                            </option>

                        </select>

                    </div>


                    <!-- From Date -->
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    <!-- To Date -->
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    <!-- Buttons -->
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">
                            Search
                        </button>

                        <a
                            href="{{ route('activity.logs') }}"
                            class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- Result Information -->
    <div class="alert alert-info">

        Showing
        <strong>{{ $logs->count() }}</strong>
        activities on this page.

        Total matching records:
        <strong>{{ $logs->total() }}</strong>

    </div>


    <!-- Activity Table -->
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Activity History
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Description</th>

                            <th>Event</th>

                            <th>Subject ID</th>

                            <th>Date</th>

                            <th width="120">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($logs as $log)

                        <tr>

                            <td>
                                {{ $log->id }}
                            </td>


                            <td>
                                {{ $log->description }}
                            </td>


                            <td>

                                @if($log->event === 'created')

                                    <span class="badge bg-success">
                                        Created
                                    </span>

                                @elseif($log->event === 'updated')

                                    <span class="badge bg-warning text-dark">
                                        Updated
                                    </span>

                                @elseif($log->event === 'deleted')

                                    <span class="badge bg-danger">
                                        Deleted
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $log->event ?? 'N/A' }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $log->subject_id ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $log->created_at->format('d M Y, h:i A') }}
                            </td>


                            <td>

                                <a
                                    href="{{ route('activity.show', $log->id) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-4">

                                No Activity Logs Found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- Pagination -->

    @if($logs->hasPages())

        <div class="mt-4">

            {{ $logs->links('pagination::bootstrap-5') }}

        </div>

    @endif


</div>

</body>

</html>