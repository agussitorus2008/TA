
<x-auth title="Login">

    <!-- Registration form -->
    <form class="flex-fill" method="post" action="{{ route('auth.doregister') }}">
        @csrf
        <div class="row">
            <div class="col-lg-4 offset-lg-4">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                                <img src="{{asset('assets/images/abu muda 2.png')}}" class="h-48px" alt="">
                            </div>
                            <h5 class="mb-0" style="color: #0A407F">DAFTAR AKUN</h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NAMA LENGKAP</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap Anda">
                                <div class="form-control-feedback-icon">
                                    <i class="ph-user-circle text-muted"></i>
                                </div>
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label class="form-label">NOMOR HP (WA AKTIF)</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="number" name="no_handphone" class="form-control" value="{{ old('no_handphone') }}" placeholder="Masukkan Nomor Hp">
                                <div class="form-control-feedback-icon">
                                    <i class="ph-user-circle-plus text-muted"></i>
                                </div>
                                @error('no_handphone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label class="form-label">EMAIL AKTIF</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Masukkan Email">
                                <div class="form-control-feedback-icon">
                                    <i class="ph-at text-muted"></i>
                                </div>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">PASSWORD</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Masukkan Password">
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-lock text-muted"></i>
                                        </div>
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">ULANGI PASSWORD</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="password" name="password_confirmation" class="form-control" value="{{ old('passowrd_confirmation') }}" placeholder="Masukkan Password">
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-lock text-muted"></i>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" required>
                            <span class="form-check-label">Accept <a href="#">&nbsp;terms of service</a></span>
                        </label>
                    </div>

                    <div class="card-body text-center border-top mt-auto">
                        <button type="submit" class="btn w-35" style="background-color: #6CE670;color: #0A407F;font-weight:bold">
                            SUBMIT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /registration form -->
</x-auth>
