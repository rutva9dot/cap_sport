@extends('layouts.master')
@section('title', __('Edit Plan'))
@section('content')

<style>
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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('coach-plan.index')}}">Plan List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Plan</li>
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
                <form action="{{ route('coach-plan.update', $coachPlan->id) }}" id="EditPlan" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="form-label" for="title">Title</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="title" name="title" value="{{ $coachPlan->title }}"
                                    placeholder="Enter Title" required/>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="form-label" for="title">Amount</h6>
                            <div class="input-group">
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $coachPlan->amount }}"
                                    placeholder="Enter Amount" required/>
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group mb-3 mt-3 col-md-12">
                            <h6 class="mb-0">Description</h6>
                            <textarea name="description" class="form-control" placeholder="Enter Description" rows="3" required>{{ $coachPlan->description }}
                            </textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('coach-plan.index') }}" class="btn btn-secondary px-4">Cancel</a>
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
        $('#EditPlan').validate({
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
                amount: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: "Please Enter Title.",
                },
                amount: {
                    required: "Please Enter Amount",
                },
                description: {
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

