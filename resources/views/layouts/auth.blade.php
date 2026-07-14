<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Courtify Arena')</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Auth Styles --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .auth-container {
            position: relative;
            width: 100%;
            max-width: 440px;
            padding: 20px 0;
        }

        /* --- CLOSE BUTTON --- */
        .auth-close-btn {
            position: absolute;
            top: 12px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 18px;
            text-decoration: none;
            transition: 0.2s;
            z-index: 10;
        }
        .auth-close-btn:hover {
            background: #f3f4f6;
            color: #111;
        }

        /* --- HEADER --- */
        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .auth-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            color: #000;
            margin-bottom: 6px;
        }
        .auth-brand-icon {
            font-size: 26px;
            color: #0d6efd;
        }
        .auth-title-navy {
            font-size: 26px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 6px;
        }
        .auth-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        /* --- CARD --- */
        .auth-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        /* --- LABEL & INPUT --- */
        .auth-label {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        .auth-forgot-link {
            font-size: 13px;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-forgot-link:hover {
            text-decoration: underline;
        }
        .auth-check-label {
            font-size: 14px;
            color: #374151;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Input dengan icon */
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
            z-index: 5;
            pointer-events: none;
        }
        .auth-input {
            padding: 12px 14px 12px 42px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            transition: 0.2s;
            background: #fff;
        }
        .auth-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        }
        .auth-input.is-invalid {
            border-color: #dc3545;
        }
        .auth-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            z-index: 5;
            padding: 0;
            line-height: 1;
        }
        .password-toggle:hover {
            color: #6b7280;
        }

        /* --- BUTTONS --- */
        .btn-auth-primary {
            background-color: #0d6efd;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 0;
            border-radius: 10px;
            transition: 0.2s;
        }
        .btn-auth-primary:hover {
            background-color: #0b5ed7;
        }
        .btn-auth-navy {
            background-color: #1e3a8a;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 0;
            border-radius: 10px;
            transition: 0.2s;
        }
        .btn-auth-navy:hover {
            background-color: #1e40af;
        }

        /* --- FOOTER --- */
        .auth-footer-text {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 20px;
            margin-bottom: 0;
        }
        .auth-footer-link {
            color: #0d6efd;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer-link:hover {
            text-decoration: underline;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 480px) {
            body {
                align-items: flex-start;
                padding: 12px;
            }
            .auth-container {
                margin-top: 10px;
            }
            .auth-card {
                padding: 20px 16px;
                border-radius: 14px;
            }
            .auth-brand {
                font-size: 20px;
            }
            .auth-title-navy {
                font-size: 22px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>