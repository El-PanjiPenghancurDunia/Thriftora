@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-0 shadow-lg" style="border-radius: 15px;">
                <div class="card-header bg-transparent border-secondary text-center pt-4">
                    <h3 class="fw-bold text-warning mb-0">REGISTER</h3>
                    <p class="text-muted small">Gabung sekarang dan temukan gaya unikmu</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-secondary">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-secondary">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label text-secondary">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" required style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Alamat Lengkap</label>
                            <textarea name="alamat_pengiriman" class="form-control" rows="2" required style="background-color: #2b3035; border: 1px solid #495057; color: white;"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary">Daftar Sebagai</label>
                            <select name="role" class="form-select" style="background-color: #2b3035; border: 1px solid #495057; color: white;">
                                <option value="pembeli">Pembeli (Customer)</option>
                                <option value="penjual">Penjual (Seller)</option>
                            </select>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-warning fw-bold py-2">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-transparent border-secondary text-center pb-4">
                    <p class="mb-0 small text-secondary">Sudah punya akun? <a href="{{ route('login') }}" class="text-warning text-decoration-none fw-bold">Login disini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body{
        background-color: #1a1a1a;
    }
</style>
@endsection