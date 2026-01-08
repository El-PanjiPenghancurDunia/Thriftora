@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary pt-4 pb-2">
                    <h4 class="fw-bold text-warning"><i class="bi bi-person-gear"></i> Edit Profil</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle border border-warning shadow" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=ffc107&color=000&size=120" class="rounded-circle border border-warning shadow" width="120">
                                @endif
                            </div>
                            <label class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-camera"></i> Ganti Foto
                                <input type="file" name="photo" class="d-none" onchange="this.form.submit()">
                            </label>
                            <div class="form-text text-secondary">Klik tombol di atas untuk memilih foto baru.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-secondary">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control text-white" value="{{ old('name', $user->name) }}" required style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-secondary">Email</label>
                                <input type="email" name="email" class="form-control text-white" value="{{ old('email', $user->email) }}" required style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-secondary">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control text-white" value="{{ old('no_hp', $user->no_hp) }}" required style="background-color: #2b3035; border: 1px solid #495057;">
                        </div>

                        <div class="mb-3">
                            <label class="text-secondary">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" class="form-control text-white" rows="3" required style="background-color: #2b3035; border: 1px solid #495057;">{{ old('alamat_pengiriman', $user->alamat_pengiriman) }}</textarea>
                        </div>

                        <hr class="border-secondary my-4">
                        <h5 class="text-warning mb-3"><i class="bi bi-shield-lock"></i> Ganti Password</h5>
                        <p class="text-muted small">Kosongkan jika tidak ingin mengubah password.</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-secondary">Password Baru</label>
                                <input type="password" name="password" class="form-control text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-secondary">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-warning fw-bold px-4 py-2">Simpan Perubahan</button>
                        </div>
                    </form>
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