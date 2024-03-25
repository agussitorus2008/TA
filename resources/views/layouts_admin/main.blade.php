<!DOCTYPE html>
<html lang="en" dir="ltr">
    @include('layouts_admin.head')
<body>
	<!-- Main navbar -->
	<div class="navbar navbar-dark navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10" style="background-color:#E0F1FA">
        <div class="container-fluid">
            <div class="d-flex d-lg-none me-2">
                <button type="button" class="btn btn-flat-dark navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                    <i class="ph-list"></i>
                </button>
            </div>
    
            <div class="navbar-brand flex-1 flex-lg-0">
                <div class="d-inline-flex align-items-center">
                    @yield('title')
                </div>
            </div>
    
    
            <ul class="nav flex-row justify-content-end order-1 order-lg-2">
    
                <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                    <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
                        <div class="status-indicator-container">
                            <img src="{{asset('assets/images/user_847969.png')}}" class="w-32px h-32px rounded-pill" alt="">
                            <span class="status-indicator bg-success"></span>
                        </div>
                        <span class="d-none d-lg-inline-block mx-lg-2 text-dark" style="font-weight: bold;">{{ Auth::User()->nama }}</span>
                    </a>
    
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{route('auth.dologout')}}" class="dropdown-item">
                            <i class="ph-sign-out me-2"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		@include('layouts_admin.sidebar')
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">
				<!-- Page header -->
				@yield('content')
				<!-- /content area -->
			</div>
            <!-- Footer -->
				<div class="navbar navbar-sm navbar-footer border-top">
                    <div class="container-fluid">
                        @include('layouts_admin.footer')
                    </div>
                </div>
			<!-- /footer -->
		</div>
		<!-- /main content -->

	</div>
	

</body>
</html>
