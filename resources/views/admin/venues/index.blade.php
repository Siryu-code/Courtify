@extends('layouts.admin')

@section('title', 'Court Management - Courtify Arena')

@section('content')
<div class="venues-wrapper">
    {{-- Header Row --}}
    <div class="venues-header-row">
        <h1 class="venues-title">Court Management</h1>
        <form method="GET" action="{{ route('admin.venues.index') }}" class="venues-search-form">
            <div class="input-icon-wrapper">
                <i class="fa-solid fa-search input-icon"></i>
                <input type="text" 
                       name="search" 
                       class="form-control venues-search-input" 
                       placeholder="Search courts..." 
                       value="{{ request('search') }}">
            </div>
        </form>
    </div>

    {{-- ======================== SECTION 1: COURTS OVERVIEW (HOMEPAGE) ======================== --}}
    <div class="venues-section">
        <div class="venues-section-header">
            <div>
                <h2 class="venues-section-title">Courts Overview (Homepage)</h2>
                <p class="venues-section-sub">Manage arena facilities and availability.</p>
            </div>
            <a href="{{ route('admin.venues.create') }}" class="btn btn-primary venues-add-btn">
                <i class="fa-solid fa-plus me-2"></i> Add New Court
            </a>
        </div>

        <div class="row g-4">
            @forelse($venues as $venue)
                @include('admin.venues._card', ['venue' => $venue])
            @empty
                <div class="col-12">
                    <div class="venues-empty">Belum ada venue terdaftar.</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Divider --}}
    <hr class="venues-divider">

    {{-- ======================== SECTION 2: COURTS OVERVIEW (ALL VENUE) ======================== --}}
    <div class="venues-section">
        <div class="venues-section-header">
            <div>
                <h2 class="venues-section-title">All Venues</h2>
                <p class="venues-section-sub">Manage arena facilities and availability.</p>
            </div>
            <a href="{{ route('admin.venues.create') }}" class="btn btn-primary venues-add-btn">
                <i class="fa-solid fa-plus me-2"></i> Add New Court
            </a>
        </div>

        <div class="row g-4">
            @forelse($venues as $venue)
                @include('admin.venues._card', ['venue' => $venue])
            @empty
                <div class="col-12">
                    <div class="venues-empty">Belum ada venue terdaftar.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .venues-wrapper { padding: 24px 28px; }
    .venues-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .venues-title {
        font-weight: 800;
        font-size: 26px;
        color: #111827;
        margin: 0;
    }
    .venues-search-form {
        min-width: 260px;
    }
    .venues-search-form .input-icon-wrapper {
        position: relative;
    }
    .venues-search-form .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
    }
    .venues-search-input {
        padding: 10px 16px 10px 42px;
        border-radius: 50px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        background: #fff;
    }
    .venues-search-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.08);
    }

    .venues-section { margin-bottom: 8px; }
    .venues-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .venues-section-title {
        font-weight: 700;
        font-size: 22px;
        color: #111827;
        margin-bottom: 2px;
    }
    .venues-section-sub {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }
    .venues-add-btn {
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    .venues-divider {
        margin: 28px 0;
        border-color: #e5e7eb;
    }
    .venues-empty {
        text-align: center;
        padding: 40px;
        color: #6b7280;
        background: #fff;
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
    }

    /* Venue Card */
    .venue-admin-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .venue-admin-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .venue-admin-img-wrap {
        position: relative;
    }
    .venue-admin-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .venue-admin-img-placeholder {
        width: 100%;
        height: 160px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 36px;
    }
    .venue-status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 50px;
        color: #fff;
    }
    .status-available { background: #22c55e; }
    .status-maintenance { background: rgba(255,255,255,0.9); color: #374151; }
    .status-in_use { background: #f59e0b; }
    .vip-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #0f172a;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        letter-spacing: 1px;
    }
    .venue-admin-body {
        padding: 14px 16px;
        flex: 1;
    }
    .venue-admin-name {
        font-weight: 700;
        font-size: 17px;
        color: #111827;
        margin-bottom: 2px;
    }
    .venue-admin-meta {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 6px;
    }
    .venue-admin-price {
        font-weight: 700;
        font-size: 18px;
        color: #111827;
    }
    .venue-admin-price small {
        font-weight: 400;
        font-size: 12px;
        color: #6b7280;
    }
    .venue-admin-actions {
        padding: 10px 16px 16px;
        display: flex;
        gap: 8px;
    }
    .venue-edit-btn {
        flex: 1;
        background: #f3f4f6;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        text-decoration: none;
        text-align: center;
        transition: 0.15s;
    }
    .venue-edit-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }
    .venue-delete-btn {
        background: #fee2e2;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        color: #dc2626;
        cursor: pointer;
        transition: 0.15s;
        font-size: 14px;
    }
    .venue-delete-btn:hover {
        background: #fecaca;
    }

    @media (max-width: 768px) {
        .venues-wrapper { padding: 16px; }
        .venues-title { font-size: 22px; }
        .venues-header-row { flex-direction: column; align-items: stretch; }
        .venues-search-form { min-width: auto; }
    }
</style>
@endpush