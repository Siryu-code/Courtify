@extends('layouts.app')

@section('title', 'Booking Confirmed - Courtify Arena')

@section('content')
<div class="confirmed-wrapper">
    {{-- ======================== SECTION 1: BUKTI PEMBAYARAN ======================== --}}
    <div id="receiptSection">
        <div class="receipt-card">
            {{-- Header Logo --}}
            <div class="receipt-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="receipt-logo">
                        <i class="fa-solid fa-futbol text-white"></i>
                    </div>
                    <div>
                        <div class="receipt-brand">Courtify Arena</div>
                        <div class="receipt-brand-sub">Venue Management</div>
                    </div>
                </div>
            </div>

            {{-- Judul & Status --}}
            <div class="receipt-title-row">
                <span class="receipt-title">BUKTI PEMBAYARAN</span>
                <span class="badge rounded-pill receipt-status-badge">LUNAS</span>
            </div>
            <p class="receipt-info-text">Nomor Transaksi: {{ $booking->booking_code }}</p>
            <p class="receipt-info-text">Tanggal: {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y') }}</p>
            <hr class="receipt-divider">

            {{-- Diterbitkan Oleh --}}
            <p class="receipt-label">DITERBITKAN OLEH:</p>
            <p class="receipt-value-bold">Courtify Arena</p>
            <p class="receipt-value-muted">123 Sports Way</p>
            <p class="receipt-value-muted">support@courtify.com</p>
            <div style="height: 12px;"></div>

            {{-- Dibayarkan Oleh --}}
            <p class="receipt-label">DIBAYARKAN OLEH:</p>
            <p class="receipt-value-bold">{{ $booking->customer_name }}</p>
            <p class="receipt-value-muted">{{ Auth::user()->email }}</p>
            <p class="receipt-value-muted">ID Pelanggan: CRT-{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}</p>
            <hr class="receipt-divider">

            {{-- Metode Pembayaran --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-credit-card text-muted"></i>
                <span class="receipt-payment-method">Metode: Kartu Kredit (Visa berakhiran *4242)</span>
            </div>

            {{-- Box Detail Venue --}}
            <div class="receipt-venue-box">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="receipt-venue-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <span class="receipt-label mb-0">VENUE</span>
                </div>
                <p class="receipt-venue-name">{{ $booking->venue->name }}</p>
                <hr class="receipt-divider">
                <div class="row">
                    <div class="col-6">
                        <p class="receipt-label mb-0">DATE</p>
                        <p class="receipt-venue-datetime">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M') }}</p>
                    </div>
                    <div class="col-6">
                        <p class="receipt-label mb-0">TIME</p>
                        <p class="receipt-venue-datetime">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Rincian Harga --}}
            <div class="receipt-price-list">
                <div class="receipt-price-row">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($booking->price, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-price-row">
                    <span>Biaya Admin</span>
                    <span>Rp{{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
                </div>
                @if($booking->promotion)
                    @php
                        $discountAmount = ($booking->price + $booking->admin_fee) * ($booking->promotion->discount / 100);
                    @endphp
                    <div class="receipt-price-row text-success">
                        <span>Diskon Promo ({{ $booking->promotion->discount }}%)</span>
                        <span>-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
            <hr class="receipt-divider">
            <div class="receipt-total-row">
                <span>Total Akhir</span>
                <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            {{-- Footer --}}
            <p class="receipt-footer-text">
                Terima kasih atas kepercayaan Anda. Jika ada pertanyaan, silakan hubungi 
                <a href="mailto:support@courtify.com" class="receipt-footer-link">support@courtify.com</a>
            </p>
        </div>

        {{-- Tombol Lanjutkan --}}
        <div class="receipt-continue-wrapper">
            <button type="button" class="btn btn-outline-dark receipt-continue-btn" id="continueToConfirmed">
                Lanjutkan <i class="fa-solid fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>

    {{-- ======================== SECTION 2: BOOKING CONFIRMED ======================== --}}
    <div id="confirmedSection" style="display: none;">
        {{-- Banner Gelap --}}
        <div class="confirmed-banner">
            <div class="confirmed-banner-pattern"></div>
        </div>

        {{-- Icon Check Overlap --}}
        <div class="confirmed-check-wrapper">
            <div class="confirmed-check-outer">
                <div class="confirmed-check-inner">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        {{-- Card Konten --}}
        <div class="confirmed-card">
            <h1 class="confirmed-title">Booking Confirmed!</h1>
            <p class="confirmed-subtitle">Your court has been successfully reserved. Get ready to play.</p>
            
            <span class="badge rounded-pill confirmed-id-badge">
                <i class="fa-solid fa-hashtag me-1"></i> {{ $booking->booking_code }}
            </span>

            {{-- Box Detail Venue --}}
            <div class="confirmed-venue-box">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="confirmed-venue-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <span class="confirmed-label mb-0">VENUE</span>
                </div>
                <p class="confirmed-venue-name">{{ $booking->venue->name }}</p>
                <hr class="confirmed-divider">
                <div class="row">
                    <div class="col-6 border-end">
                        <p class="confirmed-label mb-0">DATE</p>
                        <p class="confirmed-venue-datetime">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M') }}</p>
                    </div>
                    <div class="col-6">
                        <p class="confirmed-label mb-0">TIME</p>
                        <p class="confirmed-venue-datetime">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <a href="{{ route('history') }}" class="btn btn-view-history">
                VIEW HISTORY <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
            <a href="{{ route('home') }}" class="btn btn-back-home">
                BACK TO HOME
            </a>
        </div>
    </div>
</div>
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    body {
        background: #f3f4f6;
    }

    .confirmed-wrapper {
        max-width: 480px;
        margin: 0 auto;
        padding: 20px 16px;
    }

    /* ========== RECEIPT SECTION ========== */
    .receipt-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .receipt-header {
        margin-bottom: 16px;
    }
    .receipt-logo {
        width: 40px;
        height: 40px;
        background: #0d6efd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .receipt-brand {
        font-weight: 700;
        font-size: 16px;
        color: #000;
    }
    .receipt-brand-sub {
        font-size: 12px;
        color: #6b7280;
    }
    .receipt-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .receipt-title {
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 1.5px;
        color: #111827;
    }
    .receipt-status-badge {
        background: #d1fae5;
        color: #065f46;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 14px;
    }
    .receipt-info-text {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    .receipt-divider {
        margin: 14px 0;
        border-color: #f3f4f6;
    }
    .receipt-label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .receipt-value-bold {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
        margin-bottom: 2px;
    }
    .receipt-value-muted {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 0;
    }
    .receipt-payment-method {
        font-size: 14px;
        color: #374151;
    }
    .receipt-venue-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .receipt-venue-icon {
        width: 28px; height: 28px;
        background: #f3f4f6;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 12px;
    }
    .receipt-venue-name {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-bottom: 0;
    }
    .receipt-venue-datetime {
        font-weight: 700;
        font-size: 16px;
        color: #111827;
        margin-bottom: 0;
    }
    .receipt-price-list {
        margin-bottom: 4px;
    }
    .receipt-price-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #374151;
        margin-bottom: 6px;
    }
    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        font-weight: 800;
        font-size: 20px;
        color: #000;
    }
    .receipt-footer-text {
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        margin-top: 18px;
        margin-bottom: 0;
        line-height: 1.5;
    }
    .receipt-footer-link {
        color: #0d6efd;
        font-weight: 500;
        text-decoration: none;
    }
    .receipt-footer-link:hover {
        text-decoration: underline;
    }

    /* Tombol Lanjutkan */
    .receipt-continue-wrapper {
        padding: 16px 0;
    }
    .receipt-continue-btn {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        border-color: #d1d5db;
        color: #374151;
    }
    .receipt-continue-btn:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #111827;
    }

    /* ========== CONFIRMED SECTION ========== */
    .confirmed-banner {
        background: #0f172a;
        border-radius: 20px 20px 0 0;
        height: 130px;
        position: relative;
        overflow: hidden;
    }
    .confirmed-banner-pattern {
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            -45deg,
            transparent,
            transparent 10px,
            rgba(255,255,255,0.02) 10px,
            rgba(255,255,255,0.02) 20px
        );
    }
    .confirmed-check-wrapper {
        display: flex;
        justify-content: center;
        margin-top: -40px;
        position: relative;
        z-index: 5;
    }
    .confirmed-check-outer {
        width: 72px;
        height: 72px;
        background: #d1fae5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .confirmed-check-inner {
        width: 48px;
        height: 48px;
        background: #22c55e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 26px;
    }
    .confirmed-card {
        background: #fff;
        border-radius: 0 0 20px 20px;
        padding: 20px 20px 28px;
        text-align: center;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .confirmed-title {
        font-weight: 800;
        font-size: 24px;
        color: #000;
        margin-top: 12px;
        margin-bottom: 4px;
    }
    .confirmed-subtitle {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .confirmed-id-badge {
        background: #f3f4f6;
        color: #374151;
        font-weight: 700;
        font-size: 14px;
        padding: 6px 18px;
        margin-bottom: 20px;
    }
    .confirmed-venue-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: left;
    }
    .confirmed-venue-icon {
        width: 28px; height: 28px;
        background: #f3f4f6;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 12px;
    }
    .confirmed-label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .confirmed-venue-name {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-bottom: 0;
    }
    .confirmed-divider {
        margin: 14px 0;
        border-color: #f3f4f6;
    }
    .confirmed-venue-datetime {
        font-weight: 700;
        font-size: 16px;
        color: #111827;
        margin-bottom: 0;
    }

    /* Tombol Aksi */
    .btn-view-history {
        display: block;
        width: 100%;
        background: #0f172a;
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px;
        border-radius: 10px;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-view-history:hover {
        background: #1e293b;
        color: #fff;
    }
    .btn-back-home {
        display: block;
        width: 100%;
        background: transparent;
        border: 1px solid #d1d5db;
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px;
        border-radius: 10px;
        font-size: 14px;
        text-decoration: none;
        margin-top: 10px;
        transition: 0.2s;
    }
    .btn-back-home:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #111827;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 480px) {
        .confirmed-wrapper {
            padding: 32px 20px;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    document.getElementById('continueToConfirmed').addEventListener('click', function() {
        document.getElementById('receiptSection').style.display = 'none';
        document.getElementById('confirmedSection').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endpush