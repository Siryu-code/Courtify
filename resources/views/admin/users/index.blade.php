@extends('layouts.admin')

@section('title', 'Promo Management - Courtify Arena')

@section('content')
<div class="promos-admin-wrapper">
    {{-- Search Bar --}}
    <div class="promos-search-bar">
        <div class="input-icon-wrapper w-100">
            <i class="fa-solid fa-search input-icon"></i>
            <input type="text" 
                   class="form-control promos-search-input" 
                   placeholder="Search promos, users, or bookings...">
        </div>
    </div>

    {{-- Dua Panel --}}
    <div class="row g-4">
        {{-- Panel Kiri: Promo List --}}
        <div class="col-lg-7">
            <div class="promo-list-panel">
                <h1 class="promo-list-title">Promos & Discounts</h1>
                <p class="promo-list-sub">Manage active promotional codes and track usage.</p>

                @forelse($promotions as $promo)
                    @php
                        $isExpired = \Carbon\Carbon::parse($promo->end_date)->isPast();
                        $isActive = \Carbon\Carbon::parse($promo->start_date)->isPast() 
                                    && \Carbon\Carbon::parse($promo->end_date)->isFuture();
                        $statusLabel = $isExpired ? 'EXPIRED' : 'ACTIVE';
                        $statusClass = $isExpired ? 'expired' : 'active';
                    @endphp
                    <div class="promo-list-item {{ $isExpired ? 'dimmed' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="promo-list-icon {{ $isExpired ? 'bg-secondary-subtle' : 'bg-primary-subtle' }}">
                                <i class="fa-solid fa-tag {{ $isExpired ? 'text-secondary' : 'text-primary' }}"></i>
                            </div>
                            <div>
                                <div class="promo-list-name {{ $isExpired ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ strtoupper($promo->title) }}
                                </div>
                                <div class="promo-list-meta {{ $isExpired ? 'text-muted' : '' }}">
                                    {{ $promo->discount }}% OFF • Valid until {{ \Carbon\Carbon::parse($promo->end_date)->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <span class="badge rounded-pill promo-status-badge {{ $statusClass }}">
                            <span class="status-dot"></span> {{ $statusLabel }}
                        </span>
                    </div>
                @empty
                    <div class="promo-list-empty">
                        <i class="fa-solid fa-ticket-simple"></i>
                        <p>Belum ada promo. Buat promo baru di panel kanan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Panel Kanan: Create New Promo --}}
        <div class="col-lg-5">
            <div class="promo-form-panel">
                <div class="promo-form-header">
                    <h2 class="promo-form-title">Create New Promo</h2>
                    <p class="promo-form-sub">Configure discount rules and validity.</p>
                </div>
                <div class="promo-form-body">
                    <form method="POST" action="{{ route('admin.promos.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Promo Name --}}
                        <div class="mb-3">
                            <label class="promo-field-label">PROMO NAME</label>
                            <input type="text" 
                                   name="title" 
                                   class="form-control promo-field-input" 
                                   placeholder="e.g. WINTER25" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Type & Value --}}
                        <div class="row g-2 mb-3">
                            <div class="col-5">
                                <label class="promo-field-label">TYPE</label>
                                <select name="discount_type" class="form-select promo-field-input">
                                    <option value="percent">% Off</option>
                                    <option value="flat">Flat Discount</option>
                                </select>
                            </div>
                            <div class="col-7">
                                <label class="promo-field-label">VALUE</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-prefix">%</span>
                                    <input type="number" 
                                           name="discount" 
                                           class="form-control promo-field-input ps-5" 
                                           placeholder="20" 
                                           value="{{ old('discount') }}" 
                                           required>
                                </div>
                                @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Target Court --}}
                        <div class="mb-3">
                            <label class="promo-field-label">TARGET COURT</label>
                            <select name="target_court" class="form-select promo-field-input">
                                <option value="">All Courts</option>
                                @php $venues = \App\Models\Venue::orderBy('name')->get(); @endphp
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Date & End Date --}}
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="promo-field-label">START DATE</label>
                                <input type="date" 
                                       name="start_date" 
                                       class="form-control promo-field-input" 
                                       value="{{ old('start_date') }}" 
                                       required>
                                @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-6">
                                <label class="promo-field-label">END DATE</label>
                                <input type="date" 
                                       name="end_date" 
                                       class="form-control promo-field-input" 
                                       value="{{ old('end_date') }}" 
                                       required>
                                @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label class="promo-field-label">DESKRIPSI</label>
                            <input type="text" 
                                   name="description" 
                                   class="form-control promo-field-input" 
                                   placeholder="tuliskan deskripsi..." 
                                   value="{{ old('description') }}" 
                                   required>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Banner Image --}}
                        <div class="mb-3">
                            <label class="promo-field-label">BANNER IMAGE</label>
                            <input type="file" 
                                   name="banner_image" 
                                   class="form-control promo-field-input" 
                                   accept="image/*">
                            @error('banner_image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- First Booking Only --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" 
                                   name="is_first_booking" 
                                   class="form-check-input" 
                                   value="1" 
                                   id="firstBooking">
                            <label class="form-check-label promo-check-label" for="firstBooking">
                                Khusus First Booking
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary promo-submit-btn">
                            <i class="fa-solid fa-bullhorn me-2"></i> Publish Promo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .promos-admin-wrapper { padding: 24px 28px; }

    /* Search */
    .promos-search-bar {
        margin-bottom: 24px;
    }
    .input-icon-wrapper { position: relative; }
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        z-index: 5;
    }
    .promos-search-input {
        padding: 12px 16px 12px 44px;
        border-radius: 50px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        background: #fff;
    }

    /* Panel Kiri */
    .promo-list-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 24px;
        min-height: 400px;
    }
    .promo-list-title {
        font-weight: 800;
        font-size: 26px;
        color: #111827;
        margin-bottom: 2px;
    }
    .promo-list-sub {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
    }
    .promo-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: 0.15s;
    }
    .promo-list-item.dimmed {
        opacity: 0.55;
    }
    .promo-list-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }
    .bg-primary-subtle { background: #dbeafe; }
    .bg-secondary-subtle { background: #e5e7eb; }
    .promo-list-name {
        font-weight: 700;
        font-size: 17px;
        color: #111827;
        margin-bottom: 2px;
    }
    .promo-list-meta {
        font-size: 12px;
        color: #6b7280;
    }
    .promo-status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 14px;
    }
    .promo-status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    .promo-status-badge.expired {
        background: #e5e7eb;
        color: #6b7280;
    }
    .status-dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        margin-right: 5px;
    }
    .active .status-dot { background: #22c55e; }
    .expired .status-dot { background: #9ca3af; }
    .promo-list-empty {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
        font-size: 36px;
    }
    .promo-list-empty p {
        font-size: 14px;
        margin-top: 10px;
    }

    /* Panel Kanan */
    .promo-form-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: 24px;
    }
    .promo-form-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
    }
    .promo-form-title {
        font-weight: 700;
        font-size: 20px;
        color: #111827;
        margin-bottom: 2px;
    }
    .promo-form-sub {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .promo-form-body {
        padding: 20px 24px;
    }
    .promo-field-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }
    .promo-field-input {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        width: 100%;
    }
    .promo-field-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.08);
    }
    .input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: #6b7280;
        z-index: 5;
    }
    .promo-check-label {
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }
    .promo-submit-btn {
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        margin-top: 8px;
    }

    @media (max-width: 992px) {
        .promo-form-panel {
            position: static;
            margin-top: 20px;
        }
    }
    @media (max-width: 768px) {
        .promos-admin-wrapper { padding: 16px; }
        .promo-list-title { font-size: 22px; }
    }
</style>
@endpush