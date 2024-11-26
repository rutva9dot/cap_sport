<style>
    img {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 100px;
        height: 100px;
        margin: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 1000px !important;
    }
</style>

<form class="g-3" action="{{ route('banners.update', $banner->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" value="{{ $banner->title }}" name="title" placeholder="Enter Title" required>
            </div>
            <div class="col-md-12 mt-2">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control image" accept="image/jpeg,image/jpg,image/gif,image/png" required>
                <input type="hidden" id="croped_image" name="image">
            </div>
            <div class="col-md-2 mt-2">
                <label for="image" class="form-label">Preview</label>
                <div id="imgdiv">
                    @if ($banner->image != null)
                        <img id="output" src="{{ $banner->image }}" height="50px" width="50px">
                    @else
                        <img id="output" src="{{ asset('assets/images/preview_image.png') }}" height="50px" width="50px">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop your Banner Image</h5>
                <button type="button" class="btn btn-secondary" id="close_model">X</button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image" src="{{ asset('assets/images/preview_image.png') }}">
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<script>
    var $modal = $('#modal');
    var image = document.getElementById('image');
    var cropper;

    $("body").on("change", ".image", function(e) {
        var files = e.target.files;
        var done = function(url) {
            image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 700 / 330,
            viewMode: 1,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });

    $("#crop").click(function() {
        var canvas = cropper.getCroppedCanvas({
            width: 700,
            height: 330,
        });

        canvas.toBlob(function(blob) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(blob);
            $('#imgdiv').append(image);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                $("#croped_image").val(base64data);
            }
        });

        $modal.modal('hide');
    });

    $("#close_model").click(function() {
        $modal.modal('hide');
    });
</script>

