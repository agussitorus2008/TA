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
                    <a href="{{route('siswa.main')}}" class="nav-link">
                        <i class="ph-house"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('siswa.profile.main')}}" class="nav-link">
                        <i class="ph-article"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-layout"></i>
                        <span>Tryout</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('siswa.tryoutSaya.main')}}" class="nav-link">Tryout saya</a></li>
                        <li class="nav-item"><a href="{{route('siswa.hasilTryout.main')}}" class="nav-link">Hasil tryout</a></li>
                        <li class="nav-item"><a href="{{route('siswa.hasilTryout.rekomendasi')}}" class="nav-link">Rekomendasi Program Studi dan PTN</a></li>
                    </ul>
                </li>   
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-layout"></i>
                        <span>Simulasi</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('siswa.simulasi.main')}}" class="nav-link">PTN & Program Studi</a></li>
                        <li class="nav-item"><a href="{{route('siswa.simulasi.ptn')}}" class="nav-link">PTN</a></li>
                        <li class="nav-item"><a href="{{route('siswa.simulasi.prodi')}}" class="nav-link">Program Studi</a></li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="ph-globe"></i>
                        <span>Lalu Lintas</span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>