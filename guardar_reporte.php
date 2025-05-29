<?php
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "IS_DB");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    die("error: no autenticado");
}

// Obtener el ID del usuario en sesión
$usuario = mysqli_real_escape_string($conexion, $_SESSION['usuario']);
$res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario'");
if (!$res || mysqli_num_rows($res) === 0) {
    die("error: usuario no encontrado");
}
$dataUser   = mysqli_fetch_assoc($res);
$id_usuario = (int) $dataUser['id']; // Convertimos a entero por seguridad

// Recoger y sanitizar datos del formulario
$domicilio = mysqli_real_escape_string($conexion, $_POST['domicilio']);
$colonia   = mysqli_real_escape_string($conexion, $_POST['colonia']);
$cp        = mysqli_real_escape_string($conexion, $_POST['cp']);
$region    = mysqli_real_escape_string($conexion, $_POST['region']);
$tipo      = mysqli_real_escape_string($conexion, $_POST['tipo']);
$cantidad  = (int) $_POST['cantidad']; // Nos aseguramos de que sea un número

// Insertar en la tabla reportes_recoleccion
$sql = "INSERT INTO reportes_recoleccion
        (domicilio, colonia, codigo_postal, region, tipo_recoleccion, cantidad, id_usuario)
        VALUES
        ('$domicilio', '$colonia', '$cp', '$region', '$tipo', $cantidad, $id_usuario)";

if (mysqli_query($conexion, $sql)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($conexion);
}
?>
