<?php
$conexion = mysqli_connect("localhost", "root", "", "is_db");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
