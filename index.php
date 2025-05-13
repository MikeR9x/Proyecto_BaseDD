<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Iniciar Sesión - Tienda Virtual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="procesar_login.php">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de usuario</label>
                <select class="form-select" name="tipo" required>
                    <option value="">Seleccione tipo</option>
                    <option value="cliente">Cliente</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </div>
</div>

</body>
</html>
