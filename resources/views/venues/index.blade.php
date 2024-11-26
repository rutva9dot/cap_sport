@extends('layouts.master')

@section('title', __('Venue'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Venue List') }}</li>
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
                <h6 class="mb-0">Venue List</h6>
                <div class="font-20 ms-auto">
                    <a href="javascript:;" data-size="md" data-title="Add Venue" class="btn btn-primary"
                        data-url="{{ route('venues.create') }}" data-ajax-popup="true" data-toggle="modal"
                        data-target="#exampleModal">
                        <i class="bx bx-plus"></i>Add New
                    </a>
                </div>
            </div>
            <hr>

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Slug</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($venues) > 0)
                            @foreach ($venues as $index => $v)
                                @php
                                    $desc_latter_count = mb_strlen($v->address);
                                @endphp
                                <tr data-select-id="{{ $v->id }}">
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $v->name ?? '-' }}</td>
                                    <td>
                                        @if($desc_latter_count >= 25)
                                            <a href="javascript:void(0)"
                                                onclick="openOwnerDesc({{ json_encode($v->address ?? '-') }})"
                                                title="Click to view description" class="title">
                                                {!! substr(strip_tags($v->address), 0, 25) !!}..
                                            </a>
                                        @else
                                            {!! $v->address ?? '-' !!}
                                        @endif
                                    </td>
                                    <td>{{ $v->slug ?? '-' }}</td>
                                    <td>
                                        {{ Carbon\Carbon::parse($v->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="javascript:;" data-size="md" data-title="Edit Venue" class="ms-2"
                                                data-url="{{ route('venues.edit', $v->id) }}" style="color:blue"
                                                data-ajax-popup="true" data-toggle="modal" data-target="#exampleModal">
                                                <i class="bx bx-edit"></i>
                                            </a>

                                            <a href="javascript:;" style="color:red" class="ms-2" title="Delete User"
                                                onclick="delete_record('{{ $v->id }}','Venues')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="align-center text-center">No matching records found</td>
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
            title: "<h5>Address:</h5>",
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
                    url: "{{ route('venues.destroy', ':id') }}".replace(':id', id),
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
