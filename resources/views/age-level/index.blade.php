@extends('layouts.master')

@section('title', __('Age & Lesson'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Age & Level / Lesson Program List') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row ResponseMessage">
        <div class="col-lg-6">
            @include('flash')
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">Age & Level List</h6>
                        <div class="font-20 ms-auto">
                            <a href="javascript:;" data-size="md" data-title="Add Age & Level" class="btn btn-primary"
                                data-url="{{ route('age-level.create') }}" data-ajax-popup="true" data-toggle="modal"
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
                                    <th>Title</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($ageLevel) > 0)
                                    @foreach ($ageLevel as $index => $v)
                                        <tr data-id="{{ $v->id }}">
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $v->title ?? '-' }}</td>
                                            <td>
                                                {{ Carbon\Carbon::parse($v->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="d-flex order-actions">
                                                    <a href="javascript:;" data-size="md" data-title="Edit Age & Level" class="ms-2"
                                                        data-url="{{ route('age-level.edit', $v->id) }}" style="color:blue"
                                                        data-ajax-popup="true" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="bx bx-edit"></i>
                                                    </a>

                                                    <a href="javascript:;" style="color:red" class="ms-2" title="Delete User"
                                                        onclick="delete_record('{{ $v->id }}','Age Level')">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="align-center text-center">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">Lesson Program List</h6>
                        <div class="font-20 ms-auto">
                            <a href="javascript:;" data-size="md" data-title="Add Lesson Program" class="btn btn-primary"
                                data-url="{{ route('lesson-program.create') }}" data-ajax-popup="true" data-toggle="modal"
                                data-target="#exampleModal">
                                <i class="bx bx-plus"></i>Add New
                            </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($LessonProgram) > 0)
                                    @foreach ($LessonProgram as $index1 => $lesson_p)
                                        <tr data-select-id="{{ $lesson_p->id }}">
                                            <td>{{ ++$index1 }}</td>
                                            <td>{{ $lesson_p->title ?? '-' }}</td>
                                            <td>
                                                {{ Carbon\Carbon::parse($lesson_p->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="d-flex order-actions">
                                                    <a href="javascript:;" data-size="md" data-title="Edit Age & Level" class="ms-2"
                                                        data-url="{{ route('lesson-program.edit', $lesson_p->id) }}" style="color:blue"
                                                        data-ajax-popup="true" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="bx bx-edit"></i>
                                                    </a>

                                                    <a href="javascript:;" style="color:red" class="ms-2" title="Delete User"
                                                        onclick="delete_lesson('{{ $lesson_p->id }}','Lesson Program')">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="align-center text-center">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('after-scripts')

<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('#example1').DataTable();
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
                    url: "{{ route('age-level.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _method: 'DELETE',
                        tablename: tablename,
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('tr[data-id="' + id + '"]').remove();

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

<script>
    function delete_lesson(id, tablename) {
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
                    url: "{{ route('lesson-program.destroy', ':id') }}".replace(':id', id),
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

