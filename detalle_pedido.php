<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID de pedido no especificado.";
    exit();
}

$id_pedido = intval($_GET['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        .back {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            background: #7f8c8d;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .back:hover {
            background: #95a5a6;
        }
    </style>
</head>
<body>

<h2>Detalle del Pedido #<?php echo $id_pedido; ?></h2>

<table>
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>

    <?php
    $query = "SELECT P.nombre, D.cantidad, D.precio_unitario, (D.cantidad * D.precio_unitario) AS subtotal
                FROM DETALLE_PEDIDO D
                JOIN PRODUCTO P ON D.id_producto = P.id_producto
                WHERE D.id_pedido = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pedido);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['cantidad']}</td>
                <td>\${$row['precio_unitario']}</td>
                <td>\${$row['subtotal']}</td>
                </tr>";
    }

    mysqli_stmt_close($stmt);
    ?>
</table>

<a href="admin_pedidos.php" class="back">← Volver a pedidos</a>

</body>
</html>
