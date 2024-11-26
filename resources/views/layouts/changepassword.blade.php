@extends('layouts.master')
@section('title', __('Change Password'))

@section('content')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
        <div class="card border shadow-sm col-md-6">
            <div class="card-body">
                <form action="{{ route('updatepassword') }}" method="post" id="updatepassword" name="updatepassword">
                    @csrf
                    <div class="col-lg-12 mb-3">
                        <label for="current_password" class="text-label form-label">Current Password</label>
                        <input type="text" class="form-control" placeholder="Enter Current Password"
                            name="current_password" id="current_password" aria-label="current_password"
                            aria-describedby="basic-addon1">
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="new_password" class="text-label form-label">New Password</label>
                        <input type="text" name="new_password" id="new_password" class="form-control"
                            placeholder="Enter New Password">
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="confirm_password" class="text-label form-label">Confirm New Password</label>
                        <input type="text" class="form-control" placeholder="Enter Confirm New Password"
                            name="confirm_password" aria-label="confirm_password" id="confirm_password"
                            aria-describedby="basic-addon1">
                    </div>
                    <input type="hidden" value="{{ Session::get('login_id') }}" name="login_id">
                    <button type="submit" class="btn btn-primary pay_button" id="updatePasswordBtn">
                        Update Password
                    </button>

                    <a type="button" class="btn btn-danger" id="resetButton">Cancel</a>
                </form>
            </div>
        </div>
    </section>
</div>


@endsection

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        // Reset button click event
        $('#resetButton').on('click', function() {
            // Reset textarea value to empty string
            $('#current_password').val('');
            $('#new_password').val('');
            $('#confirm_password').val('');

            $('.text-danger').remove();
            $('.is-invalid').removeClass('is-invalid');
        });
    });
</script>

<script>
    $(document).ready(function() {
        var userId = "{{ Session::get('login_id') }}";

        $.validator.addMethod("notEqualTo", function(value, element, param) {
            return value !== $(param).val();
        }, "New password must be different from the current password.");

        $.validator.addMethod("specialCharacter", function(value, element) {
            return /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value);
        }, function() {
            return true; // Return true to prevent the message from being shown
        });

        $.validator.addMethod("noSpace", function(value, element) {
            return value.indexOf(" ") === -1;
        }, "Space not allowed");

        $.validator.addMethod("numericCharacter", function(value, element) {
            return /\d/.test(value);
        }, function() {
            return true; // Return true to prevent the message from being shown
        });

        $.validator.addMethod("alphabeticCharacter", function(value, element) {
            return /[a-zA-Z]/.test(value);
        }, function() {
            return true; // Return true to prevent the message from being shown
        });

        $.validator.addMethod("capitalLetter", function(value, element) {
            return /[A-Z]/.test(value);
        }, function() {
            return true; // Return true to prevent the message from being shown
        });

        $.validator.addMethod("checkCurrentPassword", function(value, element) {
            var isValid = false;

            $.ajax({
                type: 'POST',
                url: "{{ route('verifyCurrentPassword') }}",
                data: {
                    current_password: value,
                    id: userId,
                    _token: '{{ csrf_token() }}'
                },
                async: false,
                success: function(response) {
                    isValid = response.valid;
                }
            });
            return isValid;
        }, "Current password is incorrect.");

        $('#updatepassword').validate({
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
                current_password:{
                    noSpace: true,
                    required: true,
                    checkCurrentPassword: true
                },
                new_password: {
                    required: true,
                    minlength: 6,
                    notEqualTo: "#current_password",
                    specialCharacter: true,
                    numericCharacter: true,
                    alphabeticCharacter: true,
                    capitalLetter: true,
                    noSpace: true
                },
                confirm_password: {
                    required: true,
                    noSpace: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                current_password: {
                    required: "Please enter a current password.",
                    noSpace: "Space not allow.",
                    checkCurrentPassword: "Current password is incorrect."
                },
                new_password: {
                    required: "Please enter a new password.",
                    specialCharacter: "Please include at least one special character.",
                    numericCharacter: "Please include at least one numeric character.",
                    alphabeticCharacter: "Please include at least one alphabetic character.",
                    capitalLetter: "Please include at least one capital letter.",
                    noSpace: "Space not allow."
                },
                confirm_password: {
                    required: "Please enter confirm password.",
                    equalTo: "Passwords do not match.",
                    noSpace: "Space not allow."
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
