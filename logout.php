<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sesión cerrada</title>
    <meta http-equiv="refresh" content="2;url=index.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            padding-top: 100px;
        }

        .mensaje {
            background: white;
            display: inline-block;
            padding: 30px 50px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .mensaje h2 {
            color: #27ae60;
        }

        .mensaje p {
            margin-bottom: 20px;
        }

        .mensaje a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .mensaje a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="mensaje">
    <h2>✅ Sesión cerrada correctamente</h2>
    <p>Redirigiendo a la página de inicio...</p>
    <a href="index.php">Volver ahora</a>
</div>

</body>
</html>
