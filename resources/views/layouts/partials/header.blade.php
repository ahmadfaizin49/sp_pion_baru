<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href=""><img class="img-fluid"
                        src="{{ asset('assets/images/logo/logo.png') }}" alt=""></a></div>
            <div class="dark-logo-wrapper"><a href=""><img class="img-fluid"
                        src="{{ asset('assets/images/logo/dark-logo.png') }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle">
                </i></div>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <li class="onhover-dropdown p-0">
                    <button type="button" class="btn-premium btn-premium-lg btn-premium-primary btn-premium-white border-0" data-bs-toggle="modal"
                        data-bs-target="#logoutModal">
                        <i class="fa fa-sign-out me-1"></i> Logout
                    </button>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
@include('layouts.partials.modal')



