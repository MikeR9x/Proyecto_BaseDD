<?php
session_start();
include('conexion.php');

// Solo administradores pueden ver esta página
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Administrador</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background-color: #f4f6f8;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .detalle-link {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Lista de Pedidos 🧾</h2>

<table>
    <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Detalle</th>
    </tr>

    <?php
    $query = "SELECT P.id_pedido, U.nombre, P.fecha, P.total
              FROM PEDIDO P
              JOIN USUARIO U ON P.id_usuario = U.id_usuario
              ORDER BY P.id_pedido ASC"; // ← Cambio aplicado aquí
    $result = mysqli_query($conexion, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_pedido']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['fecha']}</td>
                <td>\${$row['total']}</td>
                <td><a class='detalle-link' href='detalle_pedido.php?id={$row['id_pedido']}'>Ver detalle</a></td>
              </tr>";
    }
    ?>
</table>

<a href="admin_dashboard.php" class="boton-regresar">← Volver al panel</a>

<style>
.boton-regresar {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    transition: background-color 0.3s;
}
.boton-regresar:hover {
    background-color: #2980b9;
}
</style>

</body>
</html>
