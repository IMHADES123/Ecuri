<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Register - MagtimusPro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <!--Formulario de Login y Registro-->
            <div class="contenedor__login-register">
                <!-- Login -->
                <form action="PHP/login_usuario_be.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" placeholder="Correo Electrónico" name="Correo">
                    <input type="password" placeholder="Contraseña" name="contrasena">
                    <button type="submit">Entrar</button>
                </form>
                <!-- Register -->
                <form action="PHP/registro_usuario_be.php" method="post" class="formulario__register">
                <input type="text" name="nombre_completo" required placeholder="Nombre completo">
                <input type="email" name="Correo" required placeholder="Correo electrónico">
                <input type="text" name="usuario" required placeholder="Usuario">
                <input type="password" name="contrasena" required placeholder="Contraseña">
                 <button type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>
