@extends('layouts.app')

@section('title', 'Confirm Booking - Courtify Arena')

@section('topbar')
    <div class="back-header">
        <button type="button" onclick="history.back()" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="back-header-brand text-primary">Courtify Arena</span>
        <div class="back-btn invisible">
            <i class="fa-solid fa-arrow-left"></i>
        </div>
    </div>
@endsection

@section('content')
{{-- ======================== PAGE HEADER ======================== --}}
<div class="confirm-header">
    <h1 class="confirm-title">Confirm Booking</h1>
    <p class="confirm-subtitle">Review your details and finalize your court reservation.</p>
</div>

{{-- ======================== CARD 1: DATA DIRI ======================== --}}
<div class="confirm-card">
    <div class="confirm-card-header">
        <i class="fa-solid fa-user text-primary"></i>
        <span class="confirm-card-title">Data Diri</span>
    </div>
    <div class="confirm-card-body">
        <div class="mb-3">
            <label class="confirm-label">Nama Lengkap</label>
            <input type="text" 
                   id="customerName" 
                   class="form-control confirm-input" 
                   value="{{ old('customer_name', $booking->customer_name ?? Auth::user()->name) }}"
                   required>
        </div>
        <div class="mb-0">
            <label class="confirm-label">No Telepon</label>
            <input type="text" 
                   id="customerPhone" 
                   class="form-control confirm-input" 
                   value="{{ old('phone', $booking->phone ?? Auth::user()->phone) }}"
                   required>
        </div>
    </div>
</div>

{{-- ======================== CARD 2: PILIH VOUCHER ======================== --}}
<div class="confirm-card voucher-card" data-bs-toggle="modal" data-bs-target="#voucherModal">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="confirm-icon-box">
                <i class="fa-solid fa-tag text-primary"></i>
            </div>
            <div>
                <div class="voucher-title" id="selectedVoucherText">
                    {{ $booking->promotion ? $booking->promotion->title : 'Pilih Voucher' }}
                </div>
                <div class="voucher-subtitle">
                    {{ $booking->promotion ? 'Diskon ' . $booking->promotion->discount . '%' : 'Gunakan promo untuk lebih hemat' }}
                </div>
            </div>
        </div>
        <i class="fa-solid fa-chevron-right text-muted"></i>
    </div>
    <input type="hidden" id="selectedPromoId" value="{{ $booking->promotion_id ?? '' }}">
</div>

{{-- ======================== CARD 3: METODE PEMBAYARAN ======================== --}}
<div class="confirm-card">
    <div class="confirm-card-header">
        <i class="fa-solid fa-wallet text-primary"></i>
        <span class="confirm-card-title">Metode Pembayaran</span>
    </div>
    <div class="confirm-card-body">
        <a href="{{ route('payment') ?? '#' }}" class="payment-method-selector">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="payment-icon-small">
                        <i class="fa-solid fa-credit-card text-primary"></i>
                    </div>
                    <span id="selectedPaymentText" class="payment-method-text">
                        {{ $booking->payment_method ?? 'Pilih Metode' }}
                    </span>
                </div>
                <i class="fa-solid fa-pen text-primary"></i>
            </div>
        </a>
    </div>
</div>

{{-- ======================== CARD 4: RINGKASAN BOOKING ======================== --}}
<div class="confirm-card">
    <h3 class="summary-title">Ringkasan Booking</h3>
    
    {{-- Venue Info --}}
    <div class="d-flex gap-3 align-items-center mb-3">
        @php
            $venueImage = $booking->venue->images->first();
        @endphp
        @if($venueImage)
            <img src="{{ asset('storage/' . $venueImage->image_path) }}" 
                 alt="{{ $booking->venue->name }}" 
                 class="summary-venue-img">
        @else
            <div class="summary-venue-placeholder">
                <i class="fa-solid fa-image text-muted"></i>
            </div>
        @endif
        <div>
            <div class="summary-venue-name">{{ $booking->venue->name }}</div>
            <div class="summary-venue-location">
                <i class="fa-solid fa-location-dot"></i> {{ $booking->venue->location }}
            </div>
        </div>
    </div>

    <hr class="summary-divider">

    {{-- Detail List --}}
    @php
        $bookingDate = \Carbon\Carbon::parse($booking->booking_date);
        $endDate = \Carbon\Carbon::parse($booking->end_date);
        $startTime = \Carbon\Carbon::parse($booking->start_time);
        $endTime = \Carbon\Carbon::parse($booking->end_time);
        $durationHours = $startTime->diffInHours($endTime);
        $totalDays = $bookingDate->diffInDays($endDate) + 1;
        $totalDuration = $durationHours * $totalDays;
    @endphp

    <div class="summary-detail-list">
        <div class="summary-detail-row">
            <span class="summary-detail-label">
                <i class="fa-regular fa-calendar me-2"></i> Tanggal
            </span>
            <span class="summary-detail-value">
                @if($booking->is_multiday)
                    {{ $bookingDate->translatedFormat('l, d M Y') }} - {{ $endDate->translatedFormat('l, d M Y') }}
                @else
                    {{ $bookingDate->translatedFormat('l, d M Y') }}
                @endif
            </span>
        </div>
        <div class="summary-detail-row">
            <span class="summary-detail-label">
                <i class="fa-regular fa-clock me-2"></i> Waktu
            </span>
            <span class="summary-detail-value">{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
        </div>
        <div class="summary-detail-row">
            <span class="summary-detail-label">
                <i class="fa-solid fa-hourglass-half me-2"></i> Durasi
            </span>
            <span class="summary-detail-value">{{ $totalDuration }} Jam</span>
        </div>
    </div>

    <hr class="summary-divider">

    {{-- Rincian Harga --}}
    <div class="summary-price-list">
        <div class="summary-price-row">
            <span class="summary-price-label">Harga Lapangan</span>
            <span class="summary-price-value">Rp{{ number_format($booking->price, 0, ',', '.') }}</span>
        </div>
        <div class="summary-price-row">
            <span class="summary-price-label">Biaya Admin</span>
            <span class="summary-price-value">Rp{{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
        </div>
        @if($booking->promotion)
            @php
                $discountAmount = ($booking->price + $booking->admin_fee) * ($booking->promotion->discount / 100);
            @endphp
            <div class="summary-price-row text-success">
                <span class="summary-price-label">Diskon ({{ $booking->promotion->title }})</span>
                <span class="summary-price-value">-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <hr class="summary-divider">

    {{-- Total --}}
    <div class="summary-total-row">
        <span class="summary-total-label">Total</span>
        <span class="summary-total-value">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
    </div>
</div>

{{-- ======================== STICKY BOTTOM BUTTON ======================== --}}
<div class="confirm-sticky-bottom">
    <form method="POST" action="{{ route('booking.store') }}" class="w-100" id="confirmForm">
        @csrf
        <input type="hidden" name="venue_id" value="{{ $booking->venue_id }}">
        <input type="hidden" name="customer_name" id="hiddenCustomerName" value="{{ $booking->customer_name ?? Auth::user()->name }}">
        <input type="hidden" name="phone" id="hiddenPhone" value="{{ $booking->phone ?? Auth::user()->phone }}">
        <input type="hidden" name="booking_date" value="{{ $booking->booking_date }}">
        <input type="hidden" name="end_date" value="{{ $booking->end_date }}">
        <input type="hidden" name="start_time" value="{{ $booking->start_time }}">
        <input type="hidden" name="end_time" value="{{ $booking->end_time }}">
        <input type="hidden" name="is_multiday" value="{{ $booking->is_multiday ? 1 : 0 }}">
        <input type="hidden" name="customer_note" value="{{ $booking->customer_note ?? '' }}">
        <input type="hidden" name="promotion_id" id="hiddenPromoId" value="{{ $booking->promotion_id ?? '' }}">
        
        <button type="submit" class="btn btn-primary confirm-book-btn">
            <i class="fa-solid fa-circle-check me-2"></i> Confirm & Book
        </button>
    </form>
</div>

{{-- ======================== VOUCHER MODAL ======================== --}}
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="voucherModalLabel">Pilih Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $promotions = \App\Models\Promotion::whereDate('start_date', '<=', now())
                                                        ->whereDate('end_date', '>=', now())
                                                        ->get();
                @endphp
                
                @forelse($promotions as $promo)
                    <div class="voucher-modal-item {{ $booking->promotion_id === $promo->id ? 'active' : '' }}" 
                         onclick="selectVoucher('{{ $promo->id }}', '{{ $promo->title }}', '{{ $promo->discount }}%')"
                         data-promo-id="{{ $promo->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="voucher-modal-icon">
                                    <i class="fa-solid fa-tag text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $promo->title }}</div>
                                    <div class="text-muted small">Diskon {{ $promo->discount }}%</div>
                                </div>
                            </div>
                            @if($booking->promotion_id === $promo->id)
                                <i class="fa-solid fa-circle-check text-primary fs-5"></i>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-3">Tidak ada promo tersedia saat ini.</p>
                @endforelse
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary flex-fill" 
                        onclick="selectVoucher('', 'Pilih Voucher', 'Gunakan promo untuk lebih hemat')">
                    Hapus Voucher
                </button>
                <button type="button" class="btn btn-primary flex-fill" data-bs-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>

{{-- Spacer --}}
<div class="confirm-spacer"></div>
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- HEADER --- */
    .back-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }
    .back-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #212529;
        cursor: pointer;
        width: 32px;
        text-align: left;
        padding: 0;
    }
    .back-header-brand {
        font-weight: 700;
        font-size: 17px;
    }

    /* --- PAGE HEADER --- */
    .confirm-header {
        padding: 20px 20px 4px;
    }
    .confirm-title {
        font-weight: 700;
        font-size: 24px;
        color: #000;
        margin-bottom: 4px;
    }
    .confirm-subtitle {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    /* --- CARDS --- */
    .confirm-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 20px;
        margin: 16px 20px;
    }
    .confirm-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        font-size: 17px;
        font-weight: 700;
    }
    .confirm-card-title {
        color: #0d6efd;
    }
    .confirm-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
        display: block;
        font-weight: 500;
    }
    .confirm-input {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 15px;
    }
    .confirm-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.08);
    }

    /* --- VOUCHER --- */
    .voucher-card {
        cursor: pointer;
        transition: 0.15s;
    }
    .voucher-card:hover {
        border-color: #bfdbfe;
        background: #fafeff;
    }
    .confirm-icon-box {
        width: 40px; height: 40px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .voucher-title {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
    }
    .voucher-subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    /* --- PAYMENT METHOD --- */
    .payment-method-selector {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .payment-method-selector:hover {
        opacity: 0.9;
    }
    .payment-icon-small {
        width: 36px; height: 36px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .payment-method-text {
        font-weight: 500;
        font-size: 15px;
        color: #111827;
    }

    /* --- RINGKASAN --- */
    .summary-title {
        font-weight: 700;
        font-size: 17px;
        color: #000;
        margin-bottom: 14px;
    }
    .summary-venue-img {
        width: 64px; height: 64px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .summary-venue-placeholder {
        width: 64px; height: 64px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .summary-venue-name {
        font-weight: 600;
        font-size: 16px;
        color: #111827;
    }
    .summary-venue-location {
        font-size: 13px;
        color: #6b7280;
    }
    .summary-venue-location i {
        margin-right: 3px;
    }
    .summary-divider {
        margin: 14px 0;
        border-color: #f3f4f6;
    }
    .summary-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .summary-detail-label {
        font-size: 14px;
        color: #6b7280;
    }
    .summary-detail-value {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }
    .summary-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .summary-price-label {
        font-size: 14px;
        color: #6b7280;
    }
    .summary-price-value {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }
    .summary-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-total-label {
        font-weight: 700;
        font-size: 16px;
        color: #000;
    }
    .summary-total-value {
        font-weight: 800;
        font-size: 19px;
        color: #0d6efd;
    }

    /* --- STICKY BOTTOM --- */
    .confirm-sticky-bottom {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        padding: 14px 20px;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        z-index: 100;
    }
    .confirm-book-btn {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
    }

    /* --- VOUCHER MODAL --- */
    .voucher-modal-item {
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: 0.15s;
    }
    .voucher-modal-item.active {
        border-color: #0d6efd;
        background: #eff6ff;
    }
    .voucher-modal-item:hover {
        border-color: #bfdbfe;
    }
    .voucher-modal-icon {
        width: 36px; height: 36px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- SPACER --- */
    .confirm-spacer {
        height: 90px;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .confirm-card,
        .confirm-header {
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    // Sync input data diri ke hidden form
    document.getElementById('customerName').addEventListener('input', function() {
        document.getElementById('hiddenCustomerName').value = this.value;
    });
    document.getElementById('customerPhone').addEventListener('input', function() {
        document.getElementById('hiddenPhone').value = this.value;
    });

    // Select voucher
    function selectVoucher(promoId, title, subtitle) {
        document.getElementById('selectedPromoId').value = promoId;
        document.getElementById('hiddenPromoId').value = promoId;
        document.getElementById('selectedVoucherText').textContent = title;
        document.querySelector('.voucher-subtitle').textContent = subtitle;
        
        // Update visual di modal
        document.querySelectorAll('.voucher-modal-item').forEach(item => {
            item.classList.remove('active');
            const check = item.querySelector('.fa-circle-check');
            if (check) check.remove();
        });
        
        const activeItem = document.querySelector(`[data-promo-id="${promoId}"]`);
        if (activeItem && promoId) {
            activeItem.classList.add('active');
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-circle-check text-primary fs-5';
            activeItem.querySelector('.d-flex').appendChild(icon);
        }
    }
</script>
@endpush