<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Activity Details</title>

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
                Activity Details
            </h2>

            <p class="text-muted mb-0">
                Detailed information about this activity.
            </p>

        </div>


        <a
            href="{{ route('activity.logs') }}"
            class="btn btn-dark">

            Back to Logs

        </a>

    </div>


    <!-- Basic Activity Information -->

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Activity Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <!-- ID -->

                <div class="col-md-4">

                    <strong>Activity ID</strong>

                    <p class="mt-1">
                        {{ $log->id }}
                    </p>

                </div>


                <!-- Description -->

                <div class="col-md-4">

                    <strong>Description</strong>

                    <p class="mt-1">
                        {{ $log->description }}
                    </p>

                </div>


                <!-- Event -->

                <div class="col-md-4">

                    <strong>Event</strong>

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


                <!-- Subject Type -->

                <div class="col-md-4">

                    <strong>Subject Type</strong>

                    <p class="mt-1">

                        {{ $log->subject_type ?? 'N/A' }}

                    </p>

                </div>


                <!-- Subject ID -->

                <div class="col-md-4">

                    <strong>Subject ID</strong>

                    <p class="mt-1">

                        {{ $log->subject_id ?? 'N/A' }}

                    </p>

                </div>


                <!-- Date -->

                <div class="col-md-4">

                    <strong>Activity Date</strong>

                    <p class="mt-1">

                        {{ $log->created_at->format('d M Y, h:i A') }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- Change Information -->

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

                                    <th>Field</th>

                                    <th>Old Value</th>

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
                                        {{ is_array($value) ? json_encode($value) : ($value ?? 'NULL') }}
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

                                    <th>Field</th>

                                    <th>New Value</th>

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
                                        {{ is_array($value) ? json_encode($value) : ($value ?? 'NULL') }}
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


    <!-- Raw Properties -->

    <div class="card shadow-sm mt-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Activity Properties
            </h5>

        </div>


        <div class="card-body">

            <pre class="bg-light border rounded p-3 mb-0"
                 style="white-space: pre-wrap;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>

        </div>

    </div>


</div>

</body>

</html>