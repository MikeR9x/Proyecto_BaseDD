<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$consulta = mysqli_prepare($conexion, "SELECT * FROM pedido WHERE id_usuario = ?");
mysqli_stmt_bind_param($consulta, "i", $id_usuario);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4 text-center">Mis Pedidos</h3>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div class="accordion" id="accordionPedidos">
            <?php $index = 0; $numero_pedido = 1; while ($pedido = mysqli_fetch_assoc($resultado)): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $index ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                            Pedido #<?= $numero_pedido ?> - <?= $pedido['fecha'] ?> - Total: $<?= number_format($pedido['total'], 2) ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionPedidos">
                        <div class="accordion-body">
                            <!-- Productos del pedido -->
                            <?php
                            $id_pedido = $pedido['id_pedido'];
                            $productos = mysqli_query($conexion,
                                "SELECT p.nombre, dp.cantidad, dp.precio_unitario
                                 FROM detalle_pedido dp
                                 JOIN producto p ON dp.id_producto = p.id_producto
                                 WHERE dp.id_pedido = $id_pedido");
                            ?>

                            <?php if (mysqli_num_rows($productos) > 0): ?>
                                <table class="table table-sm table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($prod = mysqli_fetch_assoc($productos)): ?>
                                            <tr>
                                                <td><?= $prod['nombre'] ?></td>
                                                <td><?= $prod['cantidad'] ?></td>
                                                <td>$<?= number_format($prod['precio_unitario'], 2) ?></td>
                                                <td>$<?= number_format($prod['cantidad'] * $prod['precio_unitario'], 2) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted text-center">Este pedido no tiene productos registrados.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php $index++; $numero_pedido++; endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            No tienes pedidos registrados.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="cliente.php" class="btn btn-outline-primary">← Volver al menú</a>
    </div>
</div>

<!-- Bootstrap JS para el acordeón -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
