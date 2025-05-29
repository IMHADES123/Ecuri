<?php
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "IS_DB");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si el conductor ha iniciado sesión
if (!isset($_SESSION['conductor'])) {
    header("Location: ../index.php");
    exit;
}

$usuario_conductor = mysqli_real_escape_string($conexion, $_SESSION['conductor']);

// Obtener datos del conductor
$query = "SELECT * FROM conductores WHERE usuario = '$usuario_conductor'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    $conductor_data = mysqli_fetch_assoc($resultado);
} else {
    echo "Conductor no encontrado.";
    exit;
}

$no_conductor = $conductor_data['no_conductor'];

// Obtener reportes sin asignar
$query_sin_asignar = "SELECT * FROM reportes_recoleccion WHERE id_conductor IS NULL OR id_conductor = 0";
$resultado_sin_asignar = mysqli_query($conexion, $query_sin_asignar);

// Obtener reportes asignados a este conductor
$query_asignados = "SELECT * FROM reportes_recoleccion WHERE id_conductor = $no_conductor";
$resultado_asignados = mysqli_query($conexion, $query_asignados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECURI - Menú Conductor</title>
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
            content: "🚛♻️";
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
        nav button, nav a {
            background-color: #a5d6a7;
            border: none;
            color: #1b5e20;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        nav button:hover, nav a:hover {
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
        ul, ol, table {
            padding-left: 1.2rem;
            margin-top: 0;
            border-collapse: collapse;
            width: 100%;
        }
        table th, table td {
            border: 1px solid #81c784;
            padding: 0.5rem;
            text-align: left;
        }
        table th {
            background-color: #a5d6a7;
        }
        button.accion {
            background-color: #4caf50;
            border: none;
            color: white;
            padding: 0.3rem 0.7rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button.accion:hover {
            background-color: #388e3c;
        }
        h2::before {
            content: "🌿 ";
        }
        h3::before {
            content: "🍃 ";
        }
        p, li {
            line-height: 1.5;
        }
    </style>
</head>
<body>

<header>
    <h1>ECURI - Bienvenido Conductor</h1>
</header>

<nav>
    <button onclick="mostrar('inicio')">🏡 Inicio</button>
    <button onclick="mostrar('perfil')">👤 Perfil</button>
    <button onclick="mostrar('sin_asignar')">📋 Sin Asignar</button>
    <button onclick="mostrar('asignados')">✅ Asignados</button>
    <!-- Opción de cerrar sesión eliminada -->
</nav>

<main>
    <!-- INICIO -->
    <section id="inicio" class="active">
        <h2>Panel de Conductor</h2>
        <p>En esta plataforma, como <strong>conductor</strong> tienes a tu disposición cuatro secciones principales:</p>
        <ul>
            <li><strong>Perfil:</strong> Tus datos personales y capacidad de camión.</li>
            <li><strong>Sin Asignar:</strong> Reportes disponibles que puedes tomar.</li>
            <li><strong>Asignados:</strong> Reportes que ya aceptaste, con opción de marcarlos como cumplidos.</li>
            <li><strong>Notificaciones:</strong> Pronto recibirás novedades aquí.</li>
        </ul>
        <p>Usa el menú superior para navegar entre estas secciones. ¡Manos a la obra! 🚛♻️</p>
    </section>

    <!-- PERFIL -->
    <section id="perfil">
        <h2>Perfil del Conductor</h2>
        <ul>
            <li><strong>🆔 Nº Conductor:</strong> <?php echo htmlspecialchars($conductor_data['no_conductor']); ?></li>
            <li><strong>👤 Nombre:</strong> <?php echo htmlspecialchars($conductor_data['nombre_completo']); ?></li>
            <li><strong>📧 Correo:</strong> <?php echo htmlspecialchars($conductor_data['correo_electronico']); ?></li>
            <li><strong>🚚 Tipo de Camión:</strong> <?php echo htmlspecialchars($conductor_data['tipo_camion']); ?></li>
            <li><strong>⚖️ Capacidad:</strong> <?php echo htmlspecialchars($conductor_data['capacidad']); ?> kg</li>
        </ul>
    </section>

    <!-- SIN ASIGNAR -->
    <section id="sin_asignar">
        <h2>Reportes Sin Asignar</h2>
        <?php if(mysqli_num_rows($resultado_sin_asignar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dirección</th>
                        <th>Tipo de Recolección</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($reporte = mysqli_fetch_assoc($resultado_sin_asignar)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reporte['id']); ?></td>
                        <td>
                            <?php
                                echo htmlspecialchars($reporte['domicilio']) . ', ' .
                                     htmlspecialchars($reporte['colonia']) . ', CP ' .
                                     htmlspecialchars($reporte['codigo_postal']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($reporte['tipo_recoleccion']); ?></td>
                        <td>
                            <form method="POST" action="asignar_reporte.php" style="margin:0;">
                                <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
                                <button type="submit" class="accion">Tomar servicio</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay reportes sin asignar en este momento.</p>
        <?php endif; ?>
    </section>

    <!-- ASIGNADOS -->
    <section id="asignados">
        <h2>Reportes Asignados</h2>
        <?php if(mysqli_num_rows($resultado_asignados) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dirección</th>
                        <th>Tipo de Recolección</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($reporte = mysqli_fetch_assoc($resultado_asignados)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reporte['id']); ?></td>
                        <td>
                            <?php
                                echo htmlspecialchars($reporte['domicilio']) . ', ' .
                                     htmlspecialchars($reporte['colonia']) . ', CP ' .
                                     htmlspecialchars($reporte['codigo_postal']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($reporte['tipo_recoleccion']); ?></td>
                        <td><?php echo htmlspecialchars($reporte['estado']); ?></td>
                        <td>
                            <?php if($reporte['estado'] !== 'cumplido'): ?>
                            <form method="POST" action="completar_reporte.php" style="margin:0;">
                                <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
                                <button type="submit" class="accion">Marcar como cumplido</button>
                            </form>
                            <?php else: ?>
                            <span>✔️</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes reportes asignados en este momento.</p>
        <?php endif; ?>
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
