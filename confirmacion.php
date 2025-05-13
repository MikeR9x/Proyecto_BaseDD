<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener productos del carrito
$consulta = mysqli_query($conexion,
    "SELECT c.id_producto, c.cantidad, p.precio, p.stock
     FROM carrito c
     JOIN producto p ON c.id_producto = p.id_producto
     WHERE c.id_usuario = $id_usuario");

if (mysqli_num_rows($consulta) === 0) {
    $_SESSION['error'] = "El carrito está vacío.";
    header("Location: ver_carrito.php");
    exit;
}

// Calcular total del pedido
$total = 0;
$carrito = [];
while ($item = mysqli_fetch_assoc($consulta)) {
    $subtotal = $item['cantidad'] * $item['precio'];
    $total += $subtotal;
    $carrito[] = $item;
}

// Crear pedido
$stmt = mysqli_prepare($conexion, "INSERT INTO pedido (id_usuario, total) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "id", $id_usuario, $total);
mysqli_stmt_execute($stmt);
$id_pedido = mysqli_insert_id($conexion);

// Insertar en detalle_pedido y actualizar stock
foreach ($carrito as $item) {
    $id_producto = $item['id_producto'];
    $cantidad = $item['cantidad'];
    $precio = $item['precio'];

    // Insertar detalle
    $stmt_detalle = mysqli_prepare($conexion,
        "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
         VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_detalle, "iiid", $id_pedido, $id_producto, $cantidad, $precio);
    mysqli_stmt_execute($stmt_detalle);

    // Actualizar stock
    $nuevo_stock = $item['stock'] - $cantidad;
    mysqli_query($conexion, "UPDATE producto SET stock = $nuevo_stock WHERE id_producto = $id_producto");
}

// Vaciar carrito
mysqli_query($conexion, "DELETE FROM carrito WHERE id_usuario = $id_usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h4>✅ ¡Pedido confirmado con éxito!</h4>
        <p>Tu número de pedido es: <strong>#<?= $id_pedido ?></strong></p>
        <a href="cliente.php" class="btn btn-outline-primary mt-3">← Volver al catálogo</a>
    </div>
</div>

</body>
</html>
