@extends('layouts.auth')

@section('title', 'Gabung Courtify')

@section('content')
<div class="auth-container">
    {{-- Close Button --}}
    <form method="POST" action="{{ route('register.post') }}">
        <i class="fa-solid fa-xmark"></i>
    </a>

    {{-- Header --}}
    <div class="auth-header">
        <h1 class="auth-title-navy">Gabung Courtify</h1>
        <p class="auth-subtitle">Mulai booking lapangan favoritmu dengan mudah.</p>
    </div>

    {{-- Form Card --}}
    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Full Name --}}
            <div class="mb-3">
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" 
                           name="name" 
                           class="form-control auth-input @error('name') is-invalid @enderror" 
                           placeholder="Full Name" 
                           value="{{ old('name') }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Email Address --}}
            <div class="mb-3">
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" 
                           name="email" 
                           class="form-control auth-input @error('email') is-invalid @enderror" 
                           placeholder="Email Address" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Phone Number --}}
            <div class="mb-3">
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="tel" 
                           name="phone" 
                           class="form-control auth-input @error('phone') is-invalid @enderror" 
                           placeholder="Phone Number" 
                           value="{{ old('phone') }}" 
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control auth-input @error('password') is-invalid @enderror" 
                           placeholder="Password" 
                           required>
                    <button type="button" class="password-toggle" data-target="password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control auth-input @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Confirm Password" 
                           required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-auth-navy w-100 mt-3">
                Daftar Sekarang
            </button>
        </form>
    </div>

    {{-- Footer Link --}}
    <p class="auth-footer-text">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="auth-footer-link">Masuk di sini</a>
    </p>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush