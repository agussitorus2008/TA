<style>
    .text-center {
        text-align: center;
    }

    .line-container {
        display: flex;
        align-items: center;
    }

    .line {
        flex: 1;
        border-bottom: 1px solid #000;
        margin: 0 5px;
    }

    .text {
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
        padding: 0 10px;
    }
</style>

<x-auth title="Login">
    <div class="login-form">
        <form method="post" action="{{ route('auth.dologin') }}">
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                            <img src="{{ asset('assets/images/abu muda 2.png') }}" class="h-64px" alt="" />
                        </div>
                        <h5 class="mb-0" style="color: #0A407F">MASUK</h5>
                        <div class="text-center mb-3">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="text" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="email@gmail.com" />
                            <div class="form-control-feedback-icon">
                                <i class="ph-user-circle text-muted"></i>
                            </div>
                            @error('email')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" name="password" value="{{ old('password') }}" class="form-control"
                                placeholder="•••••••••••" />
                            <div class="form-control-feedback-icon">
                                <i class="ph-lock text-muted"></i>
                            </div>
                            @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 text-right">
                        <a href="{{ route('auth.forget-password') }}" class="text-decoration-none ml-auto"
                            style="color:#0A407F;font-weight:bold">Lupa Password?</a>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="w-100 btn"
                            style="background-color:#09C211;
                    color:#0A407F;font-weight:bold">Masuk</button>
                    </div>

                    <div class="text-center mb-3">
                        <div class="line-container">
                            <div class="line"></div>
                            <div class="text">BELUM PUNYA AKUN</div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="/auth/register" class="w-100 btn"
                            style="background-color:#0A407F;
                        color:#FFFFFF;font-weight:bold">Daftar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-auth>
