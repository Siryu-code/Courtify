@extends('layouts.app')

@section('title', $venue->name)

@section('hideBottomNavbar', true)

{{-- Override topbar: kita ganti dengan back button aja --}}
@section('topbar')
    <div class="back-header">
        <button type="button" onclick="history.back()" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
    </div>
@endsection

@section('content')
{{-- ======================== GALLERY CAROUSEL ======================== --}}
<div id="venueCarousel" class="carousel slide venue-carousel" data-bs-ride="false">
    {{-- Badge Status --}}
    @php
        $statusClass = match($venue->status) {
            'available' => 'bg-success',
            'in_use' => 'bg-warning text-dark',
            default => 'bg-danger'
        };
        $statusText = match($venue->status) {
            'available' => 'TERSEDIA',
            'in_use' => 'SEDANG DIGUNAKAN',
            default => 'DALAM PERBAIKAN'
        };
    @endphp
    <span class="badge rounded-pill status-badge {{ $statusClass }}">
        <i class="fa-solid fa-circle-check me-1"></i> {{ $statusText }}
    </span>

    {{-- Image Counter --}}
    @php
        $imageCount = $venue->images->count();
    @endphp
    <span class="badge rounded-pill image-counter bg-dark bg-opacity-75 text-white">
        <i class="fa-regular fa-images me-1"></i> <span id="currentSlide">1</span> / {{ $imageCount }}
    </span>

    {{-- Carousel Inner --}}
    <div class="carousel-inner">
        @foreach($venue->images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ Storage::url($image->image_path) }}">
                     class="d-block w-100 venue-gallery-img" 
                     alt="{{ $venue->name }} - Image {{ $index + 1 }}">
            </div>
        @endforeach
    </div>

    {{-- Controls --}}
    @if($imageCount > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#venueCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#venueCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    @endif
</div>

{{-- ======================== INFO UTAMA ======================== --}}
<div class="venue-detail-info">
    {{-- Harga --}}
    <div class="venue-price-header">
        <span class="price-amount">Rp{{ number_format($venue->price_per_hour, 0, ',', '.') }}</span>
        <span class="price-unit">/Jam</span>
    </div>

    {{-- Nama Venue --}}
    <h1 class="venue-detail-name">{{ $venue->name }}</h1>

    {{-- Lokasi --}}
    <div class="venue-detail-location">
        <i class="fa-solid fa-location-dot"></i>
        <span>{{ $venue->location }}</span>
    </div>

    {{-- Rating & Jenis Lapangan --}}
    <div class="venue-detail-meta">
        <span class="meta-rating">
            <i class="fa-solid fa-star"></i>
            <strong>{{ number_format($avgRating ?? 0, 1) }}</strong>
            <span class="text-muted">({{ $venue->ratings->count() }} reviews)</span>
        </span>
        <span class="meta-separator">•</span>
        <span class="meta-type">{{ $venue->type }}</span>
    </div>

    <hr class="venue-divider">
</div>

{{-- ======================== FASILITAS ======================== --}}
@if(!empty($venue->facilities) && count($venue->facilities) > 0)
<div class="venue-detail-section">
    <h2 class="section-title">Fasilitas</h2>
    
    @php
        // Mapping nama fasilitas ke icon Font Awesome
        $facilityIcons = [
            'Parkir Gratis' => 'fa-square-parking',
            'Parkir' => 'fa-square-parking',
            'Pemandian' => 'fa-shower',
            'Shower' => 'fa-shower',
            'Toilet' => 'fa-restroom',
            'AC' => 'fa-snowflake',
            'Ruang Ganti' => 'fa-person-booth',
            'Loker' => 'fa-box',
            'WiFi' => 'fa-wifi',
            'Kantin' => 'fa-utensils',
            'Tribun' => 'fa-chair',
            'Sound System' => 'fa-volume-high',
            'Papan Skor' => 'fa-table',
            'Pencahayaan LED' => 'fa-lightbulb',
            'Rumput Sintetis' => 'fa-leaf',
        ];
    @endphp

    <div class="row row-cols-2 g-3">
        @foreach($venue->facilities as $facility)
            @php
                $icon = $facilityIcons[$facility->name] ?? 'fa-circle-check';
            @endphp
            <div class="col">
                <div class="facility-card">
                    <i class="fa-solid {{ $icon }} facility-icon"></i>
                    <span class="facility-label">{{ $facility->name }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Spacer buat sticky button --}}
<div class="sticky-spacer"></div>

{{-- ======================== STICKY BOOK NOW BUTTON ======================== --}}
<div class="sticky-book-btn">
    <div class="container px-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-white">
                <small>Mulai dari</small>
                <div class="fw-bold fs-5">Rp{{ number_format($venue->price_per_hour, 0, ',', '.') }} <span class="fw-normal fs-6">/jam</span></div>
            </div>
            <a href="{{ route('bookings.index', $venue->id) }}" class="btn btn-light btn-lg rounded-3 fw-bold px-4">
                Book Now
            </a>
        </div>
    </div>
</div>
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- BACK HEADER --- */
    .back-header {
        background: #f8f9fa;
        padding: 16px 20px;
    }
    .back-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #212529;
        cursor: pointer;
        padding: 0;
    }
    .back-btn:hover {
        color: #0d6efd;
    }

    /* --- VENUE CAROUSEL --- */
    .venue-carousel {
        position: relative;
    }
    .venue-gallery-img {
        height: 280px;
        object-fit: cover;
    }
    @media (min-width: 768px) {
        .venue-gallery-img {
            height: 420px;
        }
    }

    /* Badge Status */
    .status-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 10;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        letter-spacing: 0.3px;
    }

    /* Image Counter */
    .image-counter {
        position: absolute;
        bottom: 16px;
        right: 16px;
        z-index: 10;
        font-size: 12px;
        padding: 6px 12px;
        font-weight: 500;
    }

    /* Carousel controls */
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.8);
        border-radius: 50%;
        opacity: 0.7;
    }
    .carousel-control-prev {
        left: 12px;
    }
    .carousel-control-next {
        right: 12px;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1);
    }

    /* --- INFO UTAMA --- */
    .venue-detail-info {
        padding: 20px 16px 0;
    }
    .venue-price-header {
        margin-bottom: 4px;
    }
    .price-amount {
        font-size: 28px;
        font-weight: 800;
        color: #0d6efd;
    }
    .price-unit {
        font-size: 13px;
        color: #6c757d;
        margin-left: 4px;
    }
    .venue-detail-name {
        font-size: 22px;
        font-weight: 700;
        color: #000;
        margin-bottom: 6px;
    }
    .venue-detail-location {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 8px;
    }
    .venue-detail-location i {
        font-size: 14px;
    }
    .venue-detail-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .meta-rating i {
        color: #f5c518;
        margin-right: 2px;
    }
    .meta-separator {
        color: #adb5bd;
        font-size: 8px;
    }
    .venue-divider {
        margin-top: 16px;
        margin-bottom: 0;
        border-color: #e9ecef;
    }

    /* --- SECTION UMUM --- */
    .venue-detail-section {
        padding: 20px 16px;
    }
    .section-title {
        font-weight: 700;
        font-size: 19px;
        color: #000;
        margin-bottom: 12px;
    }

    /* --- SPESIFIKASI --- */
    .spec-list {
        list-style: disc;
        padding-left: 20px;
        margin: 0;
    }
    .spec-list li {
        font-size: 14px;
        color: #212529;
        margin-bottom: 6px;
        line-height: 1.5;
    }

    /* --- FASILITAS --- */
    .facility-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px 12px;
        text-align: center;
        transition: 0.2s;
        height: 100%;
    }
    .facility-card:hover {
        border-color: #0d6efd;
        background: #f8f9ff;
    }
    .facility-icon {
        font-size: 26px;
        color: #0d6efd;
        margin-bottom: 8px;
        display: block;
    }
    .facility-label {
        font-size: 13px;
        color: #212529;
        font-weight: 500;
    }

    /* --- SPACER & STICKY BUTTON --- */
    .sticky-spacer {
        height: 90px;
    }
    .sticky-book-btn {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #0d6efd;
        padding: 12px 0;
        z-index: 1000;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.15);
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    // Update image counter saat carousel slide
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.getElementById('venueCarousel');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function (event) {
                const currentIndex = event.to + 1; // Bootstrap index 0-based
                document.getElementById('currentSlide').textContent = currentIndex;
            });
        }
    });
</script>
@endpush