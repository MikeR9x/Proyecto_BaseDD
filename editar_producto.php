<?php
session_start();
include 'conexion.php';

// Verificar si es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_productos.php");
    exit;
}

$id = $_GET['id'];
$producto = mysqli_query($conexion, "SELECT * FROM producto WHERE id_producto = $id");

if (mysqli_num_rows($producto) === 0) {
    header("Location: admin_productos.php");
    exit;
}

$p = mysqli_fetch_assoc($producto);

// Guardar cambios si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = mysqli_prepare($conexion,
        "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id_producto = ?");
    mysqli_stmt_bind_param($stmt, "ssdii", $nombre, $descripcion, $precio, $stock, $id);
    mysqli_stmt_execute($stmt);

    header("Location: admin_productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="text-center mb-4">✏️ Editar Producto</h3>

    <form method="POST" class="bg-white p-4 rounded shadow-sm mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $p['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required><?= $p['descripcion'] ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?= $p['precio'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= $p['stock'] ?>" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="admin_productos.php" class="btn btn-secondary">← Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

</body>
</html>
