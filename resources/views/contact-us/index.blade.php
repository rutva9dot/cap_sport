@extends('layouts.master')

@section('title', __('Contact Us'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Contact Us List') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row ResponseMessage">
        <div class="col-lg-6">
            @include('flash')
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Location List</h6>
            </div>
            <hr>

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Age & Level</th>
                            <th>Lesson Program</th>
                            <th>Location</th>
                            <th>Message</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($ContactUs) > 0)
                            @foreach ($ContactUs as $index => $cu)
                                @php
                                    $desc_latter_count = mb_strlen($cu->massage);
                                @endphp
                                <tr data-select-id="{{ $cu->id }}">
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $cu->name ?? '-' }}</td>
                                    <td>{{ $cu->email ?? '-' }}</td>
                                    <td>{{ $cu->age_level_title ?? '-' }}</td>
                                    <td>{{ $cu->lesson_program_title }}</td>
                                    <td>{{ $cu->location_name }}</td>
                                    <td>
                                        @if($desc_latter_count >= 55)
                                            <a href="javascript:void(0)"
                                                onclick="openOwnerDesc({{ json_encode($cu->massage ?? '-') }})"
                                                title="Click to view description" class="title">
                                                {!! substr(strip_tags($cu->massage), 0, 20) !!}..
                                            </a>
                                        @else
                                            {!! $cu->massage ?? '-' !!}
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($cu->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="align-center text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')

<script>
    function openOwnerDesc(description) {
        Swal.fire({
            title: "<h5>Content:</h5>",
            html: `<p>${description}</p>`,
            confirmButtonText: "Ok",
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<script>
    function delete_record(id, tablename) {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this " + tablename + "!",
            type: "warning",
            showDenyButton: true,
            confirmButtonText: `YES, DELETE IT!`,
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('location.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _method: 'DELETE',
                        tablename: tablename,
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('tr[data-select-id="' + id + '"]').remove();

                            Swal.fire({
                                title: "Deleted!",
                                text: "Your " + tablename + " has been deleted.",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire("Cancelled", "Something went wrong", "error");
                        }
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelled", "Your " + tablename + " is safe :)", "error");

            }
        })
    }
</script>

@endpush
