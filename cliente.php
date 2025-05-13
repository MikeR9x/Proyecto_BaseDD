<?php
include("conexion.php");
session_start();

// Verifica si el usuario está logueado y es cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo de Productos</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background-color: #f0f2f5;
        }

        h2 {
            color: #333;
        }

        .producto {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .producto form {
            margin-top: 10px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #2ecc71;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .carrito, .logout, .mis-pedidos {
            margin-top: 30px;
        }

        .carrito a, .boton-regresar, .boton-pedidos {
            background: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .boton-regresar:hover,
        .carrito a:hover,
        .boton-pedidos:hover {
            background-color: #2980b9;
        }

        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .success {
            background-color: #d1f0d1;
            color: #2e7d32;
        }

        .error {
            background-color: #fdecea;
            color: #c0392b;
        }
    </style>
</head>
<body>

    <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?> 👋</h2>

    <!-- Mensajes de error o confirmación -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mensaje error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php elseif (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje success"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <h3>Productos disponibles</h3>

    <?php
    $productos = mysqli_query($conexion, "SELECT * FROM PRODUCTO WHERE stock > 0");

    while ($row = mysqli_fetch_assoc($productos)) {
        echo "<div class='producto'>
                <strong>{$row['nombre']}</strong><br>
                <small>{$row['descripcion']}</small><br>
                <strong>Precio:</strong> $ {$row['precio']}<br>
                <strong>Stock:</strong> {$row['stock']}
                <form method='post' action='agregar_al_carrito.php'>
                    <input type='hidden' name='id_producto' value='{$row['id_producto']}'>
                    <input type='number' name='cantidad' value='1' min='1' max='{$row['stock']}' required>
                    <input type='submit' value='Agregar al carrito 🛒'>
                </form>
            </div>";
    }
    ?>

    <div class="carrito">
        <a href="ver_carrito.php">Ver carrito 🛍️</a>
    </div>

    <div class="mis-pedidos">
        <a href="mis_pedidos.php" class="boton-pedidos">📦 Ver mis pedidos</a>
    </div>

    <div class="logout">
        <a href="logout.php" class="boton-regresar">← Cerrar sesión</a>
    </div>

</body>
</html>
