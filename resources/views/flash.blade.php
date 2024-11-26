@if ($message = Session::get('success'))
    <div id="alert-message" class="alert alert-success border-0  alert-dismissible fade show" style="background-color: #2dce89 !important">
        <div class="text-white">{{ $message ?? '-' }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div id="alert-message" class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
        <div class="text-white">{{ $message }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div id="alert-message" class="alert alert-warning border-0 bg-warning alert-dismissible fade show">
        <div class="text-dark">{{ $message }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($message = Session::get('info'))
    <div id="alert-message" class="alert alert-info border-0 bg-info alert-dismissible fade show">
        <div class="text-dark">{{ $message }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div id="alert-message" class="alert alert-secondary border-0 bg-secondary alert-dismissible fade show">
        <div class="text-white">{{ $message }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var alertMessage = document.getElementById('alert-message');

        if (alertMessage) {
            var alertShownTime = localStorage.getItem('alertShownTime');
            var currentTime = new Date().getTime();

            if (!alertShownTime || currentTime - alertShownTime > 3000) {
                setTimeout(function () {
                    alertMessage.classList.remove('show');
                    alertMessage.classList.add('fade');
                    setTimeout(function () {
                        alertMessage.remove();
                    }, 150); // Bootstrap's default fade transition duration
                }, 3000);

                localStorage.setItem('alertShownTime', currentTime);
            } else {
                alertMessage.remove();
            }
        }
    });
</script>

