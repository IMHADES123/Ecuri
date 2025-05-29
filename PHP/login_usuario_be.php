<?php
session_start(); // Iniciar la sesión

include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Correo']) && isset($_POST['contrasena'])) {
    $entrada = trim($_POST['Correo']);        // Puede ser correo o usuario
    $contrasena = trim($_POST['contrasena']);

    // -------- PRIMERA BUSQUEDA: Tabla usuarios --------
    $stmt = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE Correo = ? OR usuario = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $entrada, $entrada);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);
            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario'] = $usuario['usuario'];
                header("Location: ../menu.php");
                exit;
            } else {
                echo '<script>alert("Contraseña incorrecta para usuario."); window.location="../index.php";</script>';
                exit;
            }
        }
        mysqli_stmt_close($stmt);
    }

    // -------- SEGUNDA BUSQUEDA: Tabla conductores --------
    $stmt = mysqli_prepare($conexion, "SELECT * FROM conductores WHERE correo_electronico = ? OR usuario = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $entrada, $entrada);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $conductor = mysqli_fetch_assoc($resultado);
            if (password_verify($contrasena, $conductor['contrasena'])) {
                $_SESSION['conductor'] = $conductor['usuario'];
                header("Location: ../menu2.php");
                exit;
            } else {
                echo '<script>alert("Contraseña incorrecta para conductor."); window.location="../index.php";</script>';
                exit;
            }
        }
        mysqli_stmt_close($stmt);
    }

    // -------- TERCERA BUSQUEDA: Tabla administrador --------
    $stmt = mysqli_prepare($conexion, "SELECT * FROM administrador WHERE correo_electronico = ? OR usuario = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $entrada, $entrada);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $admin = mysqli_fetch_assoc($resultado);
            if (password_verify($contrasena, $admin['contrasena'])) {
                $_SESSION['administrador'] = $admin['usuario'];
                header("Location: ../menu3.php");
                exit;
            } else {
                echo '<script>alert("Contraseña incorrecta para administrador."); window.location="../index.php";</script>';
                exit;
            }
        } else {
            echo '<script>alert("Correo o usuario no registrado en ninguna cuenta."); window.location="../index.php";</script>';
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error en la consulta de administrador: " . mysqli_error($conexion);
        exit;
    }
} else {
    echo '<script>alert("No se recibieron datos del formulario."); window.location="../index.php";</script>';
    exit;
}
?>
