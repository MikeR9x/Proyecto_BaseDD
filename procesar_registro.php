<?php
session_start();
include 'conexion.php';

if (isset($_POST['nombre'], $_POST['correo'], $_POST['contraseña'], $_POST['tipo'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $tipo = $_POST['tipo'];

    // Verificar si ya existe un usuario con ese correo y tipo
    $consulta = mysqli_prepare($conexion, "SELECT * FROM USUARIO WHERE correo = ? AND tipo = ?");
    mysqli_stmt_bind_param($consulta, "ss", $correo, $tipo);
    mysqli_stmt_execute($consulta);
    $resultado = mysqli_stmt_get_result($consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['error'] = "El correo ya está registrado con ese tipo de usuario.";
        header("Location: registro.php");
        exit;
    }

    // Insertar nuevo usuario
    $stmt = mysqli_prepare($conexion, "INSERT INTO USUARIO (nombre, correo, contraseña, tipo) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $correo, $contraseña, $tipo);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['id_usuario'] = mysqli_insert_id($conexion);
        $_SESSION['nombre'] = $nombre;
        $_SESSION['tipo'] = $tipo;

        header("Location: " . ($tipo === 'cliente' ? "index.php" : "index.php"));
        exit;
    } else {
        $_SESSION['error'] = "Error al registrar el usuario. Intenta nuevamente.";
        header("Location: registro.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Faltan datos en el formulario.";
    header("Location: registro.php");
    exit;
}
?>
