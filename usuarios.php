<?php
session_start();
include 'conexion.php';

if (isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $tipo = $_POST['tipo'];

    // Buscar usuario existente
    $consulta = mysqli_prepare($conexion, "SELECT * FROM USUARIO WHERE correo = ?");
    mysqli_stmt_bind_param($consulta, "s", $correo);
    mysqli_stmt_execute($consulta);
    $resultado = mysqli_stmt_get_result($consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        // Usuario ya existe, validar
        $usuario = mysqli_fetch_assoc($resultado);
        if ($usuario['contraseña'] === $contraseña && $usuario['tipo'] === $tipo) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['tipo'] = $usuario['tipo'];
            header("Location: " . ($tipo === 'cliente' ? "cliente.php" : "admin_dashboard.php"));
            exit;
        } else {
            $_SESSION['error'] = "Credenciales incorrectas. Verifica tu contraseña o tipo de usuario.";
            header("Location: index.php");
            exit;
        }
    } else {
        // Nuevo usuario → registrar
        $stmt = mysqli_prepare($conexion, "INSERT INTO USUARIO (nombre, correo, contraseña, tipo) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $correo, $contraseña, $tipo);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['id_usuario'] = mysqli_insert_id($conexion);
            $_SESSION['nombre'] = $nombre;
            $_SESSION['tipo'] = $tipo;
            header("Location: " . ($tipo === 'cliente' ? "cliente.php" : "admin_dashboard.php"));
            exit;
        } else {
            $_SESSION['error'] = "Error al registrar el usuario.";
            header("Location: index.php");
            exit;
        }
    }
}
?>
