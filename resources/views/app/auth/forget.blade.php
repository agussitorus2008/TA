<x-auth title="Login">
    <form class="login-form" action="index.html">
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{asset('assets/images/abu muda 2.png')}}" class="h-64px" alt="" />
                    </div>
                    <h5 class="mb-0" style="color: #0A407F">LUPA PASSWORD</h5>
                </div>

                <div class="row justify-content-center text-center mb-3">
                    <label class="form-label">Masukkan email yang terdaftar</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="text" class="form-control" placeholder="john@doe.com" />
                        <div class="form-control-feedback-icon">
                            <i class="ph-user-circle text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="w-100 btn" style="background-color:#0A407F;
                    color:#FFFFFF;font-weight:bold">Selanjutnya</button>
                </div>
            </div>
        </div>
    </form>
</x-auth>
