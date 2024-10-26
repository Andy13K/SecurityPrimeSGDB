<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SecurityPrime</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .alert {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            animation: slideLeft 0.5s ease forwards;
            z-index: 1000;
        }

        .alert-success { background-color: var(--success-color); }
        .alert-danger { background-color: var(--danger-color); }
        .alert-warning { background-color: var(--warning-color); }
        .alert-info { background-color: var(--info-color); }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.5s ease forwards;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section img {
            width: 150px;
            height: auto;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .logo-section h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            color: #1f2937;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-input:focus + i {
            color: var(--primary-color);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .remember-me label {
            color: #6b7280;
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #9ca3af;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid #e5e7eb;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 0.25rem;
        }

        .links a:hover {
            color: var(--secondary-color);
            transform: translateY(-1px);
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-message i {
            font-size: 0.9rem;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideLeft {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive Styles */
        @media (max-width: 480px) {
            .login-container {
                margin: 0.5rem;
                padding: 1.5rem;
            }

            .logo-section img {
                width: 120px;
            }

            .logo-section h1 {
                font-size: 1.5rem;
            }

            .form-input {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="login-container">
        <div class="logo-section">
            <img src="{{ asset('image/SGPLOGO.jpeg') }}" alt="SecurityPrime Logo">
            <h1>Iniciar Sesión</h1>
        </div>

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-group">
                    <input id="email" 
                           type="email" 
                           class="form-input @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus>
                    <i class="fas fa-envelope"></i>
                </div>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-group">
                    <input id="password" 
                           type="password" 
                           class="form-input @error('password') is-invalid @enderror" 
                           name="password" 
                           required 
                           autocomplete="current-password">
                    <i class="fas fa-lock"></i>
                </div>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="remember-me">
                <input type="checkbox" 
                       name="remember" 
                       id="remember" 
                       {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
            </button>

            <div class="divider">
                <span>o</span>
            </div>

            <div class="links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                    </a>
                @endif
                <br><br>
                <a href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i> ¿No tienes cuenta? Regístrate
                </a>
            </div>
        </form>
    </div>

    <script>
        // Remover alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            // Validación básica de email
            if (!email.includes('@') || !email.includes('.')) {
                isValid = false;
                // Mostrar error
            }

            // Validación básica de contraseña
            if (password.length < 6) {
                isValid = false;
                // Mostrar error
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>