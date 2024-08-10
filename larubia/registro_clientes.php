<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
    <link rel="stylesheet" href="estilos.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7a15b05126.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        .navbar-brand {
            font-family: 'Pacifico', cursive; /* Fuente artística */
            font-size: 2rem; /* Tamaño de fuente más grande */
            color: #4b9cd3; /* Color personalizado */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Sombra de texto para efecto 3D */
            font-weight: bold; /* Negrita */
            letter-spacing: 1px; /* Espaciado entre letras */
        }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">LA RUBIA</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="Factura.php">Registrar Factura</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registro_clientes.php">Registrar Cliente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registro_articulos.php">Registrar Artículo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="menu.php">Estadísticas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link logout" href="#" onclick="confirmLogout(event)">Cerrar Sesión</a>
            </li>
            <li class="nav-item">
                <button type="button" class="btn btn-warning nav-link" onclick="runInstaller()">Ejecutar Instalador</button>
            </li>
        </ul>
    </div>
    </nav>

    <div class="container">
    <div class="card">
        <h1 class="text-center" style="color: #4b9cd3;">Registro de Clientes</h1>
        <form id="clientForm">
            <div class="form-group">
                <label for="codigoCliente">Código del Cliente (Matrícula):</label>
                <input type="text" class="form-control" id="codigoCliente" name="codigoCliente" required>
            </div>
            <div class="form-group">
                <label for="nombreCliente">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Cliente</button>
        </form>
    </div>
    </div>

    <div class="container mt-5 custom-font">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="centered-form-container">
                    <h4 class="text-center">LISTA DE ARTICULOS</h4>

                    <!-- Mostrar el mensaje de éxito o error -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php
                                echo $_SESSION['message'];
                                unset($_SESSION['message']); // Eliminar el mensaje después de mostrarlo
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover mt-4">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>codigo_cliente</th>
                                    <th>nombre_cliente</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include 'get_clientes.php'; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-4">
                        <a href="Factura.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('clientForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'registrar_cliente.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Cliente registrado con éxito');
                document.getElementById('clientForm').reset();
            },
            error: function() {
                alert('Error al registrar el cliente');
            }
        });
        });
        function runInstaller() {
        $.ajax({
            url: 'instalador.php',
            type: 'GET',
            success: function(response) {
                alert('Instalador ejecutado correctamente: ' + response);
            },
            error: function() {
                alert('Error al ejecutar el instalador');
            }
        });
        }

        function confirmLogout(event) {
            event.preventDefault(); // Evita que el enlace navegue a la URL inmediatamente
            var confirmation = confirm("¿Estás seguro de que deseas cerrar sesión?");
            if (confirmation) {
                window.location.href = 'logout.php'; // Redirige a logout.php si el usuario confirma
            }
        }
    </script>

</body>
</html>
