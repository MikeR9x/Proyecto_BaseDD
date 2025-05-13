<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="text-center mb-4">🛒 Tu Carrito</h3>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php elseif (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <?php
    $consulta = mysqli_query($conexion,
        "SELECT c.id_producto, p.nombre, p.precio, c.cantidad, p.stock
         FROM carrito c
         JOIN producto p ON c.id_producto = p.id_producto
         WHERE c.id_usuario = $id_usuario");

    if (mysqli_num_rows($consulta) > 0): ?>
        <table class="table table-bordered text-center bg-white shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                while ($item = mysqli_fetch_assoc($consulta)) {
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= $item['nombre'] ?></td>
                        <td>
                            <form method="post" action="actualizar_carrito.php">
                                <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">
                                <input
                                    type="number"
                                    name="cantidad"
                                    value="<?= $item['cantidad'] ?>"
                                    min="0"
                                    max="<?= $item['stock'] ?>"
                                    class="form-control text-center"
                                    style="width: 80px; margin: auto;"
                                    onchange="this.form.submit()"
                                >
                            </form>
                        </td>
                        <td>$<?= number_format($item['precio'], 2) ?></td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <form method="post" action="eliminar_del_carrito.php">
                                <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="table-secondary fw-bold">
                    <td colspan="3">Total</td>
                    <td colspan="2">$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="confirmacion.php" class="btn btn-success">Confirmar Pedido</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="cliente.php" class="btn btn-outline-primary">← Volver al catálogo</a>
    </div>
</div>

</body>
</html>
