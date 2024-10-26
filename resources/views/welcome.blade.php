<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecurityPrimeSGDP</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales del cuerpo */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: whitesmoke;
            color: #4a5568;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden; /* Evita el desplazamiento durante las animaciones */
            position: relative;
        }

        /* Aplicando opacidad a la imagen de fondo */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('image/camaras.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.3; /* Valor ajustado para que la imagen sea más transparente */
            z-index: -1; /* Coloca la imagen detrás del contenido */
        }
        
        /* Estilos del contenedor principal */
        .container {
            background-color: #fff; /* Sin transparencia */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 40px 40px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            margin: 10px 30px;
            border: 2px solid #a0d1f1; /* Borde en celeste claro */
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: 0; /* Asegura que empieza oculto */
            animation: fade-in 1.5s ease-out forwards;
            position: relative;
            z-index: 1; /* Coloca el contenedor sobre la imagen de fondo */
        }

        /* Estilos para la opacidad inicial */
        .logo, .title, .login-form, .buttons {
            opacity: 0; /* Asegura que los elementos empiezan ocultos */
        }

        /* Animaciones y estilo del logo */
        .logo {
            padding: 5px 10px;
            animation: slide-in-down 1.5s ease-out forwards, fade-in 1.5s ease-out forwards;
            animation-delay: 0.2s;
        }

        /* Estilos y animaciones del título */
        .title {
            font-size: 38px;
            font-weight: 900;
            color: #7fbfff; /* Celeste claro */
            margin-bottom: 30px;
            animation: slide-in-left 1.5s ease-out forwards, fade-in 1.5s ease-out forwards;
            animation-delay: 0.4s;
            text-shadow: 2px 2px 0px #4fa1eb; /* Tercera sombra */

        }

        /* Contenedor y animación de la descripción */
        .description-container {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        /* Estilos de la descripción */
        .description {
            font-size: 18px;
            color: #000000;
            white-space: nowrap;
            overflow: hidden;
            border-right: 3px solid #000; /* Efecto del cursor */
            width: 100%;
            visibility: visible; /* Asegura que sea visible */
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        /* Formulario de inicio de sesión */
        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slide-in-up 1.5s ease-out forwards, fade-in 1.5s ease-out forwards;
            animation-delay: 1s;
            width: 100%; /* Asegura que coincida con el ancho de otros elementos */
            margin-bottom: 20px;
        }

        /* Estilos de los campos de entrada del formulario */
        .login-form input {
            width: 40%; /* Ancho más pequeño */
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #a0d1f1;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif;
        }

        /* Estilos de los botones */
        .buttons {
            animation: slide-in-up 1.5s ease-out forwards, fade-in 1.5s ease-out forwards;
            animation-delay: 1.2s;
            margin-top: 20px;
        }

        /* Estilos de los enlaces tipo botón */
        .buttons a {
            background-color: #7fbfff; /* Celeste claro */
            color: #ffffff;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 650;
            transition: background-color 0.3s, transform 0.3s;
            margin: 0 10px;
        }

        /* Cambio de color en hover */
        .buttons a:hover {
            background-color: #4fa1eb; /* Celeste más oscuro */
        }

        /* Efecto de clic */
        .buttons a:active {
            transform: scale(0.95);
        }

        /* Estilos del pie de página */
        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 10px; /* Añade un pequeño margen debajo del pie */
            left: 0;
            text-align: center;
            font-size: 14px;
        }

        /* Animación de desplazamiento hacia arriba */
        @keyframes slide-in-up {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Animación de desplazamiento hacia abajo */
        @keyframes slide-in-down {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Animación de desplazamiento hacia la izquierda */
        @keyframes slide-in-left {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Animación de desvanecimiento */
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Efecto de escritura */
        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        /* Parpadeo del cursor */
        @keyframes blink-caret {
            from, to {
                border-color: transparent;
            }
            50% {
                border-color: black;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="{{ asset('image/SGPLOGO.jpeg') }}" alt="Logo" width="200">
    </div>
    <div class="title">SECURITY PRIME SGDP</div>
    <div class="description-container">
        <div class="description">Gestiona tus proyectos de manera eficiente.</div>
    </div>

    <div class="buttons">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/home') }}">Ir a Inicio</a>
            @else
                <a href="{{ route('login') }}" class="login-button">Iniciar Sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="register-button">Registrarse</a>
                @endif
            @endauth
        @endif
    </div>
</div>
<div class="footer">
    &copy; SecurityPrimeSGDP. Todos los derechos reservados.
</div>

</body>
</html>
