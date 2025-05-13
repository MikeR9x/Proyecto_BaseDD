<?php
session_start();


include 'conexion.php';
// Procesar formulario de agregar producto
if (isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nuevo_nombre'];
    $descripcion = $_POST['nuevo_descripcion'];
    $precio = $_POST['nuevo_precio'];
    $stock = $_POST['nuevo_stock'];

    $stmt = mysqli_prepare($conexion,   
        "INSERT INTO producto (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssdi", $nombre, $descripcion, $precio, $stock);
    mysqli_stmt_execute($stmt);

    header("Location: admin_productos.php");
    exit;
}
// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- Modal Agregar Producto -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nuevo_nombre" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea name="nuevo_descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Precio</label>
          <input type="number" step="0.01" name="nuevo_precio" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="nuevo_stock" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" name="agregar_producto" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-light">

<div class="container mt-5">
    <h3 class="text-center mb-4">📦 Lista de Productos</h3>

<div class="d-flex justify-content-between mb-3">
    <a > </a>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">➕ Agregar Producto</button>
</div>
<form method="GET" class="mb-4 d-flex" style="max-width: 400px;">
    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por ID o nombre..." value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
    <button type="submit" class="btn btn-outline-primary">Buscar</button>
</form>


    <?php
    if (isset($_GET['buscar']) && $_GET['buscar'] !== '') {
    $buscar = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $productos = mysqli_query($conexion,
        "SELECT * FROM producto 
         WHERE id_producto = '$buscar' 
         OR nombre LIKE '%$buscar%'");
} else {
    $productos = mysqli_query($conexion, "SELECT * FROM producto");
}

    if (mysqli_num_rows($productos) > 0): ?>
        <table class="table table-bordered table-hover bg-white shadow-sm text-center">
    <thead class="table-primary">
        <tr>
            <th>ID</th> <!-- Nuevo encabezado -->
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($p = mysqli_fetch_assoc($productos)): ?>
            <tr>
                <td><?= $p['id_producto'] ?></td> <!-- Nueva columna -->
                <td><?= $p['nombre'] ?></td>
                <td><?= $p['descripcion'] ?></td>
                <td>$<?= number_format($p['precio'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <a href="editar_producto.php?id=<?= $p['id_producto'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                    <a href="eliminar_producto.php?id=<?= $p['id_producto'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    <?php else: ?>
        <div class="alert alert-info text-center">No hay productos registrados aún.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">← Volver al panel</a>    </div>
</div>

</body>
</html>
