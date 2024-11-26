<form class="g-3" action="{{ route('programs.update', $Programs->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="title" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $Programs->name }}" name="name" placeholder="Enter Programs Name" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>
