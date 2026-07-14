@extends('layouts.app')

@section('title', 'Promo - Courtify Arena')

@section('content')
{{-- ======================== INCLUDE PARTIALS ======================== --}}


{{-- ======================== BANNER HERO PROMO ======================== --}}
@php
    $featuredPromo = $allPromotions->firstWhere('banner_image', '!=', null) ?? $allPromotions->first();
@endphp

@if($featuredPromo && $featuredPromo->banner_image)
<section class="promo-banner-wrapper">
    <div class="promo-banner" style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.7)), url('{{ Storage::url($featuredPromo->banner_image) }}') center/cover no-repeat;">
        <span class="badge rounded-pill promo-banner-badge">
            {{ strtoupper($featuredPromo->title) }}
        </span>
        <h1 class="promo-banner-title">Diskon {{ $featuredPromo->discount }}% Semua Booking</h1>
        <p class="promo-banner-desc">{{ Str::limit($featuredPromo->description, 120) }}</p>
    </div>
</section>
@endif

{{-- ======================== SECTION TITLE ======================== --}}
<div class="promo-section-header">
    <h2 class="promo-main-title">Promo & Penawaran</h2>
    <h3 class="promo-sub-title">Promo Berlaku</h3>
</div>

{{-- ======================== PROMO CARDS ======================== --}}
<div class="promo-cards-container">
    @forelse($promotions as $promo)
        <div class="promo-item-card">
            {{-- Header Row --}}
            <div class="d-flex justify-content-between align-items-center">
                @php
                    $isActive = \Carbon\Carbon::parse($promo->end_date)->isFuture() 
                                && \Carbon\Carbon::parse($promo->start_date)->isPast();
                @endphp
                <span class="badge rounded-pill promo-status-badge {{ $isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                    {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
                </span>
                <span class="badge rounded-pill promo-discount-badge bg-primary-subtle text-primary">
                    Diskon {{ $promo->discount }}%
                </span>
            </div>

            {{-- Nama Promo --}}
            <h4 class="promo-card-title">{{ $promo->title }}</h4>

            {{-- Deskripsi --}}
            <p class="promo-card-desc">{{ $promo->description }}</p>

            {{-- Kode Promo (kalau ada) --}}
            @if($promo->is_first_booking)
                <p class="promo-card-code">
                    Gunakan kode: <span class="promo-code-highlight">"FIRSTKICK"</span>
                </p>
            @endif

            {{-- Info Tanggal --}}
            <div class="promo-date-box">
                <i class="fa-regular fa-calendar"></i>
                <span>
                    {{ \Carbon\Carbon::parse($promo->start_date)->format('M d, Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse($promo->end_date)->format('M d, Y') }}
                </span>
            </div>
        </div>
    @empty
        {{-- Placeholder kalau gak ada promo aktif --}}
        <div class="promo-empty-state">
            <div class="promo-empty-icon">
                <i class="fa-solid fa-ticket-simple"></i>
            </div>
            <h4 class="promo-empty-title">Belum Ada Promo Aktif</h4>
            <p class="promo-empty-desc">
                Saat ini belum ada penawaran spesial. Cek lagi nanti atau jelajahi venue terbaik kami!
            </p>
            <a href="{{ route('home') }}#venue-section" class="btn btn-primary rounded-3 px-4">
                Jelajahi Venue
            </a>
        </div>
    @endforelse
</div>

{{-- ======================== BOTTOM SPACER ======================== --}}
<div class="bottom-spacer"></div>

{{-- ======================== INCLUDE BOTTOM NAVBAR ======================== --}}

@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- PROMO BANNER HERO --- */
    .promo-banner-wrapper {
        padding: 16px 16px 0;
    }
    .promo-banner {
        border-radius: 16px;
        padding: 28px 20px;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        position: relative;
        overflow: hidden;
    }
    .promo-banner-badge {
        background: #0d6efd;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 14px;
        letter-spacing: 0.5px;
        width: fit-content;
        margin-bottom: 12px;
    }
    .promo-banner-title {
        color: #fff;
        font-weight: 800;
        font-size: 28px;
        line-height: 1.2;
        margin-bottom: 6px;
    }
    .promo-banner-desc {
        color: rgba(255,255,255,0.85);
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 0;
        max-width: 90%;
    }

    /* --- SECTION HEADER --- */
    .promo-section-header {
        padding: 28px 16px 12px;
    }
    .promo-main-title {
        font-weight: 800;
        font-size: 22px;
        color: #000;
        margin-bottom: 4px;
    }
    .promo-sub-title {
        font-weight: 600;
        font-size: 17px;
        color: #000;
        margin-bottom: 0;
    }

    /* --- PROMO CARDS --- */
    .promo-cards-container {
        padding: 8px 16px;
    }
    .promo-item-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        transition: 0.2s;
    }
    .promo-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .promo-status-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
    }
    .bg-success-subtle {
        background-color: #d1fae5 !important;
    }
    .text-success {
        color: #065f46 !important;
    }
    .bg-secondary-subtle {
        background-color: #e5e7eb !important;
    }
    .text-secondary {
        color: #4b5563 !important;
    }
    .promo-discount-badge {
        font-size: 13px;
        font-weight: 700;
        padding: 4px 14px;
    }
    .bg-primary-subtle {
        background-color: #dbeafe !important;
    }
    .text-primary {
        color: #1d4ed8 !important;
    }
    .promo-card-title {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-top: 12px;
        margin-bottom: 6px;
    }
    .promo-card-desc {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .promo-card-code {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .promo-code-highlight {
        color: #0d6efd;
        font-weight: 600;
        background: #eff6ff;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .promo-date-box {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f9fafb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        color: #374151;
        margin-top: 12px;
    }
    .promo-date-box i {
        color: #6b7280;
    }

    /* --- EMPTY STATE --- */
    .promo-empty-state {
        text-align: center;
        padding: 48px 20px;
        background: #fff;
        border: 1px dashed #d1d5db;
        border-radius: 16px;
    }
    .promo-empty-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 16px;
    }
    .promo-empty-title {
        font-weight: 700;
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
    }
    .promo-empty-desc {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    /* --- SPACER --- */
    .bottom-spacer {
        height: 70px;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .promo-banner {
            min-height: 280px;
            padding: 36px 28px;
        }
        .promo-banner-title {
            font-size: 36px;
        }
        .promo-banner-desc {
            font-size: 16px;
            max-width: 70%;
        }
        .promo-cards-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .promo-item-card {
            padding: 24px;
        }
    }

    @media (min-width: 1024px) {
        .promo-cards-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 1000px;
        }
        .promo-item-card {
            margin-bottom: 0;
        }
    }
</style>
@endpush