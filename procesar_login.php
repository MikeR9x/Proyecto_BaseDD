<?php
session_start();
include 'conexion.php';

if (isset($_POST['correo'], $_POST['contraseña'], $_POST['tipo'])) {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $tipo = $_POST['tipo'];

    // Buscar usuario con ese correo y tipo
    $consulta = mysqli_prepare($conexion, "SELECT * FROM USUARIO WHERE correo = ? AND tipo = ?");
    mysqli_stmt_bind_param($consulta, "ss", $correo, $tipo);
    mysqli_stmt_execute($consulta);
    $resultado = mysqli_stmt_get_result($consulta);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if ($usuario['contraseña'] === $contraseña) {
            // Credenciales correctas → redirigir según tipo
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['tipo'] = $usuario['tipo'];

            header("Location: " . ($tipo === 'cliente' ? "cliente.php" : "admin_dashboard.php"));
            exit;
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error'] = "No se encontró ninguna cuenta con ese correo y tipo.";
    }

    header("Location: index.php");
    exit;
} else {
    $_SESSION['error'] = "Faltan datos para iniciar sesión.";
    header("Location: index.php");
    exit;
}
