<?php
include("conexion.php");
session_start();

// Verifica si el usuario está logueado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Administrador - Gestión de Productos</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background-color: #f5f6fa;
        }

        h2 {
            color: #333;
        }

        form, table {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #3498db;
            color: white;
        }

        .acciones a {
            margin: 0 5px;
            text-decoration: none;
            color: #e74c3c;
        }

        .logout {
            margin-top: 30px;
        }

        .logout a {
            background-color: #888;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h2>Bienvenido Administrador <?php echo $_SESSION['nombre']; ?> 🛠️</h2>

    <h3>Agregar nuevo producto</h3>
    <form method="post" action="administrador.php">
        <input type="text" name="nombre" placeholder="Nombre del producto" required>
        <textarea name="descripcion" placeholder="Descripción" rows="3" required></textarea>
        <input type="number" name="precio" placeholder="Precio" step="0.01" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="submit" name="agregar" value="Agregar producto">
    </form>

    <a href="admin_dashboard.php" class="boton-regresar">← Volver al Dashboard</a>

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

    <?php
    // Insertar nuevo producto
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $sql = "INSERT INTO PRODUCTO (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi", $nombre, $descripcion, $precio, $stock);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<p style='color: green;'>Producto agregado correctamente.</p>";
    }

    // Eliminar producto
    if (isset($_GET['eliminar'])) {
        $id = $_GET['eliminar'];
        mysqli_query($conexion, "DELETE FROM PRODUCTO WHERE id_producto = $id");
        echo "<p style='color: red;'>Producto eliminado.</p>";
    }

    // Mostrar productos existentes
    $result = mysqli_query($conexion, "SELECT * FROM PRODUCTO");

    echo "<h3>Lista de productos</h3>";
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_producto']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['descripcion']}</td>
                <td>\${$row['precio']}</td>
                <td>{$row['stock']}</td>
                <td class='acciones'>
                    <a href='administrador.php?eliminar={$row['id_producto']}'>🗑 Eliminar</a>
                </td>
                </tr>";
    }

    echo "</table>";
    ?>

    <div class="logout">
        <a href="logout.php">Cerrar sesión</a>
    </div>

</body>
</html>
