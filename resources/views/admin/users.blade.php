@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">👥 Kelola Pengguna</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card bg-dark text-white border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'penjual')
                                    <span class="badge bg-warning text-dark">Penjual</span>
                                @else
                                    <span class="badge bg-info text-dark">Pembeli</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                      onsubmit="return confirm('HAPUS USER? Data user ini akan hilang permanen!');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5">Tidak ada user lain.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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