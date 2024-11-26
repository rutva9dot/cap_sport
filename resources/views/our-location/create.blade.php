<form class="g-3" action="{{ route('location.store') }}" method="post" id="AddLocation" name="AddLocation" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Venue Name" required>
            </div>
            <div class="col-md-12">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" placeholder="Enter Address" id="address" rows="5" required></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        console.log('das');
        $('#AddLocation').validate({
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
                address: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter Name.",
                },
                address: {
                    required: "Please enter Address.",
                },
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

