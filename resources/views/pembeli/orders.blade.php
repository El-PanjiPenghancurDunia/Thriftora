@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">Riwayat Belanja Saya</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-warning">Lanjut Belanja</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card bg-dark text-white border-secondary shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse($transactions as $trx)
    <tr>
        <td class="ps-4 text-secondary">{{ $trx->created_at->format('d M Y') }}</td>
        <td>
            <strong class="text-warning">{{ $trx->product->nama_produk ?? 'Produk Dihapus' }}</strong><br>
            <small class="text-secondary">{{ strtoupper($trx->courier) }}</small>
        </td>
        <td class="fw-bold text-white">Rp {{ number_format($trx->total_harga) }}</td>
        <td>
            @if($trx->status == 'Menunggu Pembayaran')
                <span class="badge bg-warning text-dark">Belum Dibayar</span>
            @elseif($trx->status == 'Dibayar')
                <span class="badge bg-info text-dark">Lunas</span>
            @elseif($trx->status == 'Dikirim')
                <span class="badge bg-primary">Dikirim</span>
            @elseif($trx->status == 'Selesai')
                <span class="badge bg-success">Selesai</span>
            @endif
        </td>
        <td class="text-end pe-4">
            @if($trx->status == 'Menunggu Pembayaran')
                <button class="btn btn-success btn-sm fw-bold" 
                    onclick="openFakeMidtrans('{{ $trx->snap_token }}', '{{ $trx->total_harga }}')">
                    Bayar
                </button>
            
            @elseif($trx->status == 'Dikirim')
                <form action="{{ route('orders.complete', $trx->id) }}" method="POST" onsubmit="return confirm('Sudah terima barang?');">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-check-circle"></i> Diterima
                    </button>
                </form>

            @elseif($trx->status == 'Selesai')
                @php
                    $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                        ->where('product_id', $trx->product_id)
                                        ->first();
                @endphp

                @if($existingReview)
                    <button class="btn btn-outline-success btn-sm fw-bold" 
                            onclick="showMyReview('{{ $existingReview->rating }}', '{{ $existingReview->comment }}')">
                        <i class="bi bi-eye"></i> Ulasan Saya
                    </button>
                @else
                    <button class="btn btn-warning btn-sm fw-bold text-dark" 
                            onclick="openReviewModal({{ $trx->product_id }})">
                        <i class="bi bi-star-fill"></i> Beri Ulasan
                    </button>
                @endif

            @else
                <a href="{{ route('pembeli.orders.show', $trx->id) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi.</td></tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showReviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-success"><i class="bi bi-chat-quote-fill"></i> Ulasan Saya</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h1 class="mb-2" id="displayRating"></h1>
                <p class="text-secondary small mb-4">Rating yang Anda berikan</p>
                
                <div class="p-3 rounded border border-secondary bg-secondary bg-opacity-10">
                    <i class="bi bi-quote fs-3 text-warning"></i>
                    <p class="fst-italic fs-5 mb-0" id="displayComment">...</p>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fakeMidtransModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary shadow-lg">
            
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-warning">
                    <i class="bi bi-shield-check"></i> Thriftora Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="resetModal()"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="text-center mb-4 border-bottom border-secondary pb-3">
                    <p class="text-secondary mb-0 small">Total Pembayaran</p>
                    <h3 class="fw-bold text-white" id="modalTotalAmount">Rp 0</h3>
                    <small class="text-secondary">Order ID: <span id="modalOrderId" class="font-monospace">...</span></small>
                </div>

                <div id="view-select-method">
                    <p class="fw-bold mb-3 text-secondary">Pilih Metode Pembayaran:</p>
                    <div class="list-group">
                        <button class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center py-3" 
                                onclick="showPaymentDetails('BCA')">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bank text-primary fs-4 me-3"></i> 
                                <div><span class="fw-bold">BCA Virtual Account</span><br><small class="text-secondary">Cek otomatis</small></div>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <button class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center py-3" 
                                onclick="showPaymentDetails('Mandiri')">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-wallet2 text-warning fs-4 me-3"></i> 
                                <div><span class="fw-bold">Mandiri Bill</span><br><small class="text-secondary">Cek otomatis</small></div>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <button class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center py-3" 
                                onclick="showPaymentDetails('QRIS')">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-qr-code text-success fs-4 me-3"></i> 
                                <div><span class="fw-bold">QRIS (GoPay/OVO)</span><br><small class="text-secondary">Scan langsung bayar</small></div>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div id="view-payment-details" style="display: none;">
                    <div id="payment-content" class="text-center mb-4"></div>

                    <div class="text-center p-3 rounded border border-warning dashed-border" style="background-color: #2b3035;">
                        <div class="spinner-border spinner-border-sm text-warning mb-2" role="status"></div>
                        <h6 class="fw-bold text-white mb-1">Menunggu Pembayaran...</h6>
                        <small class="text-secondary">Sistem mendeteksi otomatis dalam 5 detik.</small>
                    </div>

                    <div class="d-grid mt-3">
                        <button class="btn btn-outline-secondary btn-sm" onclick="backToSelection()">&laquo; Ganti Metode</button>
                    </div>
                </div>

                <div id="view-processing" style="display: none;" class="text-center py-4">
                    <div id="loading-spinner">
                        <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                        <h5 class="text-white">Memverifikasi...</h5>
                    </div>
                    <div id="success-message" style="display: none;">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 fw-bold text-white">Pembayaran Berhasil!</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary shadow-lg">
            <form id="reviewForm" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-warning">Beri Ulasan Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <label class="form-label text-white">Berikan Bintang</label>
                        <select name="rating" class="form-select text-center fw-bold fs-5 text-warning" style="background-color: #2b3035; border: 1px solid #495057;">
                            <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                            <option value="3">⭐⭐⭐ (Lumayan)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary">Tulis Komentar Anda</label>
                        <textarea name="comment" class="form-control text-white" rows="3" placeholder="Barang bagus, pengiriman cepat..." required style="background-color: #2b3035; border: 1px solid #495057;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="submit" class="btn btn-warning fw-bold w-100">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body{
        background-color: #1a1a1a;
    }
</style>
<script>
    let currentToken = "";
    let autoCheckTimer = null;

    function openFakeMidtrans(token, harga) {
        currentToken = token;
        let rupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(harga);
        document.getElementById('modalTotalAmount').innerText = rupiah;
        document.getElementById('modalOrderId').innerText = token; 
        resetModal(); 
        var myModal = new bootstrap.Modal(document.getElementById('fakeMidtransModal'));
        myModal.show();
    }

    function showPaymentDetails(method) {
        document.getElementById('view-select-method').style.display = 'none';
        document.getElementById('view-payment-details').style.display = 'block';
        const contentDiv = document.getElementById('payment-content');
        let vaNumber = "88000" + Math.floor(100000000 + Math.random() * 900000000);
        
        if (method === 'QRIS') {
            // QR Code Background harus putih agar bisa discan
            contentDiv.innerHTML = `
                <h5 class="fw-bold mb-3 text-white">Scan QR Code</h5>
                <div class="p-3 bg-white border rounded d-inline-block mb-2 shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Thriftora-${currentToken}" alt="QR Code" class="img-fluid">
                </div>
                <p class="small text-secondary">Buka GoPay/OVO/Dana dan scan kode ini.</p>
            `;
        } else {
            if(method === 'Mandiri') vaNumber = "70012" + Math.floor(100000000 + Math.random() * 900000000);
            contentDiv.innerHTML = `
                <div class="text-start p-3 rounded border border-secondary mb-3" style="background-color: #2b3035;">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-bold text-warning">${method} Virtual Account</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center bg-dark p-2 border border-secondary rounded">
                        <span class="fw-bold fs-4 text-white font-monospace">${vaNumber}</span>
                    </div>
                </div>
            `;
        }

        if(autoCheckTimer) clearTimeout(autoCheckTimer);
        autoCheckTimer = setTimeout(() => { simulateProcessing(); }, 5000);
    }

    function simulateProcessing() {
        document.getElementById('view-payment-details').style.display = 'none';
        document.getElementById('view-processing').style.display = 'block';
        setTimeout(() => {
            fetch("{{ route('payment.fakeSuccess') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ snap_token: currentToken })
            }).then(response => response.json()).then(data => {
                if(data.success) {
                    document.getElementById('loading-spinner').style.display = 'none';
                    document.getElementById('success-message').style.display = 'block';
                    setTimeout(() => window.location.reload(), 2000); 
                }
            });
        }, 2000); 
    }

    function backToSelection() {
        if(autoCheckTimer) clearTimeout(autoCheckTimer);
        document.getElementById('view-select-method').style.display = 'block';
        document.getElementById('view-payment-details').style.display = 'none';
        document.getElementById('view-processing').style.display = 'none';
    }

    function resetModal() {
        if(autoCheckTimer) clearTimeout(autoCheckTimer);
        backToSelection();
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('success-message').style.display = 'none';
    }

    function openReviewModal(productId) {
        const form = document.getElementById('reviewForm');
        form.action = "/products/" + productId + "/review";
        var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        myModal.show();
    }

    // Fungsi Baru: Tampilkan Ulasan Saya
    function showMyReview(rating, comment) {
        let stars = "";
        for(let i=0; i<rating; i++) { stars += "⭐"; }
        
        document.getElementById('displayRating').innerHTML = stars;
        document.getElementById('displayComment').innerText = comment;
        
        var myModal = new bootstrap.Modal(document.getElementById('showReviewModal'));
        myModal.show();
    }
</script>
@endsection