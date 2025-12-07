<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <style>
            body {
                background-color: #f8f9fa;
            }
            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .auth-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                padding: 30px;
                width: 100%;
                max-width: 450px;
            }
            .logo-container {
                text-align: center;
                margin-bottom: 30px;
            }
            .logo-container img {
                max-width: 150px;
                height: auto;
            }
            .btn-primary {
                background-color: #d63384;
                border-color: #d63384;
                padding: 10px 20px;
                font-weight: 500;
            }
            .btn-primary:hover {
                background-color: #b82870;
                border-color: #b82870;
            }
            @media (max-width: 576px) {
                .auth-card {
                    padding: 20px;
                }
                .logo-container img {
                    max-width: 120px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card">
                <div class="logo-container">
                    <a href="/">
                        <img src="{{ asset('images/flogo.png') }}" alt="{{ config('app.name') }}" />
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
