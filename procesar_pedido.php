<?php
include("conexion.php");
session_start();
$id_usuario = $_SESSION['id_usuario'];

// Calcular total
$total_query = mysqli_query($conexion, "
    SELECT SUM(p.precio * c.cantidad) AS total
    FROM CARRITO c
    JOIN PRODUCTO p ON c.id_producto = p.id_producto
    WHERE c.id_usuario = $id_usuario
");

$total = mysqli_fetch_assoc($total_query)['total'];

if ($total > 0) {
    // Crear pedido
    mysqli_query($conexion, "INSERT INTO PEDIDO (id_usuario, total) VALUES ($id_usuario, $total)");
    $id_pedido = mysqli_insert_id($conexion);

    // Insertar detalles del pedido
    $detalles = mysqli_query($conexion, "
        SELECT c.id_producto, c.cantidad, p.precio
        FROM CARRITO c
        JOIN PRODUCTO p ON c.id_producto = p.id_producto
        WHERE c.id_usuario = $id_usuario
    ");

    while ($fila = mysqli_fetch_assoc($detalles)) {
        $id_producto = $fila['id_producto'];
        $cantidad = $fila['cantidad'];
        $precio_unitario = $fila['precio'];
        mysqli_query($conexion, "INSERT INTO DETALLE_PEDIDO (id_pedido, id_producto, cantidad, precio_unitario)
                                VALUES ($id_pedido, $id_producto, $cantidad, $precio_unitario)");

        // Actualizar stock
        mysqli_query($conexion, "UPDATE PRODUCTO SET stock = stock - $cantidad WHERE id_producto = $id_producto");
    }

    // Vaciar carrito
    mysqli_query($conexion, "DELETE FROM CARRITO WHERE id_usuario = $id_usuario");

    echo "<p>¡Compra realizada con éxito!</p><a href='cliente.php'>Volver</a>";
} else {
    echo "<p>Tu carrito está vacío.</p><a href='cliente.php'>Volver</a>";
}
?>
