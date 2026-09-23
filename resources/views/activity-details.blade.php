<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Activity Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body class="bg-light">

    <div class="container py-5">


        <!-- ========================================================= -->
        <!-- Header -->
        <!-- ========================================================= -->

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <strong>✅ Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <strong>❌ Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Activity Details
                </h2>

                <p class="text-muted mb-0">
                    Detailed information about this activity.
                </p>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('activity.logs') }}"
                    class="btn btn-dark">
                    ← Back to Logs
                </a>

                {{-- 1-Click Rollback & Time Machine Button --}}
                <form
                    method="POST"
                    action="{{ route('activity.rollback', $log->id) }}"
                    onsubmit="return confirm('Are you sure you want to rollback to this historical state?');">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark fw-bold shadow-sm">
                        ⏪ 1-Click Rollback
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('activity.destroy', $log->id) }}"
                    onsubmit="return confirm('Delete this activity log?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger">
                        🗑️ Delete
                    </button>

                </form>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- Activity Information -->
        <!-- ========================================================= -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    Activity Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-4">

                        <strong>
                            Activity ID
                        </strong>

                        <p class="mt-1">
                            {{ $log->id }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Description
                        </strong>

                        <p class="mt-1">
                            {{ $log->description }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Event
                        </strong>

                        <p class="mt-1">

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

                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Log Name
                        </strong>

                        <p class="mt-1">
                            {{ $log->log_name ?? 'N/A' }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Subject Type
                        </strong>

                        <p class="mt-1">
                            {{ $log->subject_type ?? 'N/A' }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Subject ID
                        </strong>

                        <p class="mt-1">
                            {{ $log->subject_id ?? 'N/A' }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Causer Type
                        </strong>

                        <p class="mt-1">
                            {{ $log->causer_type ?? 'System' }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Causer ID
                        </strong>

                        <p class="mt-1">
                            {{ $log->causer_id ?? 'System' }}
                        </p>

                    </div>


                    <div class="col-md-4">

                        <strong>
                            Activity Date
                        </strong>

                        <p class="mt-1">
                            {{ $log->created_at?->format('d M Y, h:i A') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- Change Information -->
        <!-- ========================================================= -->

        <div class="row g-4">


            <!-- Old Values -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header bg-danger text-white">

                        <h5 class="mb-0">
                            Old Values
                        </h5>

                    </div>


                    <div class="card-body">

                        @php

                        $oldValues = $log->properties
                        ? $log->properties->get('old', [])
                        : [];

                        @endphp


                        @if(!empty($oldValues))

                        <table class="table table-bordered">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Field
                                    </th>

                                    <th>
                                        Old Value
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($oldValues as $field => $value)

                                <tr>

                                    <td>

                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                        </strong>

                                    </td>

                                    <td>

                                        {{ is_array($value)
                                            ? json_encode($value)
                                            : ($value ?? 'NULL')
                                        }}

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                        @else

                        <div class="alert alert-secondary mb-0">

                            No old values available.

                        </div>

                        @endif

                    </div>

                </div>

            </div>


            <!-- New Values -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">
                            New Values
                        </h5>

                    </div>


                    <div class="card-body">

                        @php

                        $newValues = $log->properties
                        ? $log->properties->get('attributes', [])
                        : [];

                        @endphp


                        @if(!empty($newValues))

                        <table class="table table-bordered">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Field
                                    </th>

                                    <th>
                                        New Value
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($newValues as $field => $value)

                                <tr>

                                    <td>

                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                        </strong>

                                    </td>

                                    <td>

                                        {{ is_array($value)
                                            ? json_encode($value)
                                            : ($value ?? 'NULL')
                                        }}

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                        @else

                        <div class="alert alert-secondary mb-0">

                            No new values available.

                        </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- Deep Visual Diff Comparator -->
        <!-- ========================================================= -->
        <div class="card shadow-sm mt-4 border-0 rounded-3 overflow-hidden">
            <div class="card-header text-white p-3" style="background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                <h5 class="mb-0 text-white font-bold">
                    🔍 Deep Visual Diff Comparator & Side-by-Side Property Inspector
                </h5>
            </div>
            <div class="card-body p-4">
                @php
                    $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                @endphp

                @if(!empty($allFields))
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 25%;">Field Name</th>
                                    <th style="width: 37.5%;" class="bg-danger text-white">Before (Old Historical State)</th>
                                    <th style="width: 37.5%;" class="bg-success text-white">After (New Modified State)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allFields as $field)
                                    @php
                                        $oldVal = $oldValues[$field] ?? null;
                                        $newVal = $newValues[$field] ?? null;
                                        $isChanged = $oldVal !== $newVal;
                                    @endphp
                                    <tr class="{{ $isChanged ? 'table-warning' : '' }}">
                                        <td>
                                            <strong class="text-uppercase text-secondary fs-7">
                                                {{ str_replace('_', ' ', $field) }}
                                            </strong>
                                            @if($isChanged)
                                                <span class="badge bg-warning text-dark ms-2">MODIFIED</span>
                                            @else
                                                <span class="badge bg-secondary ms-2">UNCHANGED</span>
                                            @endif
                                        </td>
                                        <td class="{{ $isChanged ? 'bg-danger-subtle text-danger font-monospace' : '' }}">
                                            @if($oldVal !== null)
                                                <span class="{{ $isChanged ? 'text-decoration-line-through fw-bold' : '' }}">
                                                    {{ is_array($oldVal) ? json_encode($oldVal) : $oldVal }}
                                                </span>
                                            @else
                                                <em class="text-muted">[NONE / CREATED]</em>
                                            @endif
                                        </td>
                                        <td class="{{ $isChanged ? 'bg-success-subtle text-success font-monospace fw-bold' : '' }}">
                                            @if($newVal !== null)
                                                <span>
                                                    {{ is_array($newVal) ? json_encode($newVal) : $newVal }}
                                                </span>
                                            @else
                                                <em class="text-muted">[DELETED]</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        No property differences recorded for this event.
                    </div>
                @endif
            </div>
        </div>


        <!-- ========================================================= -->
        <!-- Raw Properties -->
        <!-- ========================================================= -->

        <div class="card shadow-sm mt-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    Activity Properties
                </h5>

            </div>


            <div class="card-body">

                <pre
                    class="bg-light border rounded p-3 mb-0"
                    style="white-space: pre-wrap;">{{ json_encode(
                $log->properties,
                JSON_PRETTY_PRINT
            ) }}</pre>

            </div>

        </div>


    </div>

</body>

</html>