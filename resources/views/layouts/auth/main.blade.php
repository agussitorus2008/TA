    <!DOCTYPE html>
    <html lang="en" dir="ltr">
    @include('layouts.auth.head')

    <body style="background-color: #0A407F">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Inner content -->
                <div class="content-inner">
                    <!-- Content area -->
                    <div class="content d-flex justify-content-center align-items-center">
                        <div class="position-absolute top-0 start-0 m-3">
                            <img src="{{asset('assets/images/Group 17.png')}}" alt="Logo" width="300">
                        </div>
                        <!-- Login form -->
                        {{ $slot }}
                        <!-- /login form -->
                    </div>
                    <!-- /content area -->
                </div>
                <!-- /inner content -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->

        <!-- Demo config -->
        <!-- /demo config -->
    </body>

    </html>
