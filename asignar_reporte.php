<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "IS_DB");
if (!$conexion) {
    http_response_code(500);
    exit('Error de conexión a la base de datos');
}

if (!isset($_SESSION['conductor'])) {
    http_response_code(401);
    exit('Error: conductor no autenticado');
}

$usuario = mysqli_real_escape_string($conexion, $_SESSION['conductor']);
$res = mysqli_query($conexion, "SELECT no_conductor FROM conductores WHERE usuario = '$usuario'");
if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    exit('Error: conductor no encontrado');
}
$data = mysqli_fetch_assoc($res);
$no_conductor = intval($data['no_conductor']);

$id_reporte = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id_reporte <= 0) {
    http_response_code(400);
    exit('Error: ID de reporte inválido');
}

$sql = "UPDATE reportes_recoleccion
        SET estado = 'trabajando', id_conductor = $no_conductor
        WHERE id = $id_reporte AND (id_conductor IS NULL OR id_conductor = 0) AND estado = 'pendiente'";

if (mysqli_query($conexion, $sql) && mysqli_affected_rows($conexion) > 0) {
    // Redirigir a menu2.php mostrando sección asignados
    header("Location: menu2.php#asignados");
    exit;
} else {
    http_response_code(400);
    echo "Error: no se pudo asignar el reporte";
}

mysqli_close($conexion);
?>
