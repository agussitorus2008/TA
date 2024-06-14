<x-auth title="Login">
    <form class="login-form" action="{{ route('auth.post-forget-password') }}" method="POST">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{ asset('assets/images/abu muda 2.png') }}" class="h-64px" alt="" />
                    </div>
                    <h5 class="mb-0" style="color: #0A407F">LUPA PASSWORD</h5>
                </div>

                <div class="row justify-content-center text-center mb-3">
                    @if (isset($error))
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endif
                </div>


                <div class="row justify-content-center text-center mb-3">
                    <label class="form-label">Masukkan email yang terdaftar</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input type="email" name="email" class="form-control" placeholder="masukkan email" />
                        <div class="form-control-feedback-icon">
                            <i class="ph-user-circle text-muted"></i>
                        </div>
                    </div>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="w-100 btn"
                        style="background-color:#0A407F;
                    color:#FFFFFF;font-weight:bold">Selanjutnya</button>
                </div>
            </div>
        </div>
    </form>
</x-auth>
