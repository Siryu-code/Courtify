@extends('layouts.auth')

@section('title', 'Sign In - Courtify Arena')

@section('content')
<div class="auth-container">
    {{-- Close Button --}}
    <a href="{{ route('home') }}" class="auth-close-btn">
        <i class="fa-solid fa-xmark"></i>
    </a>

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-brand">
            <i class="fa-solid fa-futbol auth-brand-icon"></i>
            <span>Courtify Arena</span>
        </div>
        <p class="auth-subtitle">Sign in to manage your bookings</p>
    </div>

    {{-- Form Card --}}
    <div class="auth-card">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Email Address --}}
            <div class="mb-3">
                <label for="email" class="form-label auth-label">Email Address</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control auth-input @error('email') is-invalid @enderror" 
                           placeholder="athlete@example.com" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label auth-label">Password</label>
                </div>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control auth-input @error('password') is-invalid @enderror" 
                           placeholder="••••••••" 
                           required>
                    <button type="button" class="password-toggle" data-target="password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="mb-3 form-check mt-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label auth-check-label" for="remember">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-auth-primary w-100 mt-2">
                Sign In
            </button>
        </form>
    </div>

    {{-- Footer Link --}}
    <p class="auth-footer-text">
        Don't have an account? 
        <a href="{{ route('register') }}" class="auth-footer-link">Sign up</a>
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