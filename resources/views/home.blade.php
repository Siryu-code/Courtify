@extends('layouts.app')

@section('title', 'Home')

@section('content')

@include('partials.hamburger-menu')
{{-- ======================== HERO SECTION ======================== --}}
<section class="hero-section" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.65)), url('{{ asset('123.png') }}') center/cover no-repeat;">
    <div class="hero-content">
        <h1 class="hero-heading">Book Your Court Instantly</h1>
        <p class="hero-subtext">Cari dan pesan lapangan terdekat dengan jadwal real-time dan fasilitas premium</p>
        <form method="GET" action="{{ route('home') }}" id="search-form" class="hero-search">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Search venues, locations..."
                       value="{{ request('search') }}">
                {{-- Pertahankan sort aktif saat search --}}
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
            </div>
        </form>
    </div>
</section>

{{-- ======================== PROMO SECTION ======================== --}}
<section class="promo-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="promo-title">Promo Diskon Menarik</h2>
        <a href="{{ route('promos') }}" class="promo-link">&gt;</a>
    </div>

    @if($promotions->count() > 0)
        <div class="promo-carousel">
            @php
                $bgColors = ['#0f1e3c', '#0d6efd', '#198754', '#6f42c1'];
                $icons    = ['fa-fire', 'fa-clock', 'fa-bolt', 'fa-star'];
            @endphp
            @foreach($promotions as $promo)
                @php
                    $colorIndex = $loop->index % count($bgColors);
                    $bg         = $bgColors[$colorIndex];
                    $icon       = $icons[$colorIndex];
                @endphp
                <div class="promo-card" style="background: {{ $bg }};">
                    <div class="promo-icon-circle">
                        <i class="fa {{ $icon }}"></i>
                    </div>
                    <div class="promo-card-title">{{ $promo->title }}</div>
                    <div class="promo-discount">Diskon {{ $promo->discount }}%</div>
                    <div class="promo-desc">{{ $promo->description }}</div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted" style="font-size: 13px;">Tidak ada promo aktif saat ini.</p>
    @endif
</section>

{{-- ======================== FAQ SECTION ======================== --}}
<section class="faq-section">
    @php
        $faqItems = [
            ['question' => 'How do I book a court?',                          'answer' => 'You can easily book through our website by selecting your preferred venue, date, and time. Payment is completed online for instant confirmation.'],
            ['question' => 'What are the cancellation policies?',              'answer' => 'Cancellations made 24 hours before the booking time are fully refundable. Late cancellations may incur a small fee.'],
            ['question' => 'Do you provide equipment like balls or bibs?',     'answer' => 'Yes, we provide balls and bibs at most venues. You can check the venue details to see what equipment is included.'],
            ['question' => 'Are there locker rooms and showers available?',    'answer' => 'Many of our venues have locker rooms and hot showers. Look for the facilities icon on each venue page.'],
        ];
    @endphp

    <div class="accordion" id="faqAccordion">
        @foreach($faqItems as $index => $faq)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq{{ $index }}"
                            aria-expanded="false"
                            aria-controls="faq{{ $index }}">
                        {{ $faq['question'] }}
                    </button>
                </h2>
                <div id="faq{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">{{ $faq['answer'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ======================== VENUE SECTION ======================== --}}
<section id="venue-section" class="venue-section">

    {{-- Sort Bar --}}
    <div class="sort-bar mb-3">
        <form method="GET" action="{{ route('home') }}" id="sort-form">
            {{-- Pertahankan search aktif saat sort --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="name_asc"   {{ $sort === 'name_asc'   ? 'selected' : '' }}>A → Z</option>
                <option value="name_desc"  {{ $sort === 'name_desc'  ? 'selected' : '' }}>Z → A</option>
                <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Harga Termurah</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                <option value="rating" disabled>Rating (Coming Soon)</option>
            </select>
        </form>
    </div>

    @forelse($venues as $venue)
        @php
            $avgRating  = $venue->ratings->avg('rating');
            $firstImage = $venue->images->first();
            $imgSrc     = $firstImage
                            ? asset('storage/' . $firstImage->image_path)
                            : asset('images/placeholder.jpg');
        @endphp
        <div class="venue-card">
            <div class="venue-image-wrapper position-relative">
                <a href="{{ route('venue.show', $venue->id) }}">
                    <img src="{{ $imgSrc }}" alt="{{ $venue->name }}" class="venue-img">
                </a>
                <span class="badge-type">{{ ucfirst($venue->type) }}</span>
                <span class="badge-status badge-{{ $venue->status }}">
                    @if($venue->status === 'available')
                        ● Tersedia
                    @elseif($venue->status === 'in_use')
                        1 Jam saat ini penuh
                    @else
                        ● Dalam perbaikan
                    @endif
                </span>
            </div>
            <div class="venue-info">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="venue-name">{{ $venue->name }}</h5>
                    <span class="venue-rating">
                        ⭐ {{ $avgRating ? number_format($avgRating, 1) : 'N/A' }}
                    </span>
                </div>
                <p class="venue-location">
                    <i class="fa fa-location-dot"></i> {{ $venue->location }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="venue-price">
                        <strong>Rp{{ number_format($venue->price_per_hour, 0, ',', '.') }}</strong>
                        <span class="text-muted">/jam</span>
                    </div>
                    @auth
                        <a href="{{ route('booking.index', $venue->id) }}" class="btn btn-primary btn-sm book-btn">
                            Book Now
                        </a>
                    @else
                        <button type="button" class="btn btn-primary btn-sm book-btn"
                                data-bs-toggle="modal" data-bs-target="#loginModal">
                            Book Now
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fa fa-search fa-2x mb-2"></i>
            <p>Tidak ada venue yang ditemukan.</p>
        </div>
    @endforelse
</section>

{{-- ======================== MODAL LOGIN (untuk guest) ======================== --}}
@guest
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="loginModalLabel">Login Diperlukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa fa-lock fa-3x text-primary mb-3"></i>
                <p class="text-muted">Kamu perlu login terlebih dahulu untuk melakukan booking.</p>
            </div>
            <div class="modal-footer border-0 d-flex gap-2 justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary px-4">Daftar</a>
            </div>
        </div>
    </div>
</div>
@endguest

@include('partials.bottom-navbar')
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- Global --- */
    .main-content {
        padding-bottom: 70px;
    }

    /* --- TOP BAR --- */
    .top-bar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: #ffffff;
        padding: 12px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .top-bar-icon {
        font-size: 20px;
        color: #212529;
        margin-right: 12px;
        cursor: pointer;
    }
    .top-bar-brand {
        font-weight: 700;
        font-size: 18px;
        color: #000;
    }

    /* --- HERO --- */
    .hero-section {
        position: relative;
        padding: 48px 16px 32px;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .hero-content {
        width: 100%;
        text-align: center;
    }
    .hero-heading {
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
        margin-bottom: 8px;
    }
    .hero-subtext {
        font-size: 14px;
        color: rgba(255,255,255,0.85);
        margin-bottom: 20px;
    }
    .hero-search .input-group {
        max-width: 500px;
        margin: 0 auto;
    }
    .hero-search .form-control {
        border-radius: 0 8px 8px 0;
        padding: 12px 16px;
        font-size: 14px;
    }
    .hero-search .input-group-text {
        border-radius: 8px 0 0 8px;
        padding: 12px 16px;
    }

    /* --- PROMO --- */
    .promo-section {
        background: #fff;
        padding: 24px 16px;
    }
    .promo-title {
        font-weight: 600;
        font-size: 15px;
        color: #000;
        margin: 0;
    }
    .promo-link {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
    }
    .promo-carousel {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
    }
    .promo-carousel::-webkit-scrollbar {
        display: none;
    }
    .promo-card {
        flex: 0 0 200px;
        min-height: 130px;
        border-radius: 12px;
        padding: 14px;
        scroll-snap-align: start;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    .promo-icon-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .promo-card-title {
        font-weight: 600;
        font-size: 12px;
        opacity: 0.9;
        margin-top: 6px;
    }
    .promo-discount {
        font-weight: 700;
        font-size: 18px;
    }
    .promo-desc {
        font-size: 11px;
        opacity: 0.8;
        line-height: 1.3;
    }

    /* --- FAQ --- */
    .faq-section {
        background: #fff;
        padding: 24px 16px;
    }
    .accordion-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px !important;
        margin-bottom: 8px;
        overflow: hidden;
    }
    .accordion-button {
        font-size: 14px;
        font-weight: 500;
        color: #000;
        background: #fff;
    }
    .accordion-button:not(.collapsed) {
        background: #fff;
        color: #000;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: #e5e7eb;
    }

    /* --- VENUE --- */
    .venue-section {
        background: #f8f9fa;
        padding: 24px 16px;
    }
    .sort-bar .form-select {
        max-width: 200px;
        font-size: 13px;
    }
    .venue-card {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .venue-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }
    .venue-image-wrapper {
        position: relative;
    }
    .badge-type {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(0,0,0,0.55);
        color: #fff;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .badge-status {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 4px;
        color: #fff;
    }
    .badge-available    { background: #198754; }
    .badge-in_use       { background: #fd7e14; }
    .badge-maintenance  { background: #dc3545; }
    .venue-info {
        padding: 12px;
    }
    .venue-name {
        font-weight: 700;
        font-size: 16px;
        margin: 0;
    }
    .venue-rating {
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
    }
    .venue-location {
        font-size: 12px;
        color: #6c757d;
        margin: 4px 0 8px;
    }
    .venue-location i {
        margin-right: 4px;
    }
    .venue-price strong {
        font-size: 16px;
        color: #000;
    }
    .venue-price .text-muted {
        font-size: 12px;
    }
    .book-btn {
        padding: 8px 18px;
        font-size: 13px;
        border-radius: 6px;
    }

    /* --- BOTTOM NAVBAR --- */
    .bottom-navbar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        z-index: 999;
        display: flex;
        justify-content: space-evenly;
        padding: 6px 0;
        padding-bottom: env(safe-area-inset-bottom);
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #9ca3af;
        font-size: 10px;
        padding: 4px 0;
        transition: color 0.2s;
    }
    .nav-item i {
        font-size: 20px;
        margin-bottom: 2px;
    }
    .nav-item.active {
        color: #0d6efd;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .venue-section .row-venues {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .venue-card {
            margin-bottom: 0;
        }
    }
    @media (min-width: 992px) {
        .venue-section .row-venues {
            grid-template-columns: repeat(3, 1fr);
        }
        .hero-heading {
            font-size: 48px;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    // Auto-scroll ke venue section setelah search
    @if(request('search'))
    document.addEventListener('DOMContentLoaded', function () {
        const target = document.getElementById('venue-section');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    @endif

    // Smooth scroll untuk tombol Explore
    const exploreBtn = document.getElementById('exploreBtn');

    if (exploreBtn) {
        exploreBtn.addEventListener('click', function (e) {
            e.preventDefault();
    
            const target = document.getElementById('venue-section');

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }
</script>
@endpush