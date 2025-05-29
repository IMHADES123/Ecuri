<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'conexion_be.php';

// Sanitizar entradas
$nombre_completo = trim($_POST['nombre_completo']);
$Correo = filter_var(trim($_POST['Correo']), FILTER_SANITIZE_EMAIL);
$usuario = trim($_POST['usuario']);
$contrasena = $_POST['contrasena'];

// Validación básica
if (empty($nombre_completo) || empty($Correo) || empty($usuario) || empty($contrasena)) {
    die("Todos los campos son obligatorios.");
}

// Validación de email
if (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo electrónico no válido.");
}

// Verificar si ya existe el usuario o correo
$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario' OR Correo='$Correo'");
if (mysqli_num_rows($verificar_usuario) > 0) {
    die("El usuario o correo ya está registrado.");
}

// Encriptar la contraseña
$contrasena_segura = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar datos
$query = "INSERT INTO usuarios(nombre_completo, Correo, usuario, contrasena) 
          VALUES ('$nombre_completo', '$Correo', '$usuario', '$contrasena_segura')";

$ejecutar = mysqli_query($conexion, $query);

if ($ejecutar) {
    echo '
        <script>
            alert("Usuario registrado con éxito.");
            window.location="../index.php";
        </script>
    ';
} else {
    echo "Error al registrar: " . mysqli_error($conexion);
}
?>
