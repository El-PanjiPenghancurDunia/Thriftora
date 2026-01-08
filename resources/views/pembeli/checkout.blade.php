@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-white">Checkout Pengiriman</h2>
    
    <div class="row mt-4">
        <div class="col-md-7">
            <div class="card bg-dark text-white border-secondary shadow-lg mb-4">
                <div class="card-header bg-transparent border-secondary text-warning fw-bold">
                    <i class="bi bi-geo-alt"></i> Alamat Penerima
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="text-secondary">Nama Penerima</label>
                            <input type="text" class="form-control text-white" value="{{ Auth::user()->name }}" readonly style="background-color: #2b3035; border: 1px solid #495057;">
                        </div>

                        <div class="mb-3">
                            <label class="text-secondary">Kota Tujuan</label>
                            <select name="city_id" class="form-select text-white" style="background-color: #2b3035; border: 1px solid #495057;">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="text-secondary">Kurir</label>
                            <select name="courier" id="courier" class="form-select text-white" onchange="cekOngkirPalsu()" style="background-color: #2b3035; border: 1px solid #495057;">
                                <option value="">-- Pilih Kurir --</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS Indonesia</option>
                                <option value="tiki">TIKI</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="text-secondary">Layanan</label>
                            <select name="courier_service" id="service" class="form-select text-white" onchange="updateHarga()" disabled style="background-color: #2b3035; border: 1px solid #495057;">
                                <option value="">-- Pilih Kurir Dulu --</option>
                            </select>
                        </div>
                        
                        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                        
                        <button type="submit" class="btn btn-success w-100 mt-3 fw-bold py-2">
                            <i class="bi bi-shield-lock"></i> BAYAR SEKARANG
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
    <div class="card bg-dark text-white border-secondary shadow-lg position-sticky" style="top: 100px;">
        <div class="card-header border-secondary bg-secondary bg-opacity-10 py-3">
            <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-receipt"></i> Ringkasan Belanja</h5>
        </div>
        <div class="card-body p-4">
            
            <div class="mb-3">
                <small class="text-secondary fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Item Dibeli</small>
                <ul class="list-group list-group-flush mt-2">
                    @foreach($carts as $item)
                    <li class="list-group-item bg-transparent text-white border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $item->product->gambar) }}" class="rounded me-2 border border-secondary" width="40" height="40" style="object-fit: cover;">
                            <div style="line-height: 1.2;">
                                <span class="d-block text-truncate" style="max-width: 130px;">{{ $item->product->nama_produk }}</span>
                                <small class="text-secondary">{{ $item->quantity }} x Rp {{ number_format($item->product->harga) }}</small>
                            </div>
                        </div>
                        <span class="fw-bold">Rp {{ number_format($item->product->harga * $item->quantity) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <hr class="border-secondary border-2 border-dashed my-3">

            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary">Subtotal Barang</span>
                <span class="fw-bold">Rp {{ number_format($carts->sum(fn($i) => $i->product->harga * $i->quantity)) }}</span>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
                <span class="text-secondary">Ongkos Kirim</span>
                <span class="fw-bold text-warning" id="ongkir_display">Rp 0</span>
            </div>

            <hr class="border-secondary my-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fs-5">Total Bayar</span>
                <span class="fs-4 fw-bold text-warning" id="total_bayar_display">
                    Rp {{ number_format($carts->sum(fn($i) => $i->product->harga * $i->quantity)) }}
                </span>
            </div>

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

<script>
    // PERBAIKAN 1: Hitung total langsung dari data $carts
    const totalBarang = {{ $carts->sum(fn($i) => $i->product->harga * $i->quantity) }};

    function cekOngkirPalsu() {
        const courier = document.getElementById('courier').value;
        const serviceSelect = document.getElementById('service');

        if(courier) {
            serviceSelect.disabled = false;
            serviceSelect.innerHTML = '<option>Loading...</option>';

            fetch("{{ route('api.checkOngkir') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ courier: courier })
            })
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
                data.forEach(item => {
                    let text = item.service + ' - Rp ' + item.cost.toLocaleString() + ' (' + item.etd + ')';
                    let option = document.createElement('option');
                    option.value = item.service;
                    option.text = text;
                    option.setAttribute('data-harga', item.cost);
                    serviceSelect.appendChild(option);
                });
            });
        }
    }

    function updateHarga() {
        const serviceSelect = document.getElementById('service');
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const hargaOngkir = parseInt(selectedOption.getAttribute('data-harga')) || 0;

        // PERBAIKAN 2: Sesuaikan ID dengan HTML yang Anda buat di atas
        // ID di HTML adalah 'ongkir_display' dan 'total_bayar_display'
        document.getElementById('ongkir_display').innerText = 'Rp ' + hargaOngkir.toLocaleString();
        
        // Update Total Bayar (Barang + Ongkir)
        const grandTotal = totalBarang + hargaOngkir;
        document.getElementById('total_bayar_display').innerText = 'Rp ' + grandTotal.toLocaleString();
        
        // Masukkan ke input hidden agar terkirim ke database
        document.getElementById('shipping_cost_input').value = hargaOngkir;
    }
</script>
@endsection