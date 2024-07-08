<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg" style="background-color:#0A407F">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <img src="{{ asset('assets/images/abu muda 1.png') }}" alt="" class="sidebar-resize-hide my-auto">
                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.main')}}" class="nav-link">
                        <i class="ph-house"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.siswa.main')}}" class="nav-link">
                        <i class="ph-article"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.detailtryout.main')}}" class="nav-link">
                        <i class="ph-swatches"></i>
                        <span>Tryout</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.managetryout.main')}}" class="nav-link">
                        <i class="ph-article"></i>
                        <span>Manage Tryout</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>