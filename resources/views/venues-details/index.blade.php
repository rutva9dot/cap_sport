@extends('layouts.master')

@section('title', __('Venues Details'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Venues Details List') }}</li>
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
                <h6 class="mb-0">Venues Details List</h6>
                <div class="font-20 ms-auto">
                    <a href="{{ route('venue-details.create')}}" class="btn btn-primary">
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
                            <th>Image</th>
                            <th>Venue Name</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($venue_details) > 0)
                        @foreach ($venue_details as $index => $vd)
                        @php
                        $desc_latter_count = mb_strlen($vd->content);
                        @endphp
                        <tr data-select-id="{{ $vd->id }}">
                            <td>{{ ++$index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="recent-product-img">
                                        @if($vd->image)
                                        <img src="{{ $vd->image }}" alt="" class="imageModal">
                                        @else
                                        <img src="{{ asset('assets/images/preview_image.png') }}" alt=""
                                            class="imageModal">
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $vd->venue_name ?? '-' }}</td>
                            <td>{{ $vd->title ?? '-' }}</td>
                            <td>
                                @if($desc_latter_count >= 25)
                                <a href="javascript:void(0)"
                                    onclick="openOwnerDesc({{ json_encode($vd->content ?? '-') }})"
                                    title="Click to view description" class="title">
                                    {!! substr(strip_tags($vd->content), 0, 20) !!}..
                                </a>
                                @else
                                {!! $vd->content ?? '-' !!}
                                @endif
                            </td>
                            <td>{{ Carbon\Carbon::parse($vd->created_at)->format('d-m-Y h:i A') ?? '-' }}</td>
                            <td>
                                <div class="d-flex order-actions">
                                    <a href="{{ route('venue-details.edit', $vd->id) }}" style="color:blue"
                                        class="ms-2">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <a href="javascript:;" style="color:red" class="ms-2" title="Delete User"
                                        onclick="delete_record('{{ $vd->id }}','Venues Details')">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="align-center text-center">No matching records found</td>
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
                    url: "{{ route('venue-details.destroy', ':id') }}".replace(':id', id),
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

<script>
    $(function() {
        var modal = document.getElementById("myModal");
        var closeButton = modal.querySelector(".close");

        function closeModal() {
            modal.style.display = "none";
        }
        closeButton.addEventListener("click", closeModal);
        $(".imageModal").click(function() {
            var imgUrl = $(this).attr("src");
            $("#modalImage").attr("src", imgUrl);
            $("#myModal").css("display", "block");
        });
    });
</script>

@endpush
