<?php
include('conexion.php');
session_start();

// Verificar si es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #2c3e50;
        }

        .admin-container {
            margin-top: 30px;
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .admin-button {
            display: inline-block;
            background-color: #2980b9;
            color: white;
            padding: 20px 30px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }

        .admin-button:hover {
            background-color: #3498db;
        }

        .logout {
            margin-top: 50px;
            background-color: #c0392b;
        }

        .logout:hover {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>

<h1>Panel del Administrador 🛠️</h1>

<div class="admin-container">

    <a href="admin_productos.php" class="admin-button">📦 Lista de Productos</a> 
    <a href="admin_pedidos.php" class="admin-button">📄 Ver Pedidos</a>
    <a href="admin_usuarios.php" class="admin-button">👥 Gestionar Usuarios</a>

    
</div>

<a href="logout.php" class="admin-button logout">🔒 Cerrar Sesión</a>

</body>
</html>
