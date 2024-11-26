@extends('layouts.master')

@section('title', __('Edit Gallery'))

@section('content')

<style>
    .image-preview {
        display: inline-block;
        position: relative;
        margin-right: 20px;
    }

    .image-preview img {
        display: block;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #ff5e5e;
        border: none;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('galleries.index')}}">Gallery
                            List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Gallery</li>
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
                <form action="{{ route('galleries.update', $gallery->id) }}" method="post" id="EditGallery" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <h6 class="form-label" for="title">Select Venue</h6>
                            <div class="input-group">
                                <select name="venue_id" id="single-select" class="form-control venue_id" required>
                                    <option value="">Select Venue</option>
                                        @foreach ($venues as $venue)
                                            <option value="{{ $venue->id }}" {{ $gallery->venue_id == $venue->id ? 'selected' :
                                                '' }}>
                                                {{ $venue->name }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <h6 class="form-label" for="images">Gallery Image</h6>
                            <div class="input-group">
                                <input type="file" name="images[]" class="form-control" id="imageInput"
                                    onchange="previewImages()" accept="image/jpeg, image/jpg, image/gif, image/png"
                                    multiple />
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <h6 class="form-label">Stored Images</h6>
                            <div id="storedImagesContainer" class="image-preview-container">
                                @foreach (explode(',', $gallery->images) as $image)
                                <div class="image-preview" data-filename="{{ $image }}">
                                    <img src="{{ asset('gallery_image/'. $image) }}" alt="Image" class="img-thumbnail"
                                        width="150">
                                    <button type="button" class="remove-image btn btn-danger btn-sm">×</button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <div id="imagePreviewContainer" class="image-preview-container"></div>
                        </div>
                    </div>

                    <input type="hidden" name="removed_images" id="removedImages">

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('galleries.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    function previewImages() {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = ''; // Clear any existing previews
        const files = document.getElementById('imageInput').files;

        Array.from(files).forEach((file, index) => {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'image-preview';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.height = 85;
            img.width = 150;
            img.style.marginRight = '20px';

            const cancelButton = document.createElement('button');
            cancelButton.innerHTML = '×';
            cancelButton.className = 'remove-image btn btn-danger btn-sm';
            cancelButton.onclick = function() {
                removeImage(index);
            };

            previewDiv.appendChild(img);
            previewDiv.appendChild(cancelButton);
            previewContainer.appendChild(previewDiv);
        });
    }

    function removeImage(index) {
        const imageInput = document.getElementById('imageInput');
        const dataTransfer = new DataTransfer();

        const files = Array.from(imageInput.files);

        files.splice(index, 1);

        files.forEach(file => {
            dataTransfer.items.add(file);
        });

        imageInput.files = dataTransfer.files;

        previewImages(); // Update the preview
    }

    document.querySelectorAll('.remove-image').forEach(button => {
        button.addEventListener('click', function () {
            const imagePreview = this.closest('.image-preview');
            const filename = imagePreview.getAttribute('data-filename');
            if (filename) {
                const removedImagesInput = document.getElementById('removedImages');
                let removedImages = removedImagesInput.value ? removedImagesInput.value.split(',') : [];
                removedImages.push(filename);
                removedImagesInput.value = removedImages.join(',');
                imagePreview.remove();
            } else {
                const index = Array.from(imagePreview.parentNode.children).indexOf(imagePreview);
                removeImage(index);
            }
        });
    });

    $(document).ready(function() {
        $('#EditGallery').validate({
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
                venue_id:{
                    required: true,
                }
            },
            messages: {
                venue_id: {
                    required: "Please Select Venue.",
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
