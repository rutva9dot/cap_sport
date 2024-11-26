@extends('layouts.master')

@section('title')
{{ __('Gallery') }}
@endsection

@section('content')

<style>
    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }

    .image-wrapper {
        position: relative;
        max-width: 200px;
        max-height: 200px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        padding: 10px;
    }

    .image-wrapper:hover .gallery-image {
        transform: scale(1.1);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .image-wrapper:hover .image-overlay {
        opacity: 1;
    }

    .delete-button {
        background: #ff5555;
        border: none;
        padding: 10px 15px;
        color: white;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    .delete-button:hover {
        background: #ff0000;
    }
</style>


<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('galleries.index') }}">Gallery List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Gallery') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row ResponseMessage">
        <div class="col-lg-6">
            @include('flash')
        </div>
    </div>

    <section class="section">
        <div class="image-gallery">
            @php
            $images = explode(',', $gallery->images);
            @endphp
            @foreach ($images as $image)
            <div class="image-wrapper">
                <img src="{{ asset('gallery_image/'. $image) }}" alt="Gallery Image" class="gallery-image imageModal">
                <div class="image-overlay">
                    <button class="delete-button" data-image="{{ $image }}"> <i class="bx bx-trash"></i> </button>
                </div>
            </div>

            <input type="hidden" value="{{ $gallery->id }}" name="gallery_id" id="gallery_id">
            @endforeach
        </div>
        <section>
</div>


@endsection

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const imageName = this.getAttribute('data-image');
                const GalleryId = $('#gallery_id').val();

                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    console.error('CSRF token meta tag not found!');
                    return;
                }
                const csrfToken = csrfTokenMeta.getAttribute('content');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform the delete request
                        fetch(`/gallery/delete-image`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ image: imageName, gallery_id:GalleryId }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your image has been deleted.',
                                    'success'
                                );

                                // Remove the image wrapper from the DOM
                                this.closest('.image-wrapper').remove();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete the image. Please try again later.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'Something went wrong. Please try again.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
</script>

@endpush
