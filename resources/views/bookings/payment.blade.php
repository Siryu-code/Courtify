@extends('layouts.app')

@section('title', 'Metode Pembayaran - Courtify Arena')

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
<div class="payment-page-header">
    <h1 class="payment-page-title">Metode Pembayaran</h1>
</div>

{{-- ======================== METODE TERSIMPAN ======================== --}}
<div class="payment-section">
    <h2 class="payment-section-title">Metode Tersimpan</h2>
    
    <div class="payment-list">
        {{-- Kartu Kredit/Debit --}}
        <div class="payment-item">
            <div class="d-flex align-items-center gap-3">
                <div class="payment-item-icon">
                    <i class="fa-solid fa-credit-card text-primary"></i>
                </div>
                <div>
                    <div class="payment-item-name">Kartu Kredit/Debit</div>
                    <div class="payment-item-masked">•••• •••• •••• 1234</div>
                </div>
            </div>
            <button type="button" class="payment-delete-btn" title="Hapus">
                <i class="fa-solid fa-trash text-danger"></i>
            </button>
        </div>

        {{-- GoPay --}}
        <div class="payment-item">
            <div class="d-flex align-items-center gap-3">
                <div class="payment-item-icon">
                    <i class="fa-solid fa-wallet text-primary"></i>
                </div>
                <div>
                    <div class="payment-item-name">GoPay</div>
                    <div class="payment-item-masked">0812••••5678</div>
                </div>
            </div>
            <button type="button" class="payment-delete-btn" title="Hapus">
                <i class="fa-solid fa-trash text-danger"></i>
            </button>
        </div>

        {{-- OVO --}}
        <div class="payment-item">
            <div class="d-flex align-items-center gap-3">
                <div class="payment-item-icon">
                    <i class="fa-solid fa-wallet text-primary"></i>
                </div>
                <div>
                    <div class="payment-item-name">OVO</div>
                    <div class="payment-item-masked">0856••••9012</div>
                </div>
            </div>
            <button type="button" class="payment-delete-btn" title="Hapus">
                <i class="fa-solid fa-trash text-danger"></i>
            </button>
        </div>
    </div>
</div>

{{-- ======================== TOMBOL TAMBAH ======================== --}}
<div class="payment-add-section">
    <button type="button" class="btn btn-primary payment-add-btn" id="addPaymentBtn">
        <i class="fa-solid fa-circle-plus me-2"></i> Tambah Metode Pembayaran
    </button>
</div>

{{-- ======================== MODAL TAMBAH (Dummy) ======================== --}}
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="addPaymentModalLabel">Tambah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted text-center py-4">
                    <i class="fa-solid fa-lock fs-1 d-block mb-3"></i>
                    Fitur ini akan segera hadir. Untuk saat ini, metode pembayaran bersifat statis.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Spacer --}}
<div class="payment-spacer"></div>
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
    .payment-page-header {
        padding: 20px 20px 4px;
    }
    .payment-page-title {
        font-weight: 700;
        font-size: 22px;
        color: #000;
    }

    /* --- SECTION --- */
    .payment-section {
        padding: 16px 20px;
    }
    .payment-section-title {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-bottom: 14px;
    }

    /* --- PAYMENT ITEMS --- */
    .payment-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .payment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 16px;
        transition: 0.15s;
    }
    .payment-item:hover {
        border-color: #d1d5db;
    }
    .payment-item-icon {
        width: 40px; height: 40px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .payment-item-name {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
    }
    .payment-item-masked {
        font-size: 13px;
        color: #6b7280;
    }
    .payment-delete-btn {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        transition: 0.15s;
    }
    .payment-delete-btn:hover {
        background: #fef2f2;
    }

    /* --- ADD BUTTON --- */
    .payment-add-section {
        padding: 20px 20px;
    }
    .payment-add-btn {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
    }

    /* --- SPACER --- */
    .payment-spacer {
        height: 40px;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .payment-section,
        .payment-add-section,
        .payment-page-header {
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    document.getElementById('addPaymentBtn').addEventListener('click', function() {
        // Tampilkan modal dummy
        const modal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
        modal.show();
    });

    // Delete payment (dummy)
    document.querySelectorAll('.payment-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Apakah kamu yakin ingin menghapus metode pembayaran ini?')) {
                this.closest('.payment-item').style.opacity = '0.4';
                this.closest('.payment-item').style.pointerEvents = 'none';
            }
        });
    });
</script>
@endpush