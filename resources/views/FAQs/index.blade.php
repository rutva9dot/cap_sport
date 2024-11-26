@extends('layouts.master')

@section('title', __('FAQs'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('FAQs List') }}</li>
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
                <h6 class="mb-0">FAQs List</h6>
                <div class="font-20 ms-auto">
                    <a href="{{ route('faqs.create')}}" class="btn btn-primary">
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
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($faqs) > 0)
                            @foreach ($faqs as $index => $faq)
                                @php
                                    $desc_latter_count = mb_strlen($faq->answer);
                                @endphp
                                <tr data-select-id="{{ $faq->id }}">
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $faq->question ?? '-' }}</td>
                                    <td>
                                        @if($desc_latter_count >= 50)
                                            <a href="javascript:void(0)"
                                                onclick="openOwnerDesc({{ json_encode($faq->answer ?? '-') }})"
                                                title="Click to view Answer" class="title">
                                                {!! substr(strip_tags($faq->answer), 0, 50) !!}..
                                            </a>
                                        @else
                                            {!! $faq->answer ?? '-' !!}
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($faq->created_at)->format('d-m-Y h:i A') ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="{{ route('faqs.edit', $faq->id) }}" style="color:blue" class="ms-2">
                                                <i class="bx bx-edit"></i>
                                            </a>

                                            <a href="javascript:;" style="color:red" class="ms-2" title="Delete Faqs"
                                                onclick="delete_record('{{ $faq->id }}','FAQ')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="align-center text-center">No matching records found</td>
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
                    url: "{{ route('faqs.destroy', ':id') }}".replace(':id', id),
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
