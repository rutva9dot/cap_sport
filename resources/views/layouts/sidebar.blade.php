<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/images/logo-img.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Cap sport</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>

    <ul class="metismenu" id="menu">
        {{-- <li class="mm-active-class {{ request()->is('dashboard') ? 'mm-active' : '' }}">
            <a href="{{ route('dashboard') }}" class="">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li> --}}

        <li class="mm-active-class {{ request()->is('banners*') ? 'mm-active' : '' }}">
            <a href="{{ route('banners.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-image-add'></i></div>
                <div class="menu-title">Banners</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('about-us*') ? 'mm-active' : '' }}">
            <a href="{{ route('about-us.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-info-circle'></i></div>
                <div class="menu-title">About Us</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('blogs*') ? 'mm-active' : '' }}">
            <a href="{{ route('blogs.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-news'></i></div>
                <div class="menu-title">Blog</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('galleries*') ? 'mm-active' : '' }}">
            <a href="{{ route('galleries.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-photo-album'></i></div>
                <div class="menu-title">Gallery</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('venues*') ? 'mm-active' : '' }}">
            <a href="{{ route('venues.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-building-house'></i></div>
                <div class="menu-title">Venues</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('venue-details*') ? 'mm-active' : '' }}">
            <a href="{{ route('venue-details.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-info-circle'></i></div>
                <div class="menu-title">Venues details</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('coach-plan*') ? 'mm-active' : '' }}">
            <a href="{{ route('coach-plan.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                <div class="menu-title">Plan</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('coaches*') ? 'mm-active' : '' }}">
            <a href="{{ route('coaches.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">Coaches</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('programs*') ? 'mm-active' : '' }}">
            <a href="{{ route('programs.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-book-content'></i></div>
                <div class="menu-title">Programs</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('program-details*') ? 'mm-active' : '' }}">
            <a href="{{ route('program-details.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-detail'></i></div>
                <div class="menu-title">Program Detail</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('faqs*') ? 'mm-active' : '' }}">
            <a href="{{ route('faqs.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-help-circle'></i></div>
                <div class="menu-title">FAQs</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('location*') ? 'mm-active' : '' }}">
            <a href="{{ route('location.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-current-location'></i></div>
                <div class="menu-title">Location</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('age-level*') ? 'mm-active' : '' }}">
            <a href="{{ route('age-level.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-book'></i></div>
                <div class="menu-title">Age & Lesson</div>
            </a>
        </li>

        <li class="mm-active-class {{ request()->is('contact-us*') ? 'mm-active' : '' }}">
            <a href="{{ route('contact-us.index') }}" class="">
                <div class="parent-icon"><i class='bx bx-envelope'></i></div><div class="menu-title">Contact Us</div>
            </a>
        </li>

        {{-- <li class="{{ request()->is('change-password') ? 'mm-active' : '' }}">
            <a href="{{ route('change-password') }}" class="">
                <div class="parent-icon"><i class='bx bx-lock-alt'></i></div>
                <div class="menu-title">Change Password</div>
            </a>
        </li>

        <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); confirmLogout(event);" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="#fd5353" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span class="menu-title">Logout<span>
            </a>
            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li> --}}
    </ul>
</div>

{{-- @push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmLogout(event) {
            event.preventDefault(); // Prevent the default action
            Swal.fire({
                title: 'Are you sure you want to logout?',
                text: 'You will need to log in again to continue.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
@endpush --}}
