<?php
session_start();

// Si el usuario ya está logueado, redirigir a la página principal
if (isset($_SESSION['user_id'])) {
    $userRole = $_SESSION['user_id'];
    if ($userRole == 3) {
        header("Location: FacturaEmpleado.php"); // Redirige a Factura.php si el usuario es empleado
    } else {
        header("Location: menu.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Array de usuarios fijos
    $users = [
        'adamix' => ['password' => 'pasemesolosilomeresco70', 'id' => 1],
        'admin' => ['password' => 'admin', 'id' => 2],
        'empleado' => ['password' => 'empleado', 'id' => 3]
    ];

    // Verificar si el usuario existe y la contraseña es correcta
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user_id'] = $users[$username]['id'];
        $_SESSION['username'] = $username;
        if ($users[$username]['id'] == 3) {
            header("Location: FacturaEmpleado.php"); // Redirige a Factura.php si el usuario es empleado
        } else {
            header("Location: menu.php");
        }
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7a15b05126.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #4b9cd3;
            margin-bottom: 20px;
            font-family: 'Pacifico', cursive; /* Fuente artística */
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #4b9cd3;
            border: none;
            color: #ffffff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .login-button:hover {
            background-color: #357abd;
        }
        .installer-button {
            width: 100%;
            padding: 10px;
            background-color: #ffc107;
            border: none;
            color: #333;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            margin-top: 10px;
        }
        .installer-button:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="login-title">LA RUBIA</h2>
        <?php if ($error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="login-button">Iniciar sesión</button>
                <button type="button" class="installer-button" onclick="runInstaller()">Ejecutar Instalador</button>
            </div>
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function runInstaller() {
            console.log("Intentando ejecutar instalador");
            $.ajax({
                url: 'instalador.php',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    alert('Instalador ejecutado correctamente: ' + response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al ejecutar el instalador:', textStatus, errorThrown);
                    alert('Error al ejecutar el instalador');
                }
            });
        }
    </script>
</body>

</html>
