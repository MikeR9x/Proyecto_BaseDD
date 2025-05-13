<?php
session_start();
include 'conexion.php';

// Verificación de administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

// Si se pidió eliminar un usuario
if (isset($_GET['eliminar'])) {
    $id_a_eliminar = (int)$_GET['eliminar'];

    // No permitir que se elimine a sí mismo
    if ($id_a_eliminar !== $_SESSION['id_usuario']) {
        mysqli_query($conexion, "DELETE FROM usuario WHERE id_usuario = $id_a_eliminar");
    }

    header("Location: admin_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="text-center mb-4">👥 Gestión de Usuarios</h3>

    <div class="mb-3 text-start">
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">← Volver al panel</a>
    </div>

    <?php
    $usuarios = mysqli_query($conexion, "SELECT * FROM usuario");

    if (mysqli_num_rows($usuarios) > 0): ?>
        <table class="table table-bordered table-hover bg-white text-center shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($u = mysqli_fetch_assoc($usuarios)): ?>
                    <tr>
                        <td><?= $u['id_usuario'] ?></td>
                        <td><?= $u['nombre'] ?></td>
                        <td><?= $u['correo'] ?></td>
                        <td><?= ucfirst($u['tipo']) ?></td>
                        <td>
                            <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                                <a href="admin_usuarios.php?eliminar=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">🗑️ Eliminar</a>
                            <?php else: ?>
                                <span class="text-muted">Tú mismo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No hay usuarios registrados.</div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
