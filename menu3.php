<?php
session_start();

// Verificar sesión de administrador
if (!isset($_SESSION['administrador'])) {
    header("Location: ../index.php");
    exit;
}

$conexion = mysqli_connect("localhost", "root", "", "IS_DB");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Procesar envío del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre_completo']);
    $correo = trim($_POST['correo_electronico']);
    $usuario = trim($_POST['usuario']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo_camion'];
    $capacidad = trim($_POST['capacidad']);

    $insert = "INSERT INTO conductores (nombre_completo, correo_electronico, usuario, contrasena, tipo_camion, capacidad)
               VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $insert);
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $correo, $usuario, $contrasena, $tipo, $capacidad);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Conductor añadido exitosamente.";
    } else {
        $mensaje = "Error al añadir conductor: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECURI - Menú Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #eafaf1;
            color: #2e7d32;
        }
        header {
            background-color: #388e3c;
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        header::after {
            content: "🛠️🌱";
            font-size: 2rem;
            position: absolute;
            right: 1rem;
            top: 1rem;
        }
        nav {
            background-color: #66bb6a;
            display: flex;
            justify-content: space-around;
            padding: 1rem;
            flex-wrap: wrap;
        }
        nav button {
            background-color: #a5d6a7;
            border: none;
            color: #1b5e20;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        nav button:hover {
            background-color: #81c784;
        }
        main {
            padding: 2rem;
        }
        section {
            display: none;
        }
        section.active {
            display: block;
        }
        form {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        input, select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #a5d6a7;
            border-radius: 8px;
        }
        button[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }
        button[type="submit"]:hover {
            background-color: #388e3c;
        }
        .mensaje {
            background-color: #c8e6c9;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header>
    <h1>ECURI - Panel del Administrador</h1>
</header>

<nav>
    <button onclick="mostrar('inicio')">🏡 Inicio</button>
    <button onclick="mostrar('agregar')">👷 Añadir Trabajador</button>
</nav>

<main>
    <!-- INICIO -->
    <section id="inicio" class="active">
        <h2>Bienvenido Administrador</h2>
        <p>Desde este panel puedes añadir nuevos trabajadores o conductores al sistema.</p>
    </section>

    <!-- FORMULARIO DE NUEVO CONDUCTOR -->
    <section id="agregar">
        <h2>Formulario para Añadir Conductor</h2>

        <?php if (isset($mensaje)) echo "<div class='mensaje'>" . htmlspecialchars($mensaje) . "</div>"; ?>

        <form method="POST" action="">
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" name="nombre_completo" required>

            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" name="correo_electronico" required>

            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <label for="tipo_camion">Tipo de Camión:</label>
            <select name="tipo_camion" required>
                <option value="grande">Grande</option>
                <option value="mediano">Mediano</option>
                <option value="chico">Chico</option>
            </select>

            <label for="capacidad">Capacidad (kg o litros):</label>
            <input type="text" name="capacidad" required>

            <button type="submit">➕ Añadir Conductor</button>
        </form>
    </section>
</main>

<script>
    function mostrar(seccion) {
        document.querySelectorAll('main section').forEach(s => s.classList.remove('active'));
        document.getElementById(seccion).classList.add('active');
    }
</script>

</body>
</html>
