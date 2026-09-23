<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Activity Logs</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <!-- ========================================================= -->
        <!-- Header -->
        <!-- ========================================================= -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Activity Logs
                </h2>

                <p class="text-muted mb-0">
                    Search, filter, export and manage application activity.
                </p>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('activity.dashboard') }}"
                    class="btn btn-primary">
                    Dashboard
                </a>

                <a
                    href="{{ route('activity.export', request()->query()) }}"
                    class="btn btn-success">
                    📥 CSV
                </a>

                <a
                    href="{{ route('activity.export.json', request()->query()) }}"
                    class="btn btn-dark">
                    📄 JSON
                </a>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- Success Message -->
        <!-- ========================================================= -->

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        <!-- ========================================================= -->
        <!-- Filter Card -->
        <!-- ========================================================= -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    🔎 Advanced Search & Filters
                </h5>

            </div>

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('activity.logs') }}">

                    <div class="row g-3">

                        <!-- Search -->

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Description, event, ID..."
                                value="{{ request('search') }}">

                        </div>


                        <!-- Event -->

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Event
                            </label>

                            <select
                                name="event"
                                class="form-select">

                                <option value="">
                                    All Events
                                </option>

                                <option
                                    value="created"
                                    {{ request('event') === 'created' ? 'selected' : '' }}>
                                    Created
                                </option>

                                <option
                                    value="updated"
                                    {{ request('event') === 'updated' ? 'selected' : '' }}>
                                    Updated
                                </option>

                                <option
                                    value="deleted"
                                    {{ request('event') === 'deleted' ? 'selected' : '' }}>
                                    Deleted
                                </option>

                            </select>

                        </div>


                        <!-- Log Name -->

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Log Name
                            </label>

                            <select
                                name="log_name"
                                class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach($logNames as $logName)

                                <option
                                    value="{{ $logName }}"
                                    {{ request('log_name') == $logName ? 'selected' : '' }}>
                                    {{ $logName }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- Subject Type -->

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Subject Type
                            </label>

                            <select
                                name="subject_type"
                                class="form-select">

                                <option value="">
                                    All Subject Types
                                </option>

                                @foreach($subjectTypes as $subjectType)

                                <option
                                    value="{{ $subjectType }}"
                                    {{ request('subject_type') == $subjectType ? 'selected' : '' }}>
                                    {{ class_basename($subjectType) }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- Causer ID -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Causer / User ID
                            </label>

                            <select
                                name="causer_id"
                                class="form-select">

                                <option value="">
                                    All Users
                                </option>

                                @foreach($causerIds as $causerId)

                                <option
                                    value="{{ $causerId }}"
                                    {{ request('causer_id') == $causerId ? 'selected' : '' }}>
                                    User #{{ $causerId }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- Date Preset -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Date Preset
                            </label>

                            <select
                                name="date_preset"
                                class="form-select">

                                <option value="">
                                    Custom / All Dates
                                </option>

                                <option
                                    value="today"
                                    {{ request('date_preset') === 'today' ? 'selected' : '' }}>
                                    Today
                                </option>

                                <option
                                    value="yesterday"
                                    {{ request('date_preset') === 'yesterday' ? 'selected' : '' }}>
                                    Yesterday
                                </option>

                                <option
                                    value="last_7_days"
                                    {{ request('date_preset') === 'last_7_days' ? 'selected' : '' }}>
                                    Last 7 Days
                                </option>

                                <option
                                    value="last_30_days"
                                    {{ request('date_preset') === 'last_30_days' ? 'selected' : '' }}>
                                    Last 30 Days
                                </option>

                            </select>

                        </div>


                        <!-- From Date -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                From Date
                            </label>

                            <input
                                type="date"
                                name="from_date"
                                class="form-control"
                                value="{{ request('from_date') }}">

                        </div>


                        <!-- To Date -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                To Date
                            </label>

                            <input
                                type="date"
                                name="to_date"
                                class="form-control"
                                value="{{ request('to_date') }}">

                        </div>


                        <!-- Sort By -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Sort By
                            </label>

                            <select
                                name="sort_by"
                                class="form-select">

                                <option
                                    value="created_at"
                                    {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>
                                    Date
                                </option>

                                <option
                                    value="id"
                                    {{ request('sort_by') === 'id' ? 'selected' : '' }}>
                                    ID
                                </option>

                                <option
                                    value="event"
                                    {{ request('sort_by') === 'event' ? 'selected' : '' }}>
                                    Event
                                </option>

                                <option
                                    value="description"
                                    {{ request('sort_by') === 'description' ? 'selected' : '' }}>
                                    Description
                                </option>

                                <option
                                    value="log_name"
                                    {{ request('sort_by') === 'log_name' ? 'selected' : '' }}>
                                    Log Name
                                </option>

                            </select>

                        </div>


                        <!-- Sort Direction -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Direction
                            </label>

                            <select
                                name="sort_direction"
                                class="form-select">

                                <option
                                    value="desc"
                                    {{ request('sort_direction', 'desc') === 'desc' ? 'selected' : '' }}>
                                    Descending
                                </option>

                                <option
                                    value="asc"
                                    {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>
                                    Ascending
                                </option>

                            </select>

                        </div>


                        <!-- Per Page -->

                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Records Per Page
                            </label>

                            <select
                                name="per_page"
                                class="form-select">

                                @foreach([5, 10, 25, 50, 100] as $number)

                                <option
                                    value="{{ $number }}"
                                    {{ $perPage == $number ? 'selected' : '' }}>
                                    {{ $number }} Records
                                </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="col-md-6 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">
                                🔍 Apply Filters
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


        <!-- ========================================================= -->
        <!-- Result Information -->
        <!-- ========================================================= -->

        <div class="alert alert-info">

            <strong>
                {{ $logs->total() }}
            </strong>

            matching activities found.

            Showing

            <strong>
                {{ $logs->count() }}
            </strong>

            on this page.

        </div>


        <!-- ========================================================= -->
        <!-- Log Retention & Pruning Archive Tool -->
        <!-- ========================================================= -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">
                    🧹 Log Retention & Automated Pruning Archive Tool
                </h5>
                <span class="badge bg-light text-dark">DB Optimization</span>
            </div>
            <div class="card-body bg-light">
                <div class="row align-items-center g-3">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            Automatically clean up older activity records to optimize database performance. Always export a Zip / CSV archive before running retention cleanup.
                        </p>
                    </div>
                    <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('activity.export-archive', ['days' => 30]) }}" class="btn btn-outline-dark fw-bold">
                            📦 Export Archive (CSV)
                        </a>
                        <form method="POST" action="{{ route('activity.prune') }}" onsubmit="return confirm('Prune logs older than selected retention days? Archived backups will be saved.');" class="d-flex gap-1">
                            @csrf
                            <select name="days" class="form-select form-select-sm" style="width: auto;">
                                <option value="30">Older than 30 days</option>
                                <option value="14">Older than 14 days</option>
                                <option value="7">Older than 7 days</option>
                                <option value="1">Older than 1 day</option>
                            </select>
                            <button type="submit" class="btn btn-warning fw-bold">
                                🧹 Prune Old Logs
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- Activity Table -->
        <!-- ========================================================= -->

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Activity History
                </h5>


                <!-- Clear All -->

                @if($logs->total() > 0)

                <form
                    method="POST"
                    action="{{ route('activity.clear') }}"
                    onsubmit="return confirm('Are you sure you want to delete ALL activity logs? This action cannot be undone.');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger">
                        🗑️ Clear All Logs
                    </button>

                </form>

                @endif

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Log Name
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    User
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Actions
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

                                    {{ $log->log_name ?? 'N/A' }}

                                </td>


                                <td>

                                    @if($log->subject_type)

                                    {{ class_basename($log->subject_type) }}

                                    <br>

                                    <small class="text-muted">
                                        #{{ $log->subject_id }}
                                    </small>

                                    @else

                                    N/A

                                    @endif

                                </td>


                                <td>

                                    @if($log->causer_id)

                                    User #{{ $log->causer_id }}

                                    @else

                                    System

                                    @endif

                                </td>


                                <td>

                                    {{ $log->created_at?->format('d M Y') }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $log->created_at?->format('h:i A') }}
                                    </small>

                                </td>


                                <td>

                                    <div class="d-flex gap-1">

                                        <!-- View -->

                                        <a
                                            href="{{ route('activity.show', $log->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        <!-- 1-Click Rollback -->
                                        <form
                                            method="POST"
                                            action="{{ route('activity.rollback', $log->id) }}"
                                            onsubmit="return confirm('Rollback this activity snapshot?');">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-warning text-dark fw-bold">
                                                ⏪ Rollback
                                            </button>
                                        </form>

                                        <!-- Delete -->

                                        <form
                                            method="POST"
                                            action="{{ route('activity.destroy', $log->id) }}"
                                            onsubmit="return confirm('Delete this activity log?');">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    <h5 class="text-muted">
                                        No Activity Logs Found
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Try changing your filters.
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- Pagination -->
        <!-- ========================================================= -->

        @if($logs->hasPages())

        <div class="mt-4">

            {{ $logs->links('pagination::bootstrap-5') }}

        </div>

        @endif


    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>