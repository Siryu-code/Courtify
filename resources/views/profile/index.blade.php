@extends('layouts.app')

@section('title', 'Profile - Courtify Arena')

@section('content')
{{-- ======================== INCLUDE PARTIALS ======================== --}}
@include('partials.topbar')
@include('partials.hamburger-menu')

{{-- ======================== HEADER (Back + Title) ======================== --}}
<div class="profile-header">
    <button type="button" onclick="history.back()" class="profile-back-btn">
        <i class="fa-solid fa-arrow-left"></i>
    </button>
    <h1 class="profile-header-title" id="profileTitle">Profile</h1>
    <div class="profile-back-btn invisible">
        <i class="fa-solid fa-arrow-left"></i>
    </div>
</div>

{{-- ======================== VIEW MODE ======================== --}}
<div id="viewMode">
    {{-- Profile Summary --}}
    <div class="profile-summary">
        <div class="profile-avatar-wrapper">
            @if($user->profile_photo)
                <img src="{{ Storage::url($user->profile_photo) }}" 
                     alt="Avatar" class="profile-avatar-img">
            @else
                <div class="profile-avatar-placeholder">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <h2 class="profile-name">{{ $user->name }}</h2>
        <p class="profile-email">{{ $user->email }}</p>
        <p class="profile-phone-masked">
            {{ substr($user->phone, 0, 3) }}************
        </p>
        <button type="button" class="btn btn-outline-dark profile-edit-btn" id="editProfileBtn">
            Edit Profile
        </button>
    </div>

    {{-- Total Booking Card --}}
    <div class="profile-booking-card">
        <div class="profile-booking-count">{{ $totalBooking }}</div>
        <div class="profile-booking-label">Bookings</div>
    </div>

    {{-- Menu List --}}
    <div class="profile-menu-card">
        {{-- Payment Methods (Static) --}}
        <div class="profile-menu-item" data-bs-toggle="modal" data-bs-target="#paymentModal">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-menu-icon bg-light rounded-2">
                    <i class="fa-solid fa-credit-card text-primary"></i>
                </div>
                <span class="profile-menu-label">Payment Methods</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted"></i>
        </div>

        {{-- Booking History --}}
        <a href="{{ route('history') }}" class="profile-menu-item text-decoration-none">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-menu-icon bg-light rounded-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                </div>
                <span class="profile-menu-label">Booking History</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted"></i>
        </a>
    </div>

    {{-- Logout Button --}}
    <form method="POST" action="{{ route('logout') }}" class="profile-logout-form">
        @csrf
        <button type="submit" class="btn btn-outline-dark profile-logout-btn">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> LOG OUT
        </button>
    </form>
</div>

{{-- ======================== EDIT MODE (Hidden by Default) ======================== --}}
<div id="editMode" style="display: none;">
    {{-- Avatar Section --}}
    <div class="edit-avatar-section">
        <div class="edit-avatar-preview">
            @if($user->profile_photo)
                <img src="{{ Storage::url($user->profile_photo) }}" 
                     alt="Avatar" class="edit-avatar-img" id="avatarPreview">
            @else
                <div class="edit-avatar-placeholder" id="avatarPlaceholder">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="edit-avatar-info">
            <h3 class="edit-name-text">{{ $user->name }}</h3>
            <button type="button" class="btn btn-light edit-photo-btn" id="changePhotoBtn">
                <i class="fa-solid fa-camera me-1"></i> Ubah Foto
            </button>
            <input type="file" id="profilePhotoInput" name="profile_photo" accept="image/*" hidden>
        </div>
    </div>

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-edit-form" id="editProfileForm">
        @csrf
        @method('PATCH')
        
        {{-- Hidden file input clone (real submission) --}}
        <input type="file" name="profile_photo" id="realPhotoInput" accept="image/*" style="display: none;">

        <div class="edit-form-card">
            {{-- Nama Lengkap --}}
            <div class="edit-field-group">
                <label class="edit-field-label">Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" 
                           name="name" 
                           class="form-control edit-field-input @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Alamat Email --}}
            <div class="edit-field-group">
                <label class="edit-field-label">Alamat Email</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" 
                           name="email" 
                           class="form-control edit-field-input @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="edit-field-group">
                <label class="edit-field-label">Nomor Telepon</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="text" 
                           name="phone" 
                           class="form-control edit-field-input @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $user->phone) }}" 
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary edit-save-btn">
            Simpan Perubahan
        </button>
        <button type="button" class="btn btn-outline-secondary edit-cancel-btn" id="cancelEditBtn">
            Batal
        </button>
    </form>
</div>

{{-- ======================== PAYMENT METHODS MODAL (Static) ======================== --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3 border rounded-3 p-3">
                        <i class="fa-solid fa-building-columns text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold">Bank Transfer</div>
                            <div class="text-muted small">BCA, Mandiri, BNI, BRI</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 border rounded-3 p-3">
                        <i class="fa-solid fa-wallet text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold">E-Wallet</div>
                            <div class="text-muted small">GoPay, OVO, Dana, ShopeePay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== BOTTOM SPACER ======================== --}}
<div class="bottom-spacer"></div>

{{-- ======================== INCLUDE BOTTOM NAVBAR ======================== --}}
@include('partials.bottom-navbar')
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- PROFILE HEADER --- */
    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }
    .profile-back-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #212529;
        cursor: pointer;
        padding: 0;
        width: 32px;
        text-align: left;
    }
    .profile-header-title {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin: 0;
    }

    /* --- VIEW MODE - PROFILE SUMMARY --- */
    .profile-summary {
        text-align: center;
        padding: 28px 16px 20px;
    }
    .profile-avatar-wrapper {
        margin-bottom: 12px;
        display: inline-block;
    }
    .profile-avatar-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f3f4f6;
    }
    .profile-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-size: 36px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #f3f4f6;
        margin: 0 auto;
    }
    .profile-name {
        font-weight: 700;
        font-size: 20px;
        color: #000;
        margin-bottom: 4px;
    }
    .profile-email,
    .profile-phone-masked {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    .profile-edit-btn {
        margin-top: 12px;
        padding: 8px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
    }

    /* --- TOTAL BOOKING CARD --- */
    .profile-booking-card {
        margin: 0 16px 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .profile-booking-count {
        font-size: 30px;
        font-weight: 800;
        color: #0d6efd;
    }
    .profile-booking-label {
        font-size: 13px;
        color: #6b7280;
        margin-top: 2px;
    }

    /* --- MENU LIST --- */
    .profile-menu-card {
        margin: 0 16px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .profile-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        cursor: pointer;
        transition: 0.15s;
        color: #212529;
    }
    .profile-menu-item:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }
    .profile-menu-item:hover {
        background: #f9fafb;
    }
    .profile-menu-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .profile-menu-label {
        font-size: 15px;
        font-weight: 500;
    }

    /* --- LOGOUT --- */
    .profile-logout-form {
        padding: 24px 16px;
    }
    .profile-logout-btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.5px;
        border-color: #d1d5db;
        color: #374151;
    }
    .profile-logout-btn:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    /* --- EDIT MODE --- */
    .edit-avatar-section {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px 20px 16px;
    }
    .edit-avatar-preview {
        flex-shrink: 0;
    }
    .edit-avatar-img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }
    .edit-avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e5e7eb;
    }
    .edit-avatar-info {
        flex: 1;
    }
    .edit-name-text {
        font-weight: 700;
        font-size: 18px;
        color: #000;
        margin-bottom: 8px;
    }
    .edit-photo-btn {
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 500;
    }

    /* --- EDIT FORM --- */
    .profile-edit-form {
        padding: 0 20px;
    }
    .edit-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .edit-field-group {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .edit-field-group:last-child {
        border-bottom: none;
    }
    .edit-field-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 6px;
        display: block;
    }
    .edit-field-input {
        border: none;
        padding: 8px 8px 8px 36px;
        border-radius: 0;
        font-size: 15px;
        background: transparent;
        border-bottom: 1px solid #e5e7eb;
    }
    .edit-field-input:focus {
        box-shadow: none;
        border-bottom-color: #0d6efd;
    }
    .input-icon-wrapper {
        position: relative;
    }
    .input-icon {
        position: absolute;
        left: 4px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        z-index: 5;
        pointer-events: none;
    }
    .edit-save-btn {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
        margin-top: 28px;
    }
    .edit-cancel-btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        margin-top: 10px;
        margin-bottom: 24px;
    }

    /* --- SPACER --- */
    .bottom-spacer {
        height: 80px;
    }

    /* --- RESPONSIVE --- */
    @media (min-width: 768px) {
        .profile-booking-card,
        .profile-menu-card,
        .profile-logout-form,
        .profile-edit-form {
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .profile-summary {
            max-width: 400px;
            margin: 0 auto;
        }
        .edit-avatar-section {
            max-width: 600px;
            margin: 0 auto;
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');
        const profileTitle = document.getElementById('profileTitle');
        const editProfileBtn = document.getElementById('editProfileBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const changePhotoBtn = document.getElementById('changePhotoBtn');
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const realPhotoInput = document.getElementById('realPhotoInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');

        // Toggle ke Edit Mode
        editProfileBtn.addEventListener('click', function () {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            profileTitle.textContent = 'Edit Profile';
        });

        // Toggle ke View Mode
        cancelEditBtn.addEventListener('click', function () {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            profileTitle.textContent = 'Profile';
        });

        // Trigger file input untuk ubah foto
        changePhotoBtn.addEventListener('click', function () {
            profilePhotoInput.click();
        });

        // Preview foto baru + sync ke real input
        profilePhotoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                // Transfer file ke hidden input yang akan disubmit
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                realPhotoInput.files = dataTransfer.files;

                // Preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                    } else if (avatarPlaceholder) {
                        // Ganti placeholder dengan img
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Avatar';
                        img.className = 'edit-avatar-img';
                        img.id = 'avatarPreview';
                        avatarPlaceholder.parentNode.replaceChild(img, avatarPlaceholder);
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Tampilkan success message kalau ada
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif
    });
</script>
@endpush