<!doctype html>
<html lang="zxx">
    <head>
    @include('layouts.index.head')
    <style>
        .navbar-area {
        background-image: linear-gradient(to right, #0055A4, #7CA7D0);
    }

    .banner-style-three, .banner-style-four {
        background-image: linear-gradient(to bottom right, #0055A4, #FFFFFF);
        /* Tambahan gaya lainnya untuk banner tetap di sini */
    }
    </style>
    </head>
    <body>  
            <div class="navbar-area">
                @include('layouts.index.navbar')
            </div>
            <div class="banner-style-three banner-style-four">
                <div class="d-table">
                    <div class="d-table-cell">
                        <div class="container">
                            <div class="banner-text">
                                <h2 style="color:#F9F9F9">Menuju PTN Impian</h2>
                                <h2 style="color:#F9F9F9">Bersama <a style="color:#0A407F">KAWAL PTN-KU!</a></h2>
                                <p style="color:#F9F9F9">Uji kemampuanmu menghadapi UTBK dan bersaing dengan ratusan ribu siswa lainnya dengan mengikuti Tryout disini:</p>
                                <p style="color:#F9F9F9">Persiapan yang matang adalah modal utama kelulusan Anda!</p>
        
                                <div class="theme-btn">
                                    <a href="{{route('auth.register')}}" class="btn" style="background-color: #030994;color:#F9F9F9">DAFTAR</a>
                                    <a href="{{route('auth.login')}}" class="btn" style="background-color: #F9F9F9;color:#241581">MASUK</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="banner-img">
                    <img src="{{asset('assets/images/image 1.png')}}" alt="banner image">
                </div>
            </div>
        <!-- Banner Section End -->


        <!-- Job Category Section Start -->
        <div class="category-style-two pb-70 mt-5"> 
            <div class="container">
                <div class="section-title text-center">
                    <h4>MENGAPA HARUS BERLATIH BERSAMA</h4>
                    <h1 style="color: #0A407F">KAWALPTN-KU?</h1>
                </div>

                <div class="row justify-content-center text-center">
                    <div class="col-xl-6">
                        <div class="card-body">
                            <div class="mx-auto mb-3">
                                <div class="text-center">
                                    <h6>Live Rangking Berdasarkan Pilihan Prodi Anda</h6>
                                </div>
                                <img src="{{ asset('assets/images/uil_graph-bar.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6"> 
                        <div class="card-body">
                            <div class=" mx-auto mb-3">
                                <div class="text-center">
                                    <h6>Grafik Perkembangan Nilai Tryout</h6>
                                </div>
                                <img src="{{ asset('assets/images/Stuck at Home Monitor.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
        <!-- Counter Section Start -->
        <div class="counter-section" style="background-color: #0A407F">
            <div class="container">
                <div class="row counter-area">
                    <div class="col-lg-3 col-6">
                        <div class="counter-text">
                            <img src="{{ asset('assets/images/Group.png') }}" alt="" class="img-fluid mt-3" width="90px">
                            <h2><span>14.717</span></h2>
                            <p>Pendaftar</p>
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-6">
                        <div class="counter-text">
                            <img src="{{ asset('assets/images/Vector (2).png') }}" alt="" class="img-fluid mt-3" width="90px">
                            <h2><span>1.835</span></h2>
                            <p>Sekolah</p>
                        </div>
                    </div>
            
                    <div class="col-lg-3 col-6">
                        <div class="counter-text">
                            <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid mt-3" width="90px">
                            <h2><span>653.47</span></h2>
                            <p>Nilai rata-rata Tryout Pendaftar saat ini</p>
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-6">
                        <div class="counter-text">
                            <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid mt-3" width="90px">
                            <h2><span>985.24</span></h2>
                            <p>Nilai Maksimum Tryout saat ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		@include('layouts.index.footer')
        <!-- Back To Top End -->
        @include('layouts.index.script')
    </body>
</html>