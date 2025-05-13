<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

if (isset($_POST['id_producto'], $_POST['cantidad'])) {
    $id_producto = $_POST['id_producto'];
    $cantidad_solicitada = (int) $_POST['cantidad'];

    // Consultar stock actual
    $consulta_stock = mysqli_prepare($conexion, "SELECT stock FROM producto WHERE id_producto = ?");
    mysqli_stmt_bind_param($consulta_stock, "i", $id_producto);
    mysqli_stmt_execute($consulta_stock);
    $resultado_stock = mysqli_stmt_get_result($consulta_stock);
    $producto = mysqli_fetch_assoc($resultado_stock);

    if (!$producto) {
        $_SESSION['error'] = "Producto no encontrado.";
        header("Location: cliente.php");
        exit;
    }

    $stock_disponible = $producto['stock'];

    // Consultar si ya está en el carrito
    $consulta_carrito = mysqli_prepare($conexion,
        "SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
    mysqli_stmt_bind_param($consulta_carrito, "ii", $id_usuario, $id_producto);
    mysqli_stmt_execute($consulta_carrito);
    $resultado_carrito = mysqli_stmt_get_result($consulta_carrito);

    $cantidad_actual = 0;
    if ($fila = mysqli_fetch_assoc($resultado_carrito)) {
        $cantidad_actual = $fila['cantidad'];
    }

    $nueva_cantidad_total = $cantidad_actual + $cantidad_solicitada;

    if ($nueva_cantidad_total > $stock_disponible) {
        $_SESSION['error'] = "No puedes agregar más de $stock_disponible unidades. Ya tienes $cantidad_actual en el carrito.";
        header("Location: cliente.php");
        exit;
    }

    if ($cantidad_actual > 0) {
        // Actualizar cantidad
        $stmt = mysqli_prepare($conexion,
            "UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_producto = ?");
        mysqli_stmt_bind_param($stmt, "iii", $cantidad_solicitada, $id_usuario, $id_producto);
    } else {
        // Insertar nuevo producto en el carrito
        $stmt = mysqli_prepare($conexion,
            "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_producto, $cantidad_solicitada);
    }

    mysqli_stmt_execute($stmt);
    $_SESSION['mensaje'] = "Producto agregado al carrito.";
    header("Location: cliente.php");
    exit;
}
?>
