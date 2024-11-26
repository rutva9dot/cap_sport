@extends('layouts.master')

@section('title', __('Coach'))

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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('coaches.index')}}">Coach List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Coach</li>
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
                <form action="{{ route('coaches.store') }}" method="post" id="AddCoaches" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="form-label" for="name">Name</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" />
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-5">
                            <h6 class="form-label" for="image">Image</h6>
                            <div class="input-group">
                                <input type="file" name="image" class="form-control"
                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                    accept="image/jpeg , image/jpg, image/gif, image/png" />
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-1">
                            <h6 for="image" class="form-label">Preview</h6>
                            <div>
                                <img id="blah" src="{{ asset('assets/images/preview_image.png') }}" height="50px"
                                    width="50px">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <h6 class="form-label" for="designation">Designation</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="designation" name="designation"
                                    placeholder="Enter Designation" />
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="form-label" for="country">Country</h6>
                            <div class="input-group">
                                <select name="country_id" id="single-select" class="form-control country_id" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="form-label" for="certification">Certification</h6>
                            <div class="input-group">
                                <textarea name="certification" class="form-control" id="certification" rows="3" placeholder="Enter Certification"></textarea>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group mb-3 mt-3 col-md-12">
                            <h6 class="mb-0">Profile Summary</h6>
                            <textarea name="about" id="summernote" class="form-control" placeholder="Enter Profile Summary"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('coaches.index') }}" class="btn btn-secondary px-4">Cancel</a>
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

        $('#AddCoaches').validate({
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
                name:{
                    required: true,
                },
                designation: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                image: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter Name.",
                },
                designation: {
                    required: "Please Enter Designation.",
                },
                country_id: {
                    required: "Please select Country.",
                },
                image: {
                    required: "Please Choose Image.",
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
