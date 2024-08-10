<?php
include 'conexion.php';

// Consultas para obtener la cantidad de facturas y el dinero total cobrado
$totalFacturasQuery = $pdo->query("SELECT COUNT(*) as total_facturas FROM facturas");
$totalFacturas = $totalFacturasQuery->fetchColumn();

$totalCobradoQuery = $pdo->query("SELECT SUM(total) as total_cobrado FROM facturas");
$totalCobrado = $totalCobradoQuery->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Estadísticas</title>
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
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
        .card {
            margin-bottom: 20px;
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
    <h1 class="text-center" style="color: #4b9cd3;">Estadísticas de Facturación</h1>

    <!-- Formulario para seleccionar la fecha -->
    <form method="POST" action="menu.php">
        <div class="form-group">
            <label for="fecha">Selecciona una Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>
    
    <!-- Resultados de la consulta por fecha -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener la fecha seleccionada
        $fechaSeleccionada = $_POST['fecha'];

        // Consultar la cantidad de facturas y el monto total cobrado para la fecha seleccionada
        $facturasPorFechaQuery = $pdo->prepare("SELECT COUNT(*) as total_facturas FROM facturas WHERE fecha = :fecha");
        $facturasPorFechaQuery->execute(['fecha' => $fechaSeleccionada]);
        $totalFacturasFecha = $facturasPorFechaQuery->fetchColumn();

        $cobradoPorFechaQuery = $pdo->prepare("SELECT SUM(total) as total_cobrado FROM facturas WHERE fecha = :fecha");
        $cobradoPorFechaQuery->execute(['fecha' => $fechaSeleccionada]);
        $totalCobradoFecha = $cobradoPorFechaQuery->fetchColumn();

        echo '<div class="card mt-4">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">Resultados para ' . htmlspecialchars($fechaSeleccionada) . '</h5>';
        echo '<p class="card-text">Cantidad de Facturas: ' . number_format($totalFacturasFecha) . ' facturas</p>';
        echo '<p class="card-text">Cantidad Total de Dinero Cobrado: $' . number_format($totalCobradoFecha, 2) . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <!-- Estadísticas generales -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Cantidad Total de Facturas</h5>
            <p class="card-text"><?php echo number_format($totalFacturas); ?> facturas</p>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Cantidad Total de Dinero Cobrado</h5>
            <p class="card-text"><?php echo '$' . number_format($totalCobrado, 2); ?></p>
        </div>
    </div>
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

