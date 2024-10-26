<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - SecurityPrime</title>
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

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            margin: 1rem;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.5s ease forwards;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
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

        .password-requirements {
            background: #f9fafb;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .password-requirements h4 {
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.25rem 0;
        }

        .requirement i {
            font-size: 0.8rem;
        }

        .requirement.valid {
            color: var(--success-color);
        }

        .requirement.invalid {
            color: var(--danger-color);
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
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

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .links a:hover {
            color: var(--secondary-color);
            transform: translateY(-1px);
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 0.5rem;
                padding: 1.5rem;
            }

            .logo-section img {
                width: 120px;
            }

            .logo-section h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo-section">
            <img src="{{ asset('image/SGPLOGO.jpeg') }}" alt="SecurityPrime Logo">
            <h1>Crear Cuenta</h1>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    <div class="form-group">
        <label for="name">Nombre Completo</label>
        <div class="input-group">
            <input id="name" 
                   type="text" 
                   class="form-input @error('name') is-invalid @enderror" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autocomplete="name" 
                   autofocus
                   placeholder="Ingrese su nombre completo">
            <i class="fas fa-user"></i>
        </div>
        @error('name')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

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
                   placeholder="ejemplo@correo.com">
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
                   autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres">
            <i class="fas fa-lock"></i>
        </div>
        <div class="password-requirements">
            <h4>La contraseña debe contener:</h4>
            <div class="requirement" data-requirement="length">
                <i class="fas fa-times-circle"></i>
                <span>Mínimo 8 caracteres</span>
            </div>
            <div class="requirement" data-requirement="uppercase">
                <i class="fas fa-times-circle"></i>
                <span>Al menos una mayúscula</span>
            </div>
            <div class="requirement" data-requirement="number">
                <i class="fas fa-times-circle"></i>
                <span>Al menos un número</span>
            </div>
        </div>
        @error('password')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password-confirm">Confirmar Contraseña</label>
        <div class="input-group">
            <input id="password-confirm" 
                   type="password" 
                   class="form-input" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password"
                   placeholder="Repita su contraseña">
            <i class="fas fa-lock"></i>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" id="submitBtn">
        <i class="fas fa-user-plus"></i>
        Crear Cuenta
    </button>

    <div class="divider">
        <span>o</span>
    </div>

    <div class="links">
        <a href="{{ route('login') }}">
            <i class="fas fa-sign-in-alt"></i>
            ¿Ya tienes cuenta? Inicia sesión
        </a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar que todos los campos estén llenos
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password-confirm').value;

            if (!name || !email || !password || !passwordConfirm) {
                alert('Por favor, complete todos los campos');
                return;
            }

            // Validar contraseña
            if (password.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres');
                return;
            }

            if (password !== passwordConfirm) {
                alert('Las contraseñas no coinciden');
                return;
            }

            // Si todo está bien, enviar el formulario
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando cuenta...';
            submitBtn.disabled = true;
            this.submit();
        });
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password-confirm');
            const submitBtn = document.getElementById('submitBtn');
            const requirements = {
                length: { regex: /.{8,}/, element: document.querySelector('[data-requirement="length"]') },
                uppercase: { regex: /[A-Z]/, element: document.querySelector('[data-requirement="uppercase"]') },
                number: { regex: /[0-9]/, element: document.querySelector('[data-requirement="number"]') }
            };

            function updateRequirements(password) {
                let valid = true;
                for (const [key, requirement] of Object.entries(requirements)) {
                    const isValid = requirement.regex.test(password);
                    const icon = requirement.element.querySelector('i');
                    
                    requirement.element.classList.toggle('valid', isValid);
                    requirement.element.classList.toggle('invalid', !isValid);
                    icon.className = isValid ? 'fas fa-check-circle' : 'fas fa-times-circle';
                    
                    valid = valid && isValid;
                }
                return valid;
            }

            function validatePasswords() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const requirementsValid = updateRequirements(password);
                const passwordsMatch = password === confirmPassword && confirmPassword !== '';
                const matchMessage = document.getElementById('password-match-message');
                
                matchMessage.style.display = confirmPassword !== '' && !passwordsMatch ? 'flex' : 'none';
                submitBtn.disabled = !(requirementsValid && passwordsMatch);
            }

            passwordInput.addEventListener('input', validatePasswords);
            confirmPasswordInput.addEventListener('input', validatePasswords);

            // Validación del formulario
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            function validateForm() {
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (name.length < 3) {
                    showError('name', 'El nombre debe tener al menos 3 caracteres');
                    return false;
                }

                if (!email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
                    showError('email', 'Por favor ingrese un email válido');
                    return false;
                }

                if (password !== confirmPassword) {
                    showError('password-confirm', 'Las contraseñas no coinciden');
                    return false;
                }

                return true;
            }

            function showError(inputId, message) {
                const input = document.getElementById(inputI
                function showError(inputId, message) {
                const input = document.getElementById(inputId);
                const errorDiv = input.parentElement.nextElementSibling;
                if (!errorDiv.classList.contains('error-message')) {
                    const newErrorDiv = document.createElement('div');
                    newErrorDiv.className = 'error-message';
                    newErrorDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <span>${message}</span>
                    `;
                    input.parentElement.parentElement.insertBefore(newErrorDiv, input.parentElement.nextElementSibling);
                } else {
                    errorDiv.querySelector('span').textContent = message;
                    errorDiv.style.display = 'flex';
                }
                
                input.classList.add('is-invalid');
                setTimeout(() => {
                    const errorMessage = input.parentElement.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.style.display = 'none';
                    }
                    input.classList.remove('is-invalid');
                }, 5000);
            }

            // Validación en tiempo real del email
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                if (!this.value.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
                    showError('email', 'Por favor ingrese un email válido');
                }
            });

            // Validación en tiempo real del nombre
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('blur', function() {
                if (this.value.length < 3) {
                    showError('name', 'El nombre debe tener al menos 3 caracteres');
                }
            });

            // Efecto de carga al enviar el formulario
            document.getElementById('registerForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = `
                    <i class="fas fa-spinner fa-spin"></i>
                    Creando cuenta...
                `;
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>