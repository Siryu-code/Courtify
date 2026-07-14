@extends('layouts.app')

@section('title', 'Booking History - Courtify Arena')

@section('content')
{{-- ======================== INCLUDE PARTIALS ======================== --}}
@include('partials.topbar')
@include('partials.hamburger-menu')

{{-- ======================== PAGE HEADER ======================== --}}
<div class="history-header">
    <h1 class="history-title">Booking History</h1>
</div>
<div class="history-divider"></div>

{{-- ======================== BOOKING LIST ======================== --}}
<div class="history-container">
    @auth
        @forelse($bookings ?? [] as $booking)
            @php
                $venue = $booking->venue;
                $firstImage = $venue->images->first();
                $imagePath = $firstImage ? $firstImage->image_path : null;
                
                $statusClasses = match($booking->status) {
                    'pending' => 'status-pending',
                    'confirmed' => 'status-confirmed',
                    'completed' => 'status-completed',
                    default => ''
                };
            @endphp

            <div class="booking-card {{ $statusClasses }}">
                {{-- Gambar Venue --}}
                <div class="booking-card-image">
                    @if($imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}" 
                             alt="{{ $venue->name }}" 
                             class="booking-venue-img">
                    @else
                        <div class="booking-venue-placeholder">
                            <i class="fa-solid fa-image text-muted"></i>
                        </div>
                    @endif
                </div>

                {{-- Info Booking --}}
                <div class="booking-card-body">
                    {{-- Nama Venue + Harga --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h3 class="booking-venue-name">{{ $venue->name }}</h3>
                        <span class="badge rounded-pill booking-price-badge {{ $booking->status === 'completed' ? 'text-decoration-line-through bg-light text-muted' : 'bg-primary-subtle text-primary' }}">
                            Rp{{ number_format($venue->price_per_hour, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Lokasi --}}
                    <p class="booking-location">
                        <i class="fa-solid fa-location-dot"></i> {{ $venue->location }}
                    </p>

                    {{-- Tanggal & Jam --}}
                    <div class="booking-datetime">
                        <div class="booking-datetime-item">
                            <i class="fa-regular fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</span>
                        </div>
                        <div class="booking-datetime-item">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Area --}}
                <div class="booking-card-actions">
                    @if($booking->status === 'pending')
                        <div class="booking-status-badge pending">
                            <i class="fa-regular fa-clock me-2"></i> Pending...
                        </div>
                    @elseif($booking->status === 'confirmed')
                        <div class="d-flex gap-2">
                            <a href="{{ route('history.detail', $booking->id) }}" 
                               class="btn btn-dark flex-fill booking-action-btn">
                                View Details
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-secondary flex-fill booking-action-btn cancel-btn"
                                    data-booking-id="{{ $booking->id }}">
                                Cancel...
                            </button>
                        </div>
                    @elseif($booking->status === 'completed')
                        <div class="d-flex flex-column gap-2">
                            <a href="#" class="btn btn-outline-secondary booking-action-btn rate-btn" 
                               data-booking-id="{{ $booking->id }}" 
                               data-venue-id="{{ $venue->id }}">
                                Beri Rating
                            </a>
                            <a href="{{ route('booking.index', ['venue_id' => $venue->id]) }}" 
                               class="btn btn-outline-secondary booking-action-btn">
                                Rebook
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="history-empty-state">
                <div class="history-empty-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="history-empty-title">Belum Ada Riwayat Booking</h3>
                <p class="history-empty-desc">
                    Kamu belum memiliki riwayat pemesanan. Yuk, pesan lapangan pertamamu sekarang!
                </p>
                <a href="{{ route('home') }}#venue-section" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold">
                    Cari Lapangan
                </a>
            </div>
        @endforelse
    @else
        {{-- Guest State --}}
        <div class="history-empty-state">
            <div class="history-empty-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h3 class="history-empty-title">Login untuk Melihat Riwayat</h3>
            <p class="history-empty-desc">
                Kamu harus login terlebih dahulu untuk melihat riwayat booking kamu.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold">
                Login Sekarang
            </a>
        </div>
    @endauth
</div>

{{-- ======================== BOTTOM SPACER ======================== --}}
<div class="bottom-spacer"></div>

{{-- ======================== INCLUDE BOTTOM NAVBAR ======================== --}}
@include('partials.bottom-navbar')
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- PAGE HEADER --- */
    .history-header {
        padding: 20px 20px 12px;
        background: #fff;
    }
    .history-title {
        font-weight: 700;
        font-size: 24px;
        color: #000;
        margin: 0;
    }
    .history-divider {
        border-bottom: 1px solid #e5e7eb;
        margin: 0 20px;
    }

    /* --- CONTAINER --- */
    .history-container {
        padding: 20px;
    }

    /* --- BOOKING CARD --- */
    .booking-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: 0.2s;
    }
    .booking-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    /* Card Dim untuk Completed */
    .booking-card.status-completed {
        opacity: 0.65;
        filter: grayscale(0.4);
    }
    .booking-card.status-completed:hover {
        opacity: 0.85;
        filter: grayscale(0.15);
    }

    /* --- IMAGE --- */
    .booking-card-image {
        position: relative;
    }
    .booking-venue-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }
    .booking-venue-placeholder {
        width: 100%;
        height: 180px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
    }

    /* --- BODY --- */
    .booking-card-body {
        padding: 16px;
    }
    .booking-venue-name {
        font-weight: 700;
        font-size: 18px;
        color: #111827;
        margin: 0;
        flex: 1;
        margin-right: 10px;
    }
    .booking-price-badge {
        font-size: 13px;
        font-weight: 700;
        padding: 5px 14px;
        white-space: nowrap;
    }
    .bg-primary-subtle {
        background-color: #dbeafe !important;
    }
    .text-primary {
        color: #1d4ed8 !important;
    }
    .booking-location {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .booking-location i {
        font-size: 13px;
    }
    .booking-datetime {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
    }
    .booking-datetime-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
    }
    .booking-datetime-item i {
        font-size: 13px;
    }

    /* --- ACTIONS --- */
    .booking-card-actions {
        padding: 12px 16px 16px;
        border-top: 1px solid #f3f4f6;
    }
    .booking-status-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
        padding: 10px;
        border-radius: 8px;
        width: 100%;
    }
    .booking-action-btn {
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
    }
    .btn-dark {
        background: #111827;
        border-color: #111827;
    }
    .btn-dark:hover {
        background: #1f2937;
        border-color: #1f2937;
    }
    .cancel-btn {
        border-color: #d1d5db;
        color: #374151;
    }
    .cancel-btn:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }
    .rate-btn {
        border-color: #d1d5db;
        color: #374151;
    }
    .rate-btn:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    /* --- EMPTY STATE --- */
    .history-empty-state {
        text-align: center;
        padding: 60px 24px;
        background: #fff;
        border: 2px dashed #d1d5db;
        border-radius: 20px;
    }
    .history-empty-icon {
        font-size: 52px;
        color: #9ca3af;
        margin-bottom: 18px;
    }
    .history-empty-title {
        font-weight: 700;
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
    }
    .history-empty-desc {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 22px;
        max-width: 320px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    /* --- SPACER --- */
    .bottom-spacer {
        height: 80px;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .history-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .booking-datetime {
            gap: 24px;
        }
    }
    @media (min-width: 1024px) {
        .history-container {
            max-width: 900px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .booking-card {
            margin-bottom: 0;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cancel button
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function () {
                const bookingId = this.getAttribute('data-booking-id');
                if (confirm('Apakah kamu yakin ingin membatalkan booking ini?')) {
                    // Arahkan ke endpoint cancel (belum ada route, placeholder)
                    alert('Fitur pembatalan akan segera tersedia. Booking ID: ' + bookingId);
                }
            });
        });

        // Rate button
        document.querySelectorAll('.rate-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-booking-id');
                const venueId = this.getAttribute('data-venue-id');
                // Arahkan ke modal rating atau halaman rating
                alert('Fitur rating akan segera tersedia.\nBooking ID: ' + bookingId + '\nVenue ID: ' + venueId);
            });
        });
    });
</script>
@endpush