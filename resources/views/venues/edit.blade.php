<form class="g-3" action="{{ route('venues.update', $venues->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="title" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $venues->name }}" name="name" placeholder="Enter Venue Name" required>
            </div>

            <div class="col-md-12">
                <label for="title" class="form-label mt-1">Address</label>
                <textarea name="address" class="form-control" id="address" rows="3" placeholder="Enter Address">{{ $venues->address }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>
