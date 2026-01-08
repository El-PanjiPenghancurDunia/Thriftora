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

        <div class="col-md-5">
            <div class="card bg-dark text-white border-secondary shadow-lg">
                <div class="card-body p-4">
                    <h5 class="text-warning fw-bold">Ringkasan Biaya</h5>
                    <hr class="border-secondary">
                    @php $totalBarang = $carts->sum(function($item){ return $item->product->harga; }); @endphp
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Total Barang</span>
                        <strong>Rp {{ number_format($totalBarang) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Ongkir</span>
                        <strong id="ongkirDisplay" class="text-info">Rp 0</strong>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between">
                        <span class="h5 mb-0">Total Bayar</span>
                        <strong class="h4 text-warning mb-0" id="grandTotal">Rp {{ number_format($totalBarang) }}</strong>
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
    const totalBarang = {{ $totalBarang }};

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

        document.getElementById('ongkirDisplay').innerText = 'Rp ' + hargaOngkir.toLocaleString();
        document.getElementById('grandTotal').innerText = 'Rp ' + (totalBarang + hargaOngkir).toLocaleString();
        document.getElementById('shipping_cost_input').value = hargaOngkir;
    }
</script>
@endsection