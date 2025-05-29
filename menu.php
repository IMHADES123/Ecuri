<?php
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "IS_DB");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Obtener datos del usuario
$usuario_sesion = mysqli_real_escape_string($conexion, $_SESSION['usuario']);
$q = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario_sesion'");
if (!$q || mysqli_num_rows($q) === 0) {
    die("Usuario no encontrado.");
}
$usuario_data = mysqli_fetch_assoc($q);

// Cargar reportes existentes para notificaciones
$id_usuario = $usuario_data['id'];
$rpt = mysqli_query($conexion, "
    SELECT domicilio, colonia, codigo_postal, region, tipo_recoleccion, cantidad, estado
    FROM reportes_recoleccion
    WHERE id_usuario = $id_usuario
    ORDER BY fecha_reporte DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECURI - Plataforma Verde</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family:'Segoe UI',sans-serif; margin:0; background:#eafaf1; color:#2e7d32; }
        header { background:#388e3c; color:#fff; padding:1.5rem; text-align:center; position:relative; }
        header::after { content:"♻️"; font-size:2rem; position:absolute; right:1rem; top:1rem; }
        nav { background:#66bb6a; display:flex; justify-content:space-around; padding:1rem; flex-wrap:wrap; }
        nav button, nav a {
            background:#a5d6a7; border:none; color:#1b5e20;
            padding:.5rem 1rem; border-radius:10px; cursor:pointer; text-decoration:none;
            transition:background .3s;
        }
        nav button:hover, nav a:hover { background:#81c784; }
        section { padding:2rem; display:none; }
        section.active { display:block; }
        form input, form select, form textarea {
            width:100%; padding:.7rem; margin:1rem 0;
            border:1px solid #c8e6c9; border-radius:5px; background:#fff;
        }
        form button {
            background:#43a047; color:#fff; padding:.7rem 1.2rem;
            border:none; border-radius:5px; cursor:pointer;
        }
        .notificacion {
            background:#f1f8e9; border-left:6px solid #43a047;
            padding:1rem; margin-top:1rem; border-radius:6px;
        }
        h2::before { content:"🌱 "; }
        h3::before { content:"🍃 "; }
        ul { padding-left:1.2rem; }
    </style>
</head>
<body>

<header>
    <h1>ECURI - Plataforma Verde</h1>
</header>

<nav>
    <button onclick="mostrar('home')">🏡 Inicio</button>
    <button onclick="mostrar('perfil')">👤 Perfil</button>
    <button onclick="mostrar('reporte')">📝 Recolección</button>
    <button onclick="mostrar('notificaciones')">🔔 Notificaciones</button>
</nav>

<main>
    <!-- HOME -->
    <section id="home" class="active">
        <h2>Bienvenidos a ECURI</h2>
        <p>🌍 Somos una empresa enfocada en la recolección, clasificación y gestión de residuos con compromiso ecológico.</p>

        <h3>¿Quiénes Somos?</h3>
        <ul>
            <li>🌳 Más de 10 años de experiencia en sostenibilidad</li>
            <li>🚛 Operación eficiente y cobertura regional</li>
            <li>👷‍♂️ Personal técnico altamente capacitado</li>
        </ul>

        <h3>Objetivo de la Página Web</h3>
        <ul>
            <li>📢 Informar sobre nuestros servicios y valores ambientales</li>
            <li>📄 Agilizar la recolección de residuos mediante formularios inteligentes</li>
            <li>📡 Conectar directamente con usuarios y conductores</li>
            <li>🕒 Ofrecer un sistema de seguimiento en tiempo real</li>
            <li>🔔 Centralizar notificaciones y avisos importantes</li>
        </ul>

        <h3>Secciones Principales del Sitio Web</h3>
        <ul>
            <li>🟢 <strong>Inicio:</strong> Conoce nuestra empresa, noticias, proyectos y logros ambientales.</li>
            <li>🟡 <strong>Solicitar Recolección:</strong> Formulario digital para registrar solicitudes según tipo de residuo, ubicación y horario.</li>
            <li>🔵 <strong>Perfil del Usuario/Conductor:</strong> Visualiza tus datos personales, historial y estado de cuenta.</li>
            <li>🟠 <strong>Notificaciones:</strong> Actualizaciones del sistema sobre el estado de tus reportes.</li>
            <li>⚫ <strong>Reportes Asignados/Sin Asignar (conductores):</strong> Gestión de tareas pendientes y rutas.</li>
        </ul>

        <h3>Tipos de Residuos que Gestionamos</h3>
        <ul>
            <li>♻️ <strong>Reciclables:</strong> Plástico, cartón, papel, metales</li>
            <li>🛢️ <strong>Peligrosos:</strong> Químicos, aceites, baterías</li>
            <li>🗑️ <strong>Orgánicos:</strong> Restos alimenticios, residuos de jardín</li>
            <li>📦 <strong>Especiales:</strong> Electrónicos, muebles, escombros</li>
        </ul>

        <h3>Beneficios de Usar Nuestro Portal Web</h3>
        <ul>
            <li>✅ Solicitud rápida y fácil de recolecciones</li>
            <li>✅ Acceso a información en tiempo real</li>
            <li>✅ Mayor transparencia en el proceso</li>
            <li>✅ Seguridad en el manejo de datos personales</li>
            <li>✅ Impacto positivo en la comunidad y el medio ambiente</li>
        </ul>

        <h3>Seguridad y Privacidad</h3>
        <ul>
            <li>🔐 Sistema de autenticación para usuarios y conductores</li>
            <li>🔒 Datos protegidos conforme a normativas nacionales</li>
            <li>🛡️ Infraestructura segura para el manejo de información confidencial</li>
        </ul>

        <h3>Únete al Cambio con ECURI</h3>
        <p>En ECURI, cada acción cuenta. Nuestra plataforma es más que una herramienta: es una forma de contribuir activamente al desarrollo sostenible. Transformamos residuos en oportunidades para un planeta más limpio.</p>
    </section>

    <!-- PERFIL -->
    <section id="perfil">
        <h2>Perfil del Usuario</h2>
        <p><b>🆔 ID:</b> <?php echo $usuario_data['id']; ?></p>
        <p><b>👤 Nombre:</b> <?php echo htmlspecialchars($usuario_data['nombre_completo']); ?></p>
        <p><b>📧 Correo:</b> <?php echo htmlspecialchars($usuario_data['Correo']); ?></p>
        <p><b>👨‍💻 Usuario:</b> <?php echo htmlspecialchars($usuario_data['usuario']); ?></p>
        <p><b>📅 Registro:</b> <?php echo htmlspecialchars($usuario_data['fecha_registro'] ?: 'N/A'); ?></p>
    </section>

    <!-- REPORTE -->
    <section id="reporte">
        <h2>Generar Reporte de Recolección</h2>
        <form id="form-reporte">
            <input type="text"  name="domicilio" placeholder="📍 Domicilio" required>
            <input type="text"  name="colonia"   placeholder="🏘️ Colonia"   required>
            <input type="text"  name="cp"        placeholder="📮 Código Postal" required>
            <select name="region" required>
                <option value="">📌 Selecciona región</option>
                <option value="Villa de Alvarez">Villa de Álvarez</option>
                <option value="Colima">Colima</option>
                <option value="Comala">Comala</option>
            </select>
            <textarea name="tipo" placeholder="🗑️ Tipo de recolección" required></textarea>
            <input type="text"  name="cantidad" placeholder="⚖️ Cantidad (kg/l)" required>
            <button type="submit">🚛 Enviar Reporte</button>
        </form>
    </section>

    <!-- NOTIFICACIONES -->
    <section id="notificaciones">
        <h2>Notificaciones</h2>
        <div id="contenedor-notificaciones">
            <?php while($r = mysqli_fetch_assoc($rpt)): ?>
                <div class="notificacion">
                    <strong>♻️ Reporte:</strong><br>
                    <b>Domicilio:</b> <?php echo htmlspecialchars($r['domicilio']); ?><br>
                    <b>Colonia:</b> <?php echo htmlspecialchars($r['colonia']); ?><br>
                    <b>CP:</b> <?php echo htmlspecialchars($r['codigo_postal']); ?><br>
                    <b>Región:</b> <?php echo htmlspecialchars($r['region']); ?><br>
                    <b>Tipo:</b> <?php echo htmlspecialchars($r['tipo_recoleccion']); ?><br>
                    <b>Cantidad:</b> <?php echo htmlspecialchars($r['cantidad']); ?><br>
                    <b>Estado:</b> <?php echo htmlspecialchars($r['estado']); ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<script>
    function mostrar(id) {
        document.querySelectorAll('section').forEach(s => s.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }

    // Envío por AJAX y actualización de notificaciones
    document.getElementById('form-reporte').addEventListener('submit', function(e) {
        e.preventDefault();
        const data = new FormData(this);
        fetch('guardar_reporte.php', { method:'POST', body:data })
            .then(r => r.text())
            .then(txt => {
                if (txt.trim() === 'success') {
                    const noti = document.createElement('div');
                    noti.className = 'notificacion';
                    noti.innerHTML = `
                        <strong>♻️ Reporte enviado</strong><br>
                        <b>Estado:</b> pendiente
                    `;
                    document.getElementById('contenedor-notificaciones').prepend(noti);
                    this.reset();
                    mostrar('notificaciones');
                } else {
                    alert('Error al enviar reporte');
                }
            });
    });
</script>

</body>
</html>
