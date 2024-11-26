<style>
    .form-control.is-invalid {
        width: 100%;
    }

    .input-group-text.is-invalid {
        border-color: #dc3545;
    }
</style>

<form class="g-3" action="{{ route('venues.store') }}" method="post" id="AddVenue" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="title" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Venue Name" required>
            </div>

            <div class="col-md-12">
                <label for="title" class="form-label mt-1">Address</label>
                <textarea name="address" class="form-control" id="address" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>

{{-- @push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        $('#AddVenue').validate({
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
                }
            },
            messages: {
                name: {
                    required: "Please enter Name.",
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
@endpush --}}
