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

                <div class="row justify-content-center text-center">
                    <label class="form-label">email123@gmail.com</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="password" class="form-control" placeholder="•••••••••••" />
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="password" class="form-control" placeholder="•••••••••••" />
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="w-100 btn" style="background-color:#0A407F;
                    color:#FFFFFF;font-weight:bold">SIMPAN</button>
                </div>
            </div>
        </div>
    </form>
</x-auth>
