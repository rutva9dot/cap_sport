@extends('layouts.master')

@section('title', __('Plan'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Plan List') }}</li>
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
                <h6 class="mb-0">Plan List</h6>
                <div class="font-20 ms-auto">
                    <a href="{{ route('coach-plan.create')}}" class="btn btn-primary">
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
                            <th>Amout</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($coach_plan) > 0)
                            @foreach ($coach_plan as $index => $cp)
                                @php
                                    $desc_latter_count = mb_strlen($cp->descriprion);
                                @endphp
                                <tr data-select-id="{{ $cp->id }}">
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $cp->title ?? '-' }}</td>
                                    <td>{{ $cp->amount ?? '-' }}</td>
                                    <td>
                                        @if($desc_latter_count >= 25)
                                            <a href="javascript:void(0)"
                                                onclick="openOwnerDesc({{ json_encode($cp->description ?? '-') }})"
                                                title="Click to view description" class="title">
                                                {!! substr(strip_tags($cp->description), 0, 20) !!}..
                                            </a>
                                        @else
                                            {!! $cp->description ?? '-' !!}
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($cp->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="{{ route('coach-plan.edit', $cp->id) }}" style="color:blue" class="ms-2">
                                                <i class="bx bx-edit"></i>
                                            </a>

                                            <a href="javascript:;" style="color:red" class="ms-2" title="Delete User"
                                                onclick="delete_record('{{ $cp->id }}','Plan')">
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
		$(document).ready(function() {
			$('#example').DataTable();
		});
	</script>

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
        function delete_record(id, tablename) {
            console.log(id, tablename);
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
                        type: 'DELETE',
                        url: "{{ route('coach-plan.destroy', ':id') }}".replace(':id', id),
                        data: {
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
