@extends('layouts.master')
@section('title', __('Edit Program Details'))
@section('content')

<style>
    .card-header {
        display: block !important;
    }

    .close {
        display: none !important;
    }

    .note-editor .note-editing-area ul li {
        list-style-type: disc !important;
        list-style-position: inside;
        margin-left: 20px;
    }

    .note-editor .note-editing-area ol li {
        list-style-type: decimal !important;
        list-style-position: inside;
        margin-left: 20px;
    }

    .note-editor .note-editing-area ul li,
    .note-editor .note-editing-area ol li {
        margin-bottom: 5px;
    }

    .form-control.is-invalid {
        width: 100%;
    }

    .input-group-text.is-invalid {
        border-color: #dc3545;
    }
</style>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('program-details.index')}}">Program Details</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Program Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row ResponseMessage">
        <div class="col-lg-12">
            @include('flash')
        </div>
    </div>

    <section class="section">
        <div class="card border shadow-sm">
            <div class="card-body">
                <form action="{{ route('program-details.update', $programDetails->id) }}" method="post" id="EditVenueDetails" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="form-label" for="title">Title</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="title" name="title" value="{{ $programDetails->title }}"
                                    placeholder="Enter Title" required/>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="form-label" for="title">Select Venue</h6>
                            <div class="input-group">
                                <select name="venue_id" id="single-select" class="form-control venue_id" required>
                                    <option value="">Select Venue</option>
                                    @foreach ($Programs as $Program)
                                        <option value="{{ $Program->id }}" {{ $programDetails->program_id == $Program->id ? 'selected' : '' }}>
                                            {{ $Program->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <h6 class="form-label" for="image">Image</h6>
                            <div class="input-group">
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])"
                                    accept="image/jpeg , image/jpg, image/gif, image/png" />
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-1 mt-3">
                            <h6 for="image" class="form-label">Preview</h6>
                            <div>
                                @if ($programDetails->image != null)
                                    <img id="blah1" src="{{  $programDetails->image }}" height="50px" width="50px">
                                @else
                                    <img id="blah1" src="{{ asset('assets/images/preview_image.png') }}" height="50px" width="50px">
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3 mt-3 col-md-12">
                            <h6 class="form-label" for="title">Content</h6>
                            <textarea name="content" class="form-control" placeholder="Enter Content" id="summernote" required>{!! $programDetails->content !!}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('program-details.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Enter Description',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                // ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                // ['help', ['help']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    // Apply custom styles directly
                    $('.note-editable ul').each(function() {
                        $(this).css({
                            'list-style-type': 'disc',
                            'list-style-position': 'inside',
                            'margin-left': '20px'
                        });
                    });
                    $('.note-editable ol').each(function() {
                        $(this).css({
                            'list-style-type': 'decimal',
                            'list-style-position': 'inside',
                            'margin-left': '20px'
                        });
                    });
                    $('.note-editable li').css({
                        'margin-bottom': '5px'
                    });
                }
            }
        });

        $('#EditVenueDetails').validate({
            errorElement: 'div',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                // error.insertAfter(element);
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            rules: {
                title:{
                    required: true,
                },
                program_id: {
                    required: true,
                },
                content: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: "Please enter Title.",
                },
                program_id: {
                    required: "Please select Program",
                },
                content: {
                    required: "Please Enter Description.",
                }
            },
            success: function(label, element) {
                // Remove the error message for the element
                $(element).removeClass('is-invalid');
                $(label).remove();
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

@endpush
