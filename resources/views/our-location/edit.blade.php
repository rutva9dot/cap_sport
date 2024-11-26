<form class="g-3" action="{{ route('location.update', $ourLocation->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="title" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $ourLocation->name }}" name="name" placeholder="Enter Location Name" required>
            </div>

            <div class="col-md-12">
                <label for="title" class="form-label">Address</label>
                <textarea name="address" class="form-control" id="address" rows="5" placeholder="Enter Location Address" required>{{ $ourLocation->address }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>
