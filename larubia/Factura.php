<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Facturas</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group input[type="number"] {
            width: auto;
            display: inline-block;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #0021fa;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #2e00fa;
        }
        .articulos-container {
            margin-top: 20px;
        }
        .articulo-item {
            margin-bottom: 10px;
        }
        .articulo-item input {
            margin-right: 5px;
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
        <h1 class="text-center" style="color: #4b9cd3;">Registro de Factura</h1>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php endif; ?>

    <div class="form-group">
        <label for="numero_factura">Número de Factura:</label>
        <div id="numero_factura" class="form-control">
            <?php
            include 'conexion.php';
            
            // Consulta para obtener el último número de factura
            $stmt = $pdo->query("SELECT id FROM facturas ORDER BY id DESC LIMIT 1");
            $row = $stmt->fetch();
            
            // Calcular el siguiente número de factura
            $numero_factura = $row ? $row['id'] + 1 : 1;
            
            // Mostrar el siguiente número de factura
            echo 'Factura #' . $numero_factura;
            ?>
        </div>
    </div>
        <form id="invoiceForm" action="guardar_factura.php" method="POST">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="codigo_cliente">Código del Cliente:</label>
                <input type="text" id="codigo_cliente" name="codigo_cliente" required onblur="checkCliente()">
            </div>
            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente:</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente" required>
            </div>
            <div class="form-group">
                <label for="comentario">Comentario:</label>
                <textarea id="comentario" name="comentario"></textarea>
            </div>
            <div class="form-group">
                <label for="total">Total a Pagar:</label>
                <input type="number" id="total" name="total" step="0.01" required>
            </div>
            <div class="articulos-container" id="articulosContainer">
                <div class="articulo-item">
                    <input type="text" name="articulos[0][nombre]" placeholder="Nombre del Artículo" required onblur="checkArticulo(this)">
                    <input type="number" name="articulos[0][precio]" placeholder="Precio" step="0.01" readonly required oninput="calculateTotal(this)">
                    <input type="number" name="articulos[0][cantidad]" placeholder="Cantidad" required oninput="calculateTotal(this)">
                    <input type="number" name="articulos[0][total]" placeholder="Total" step="0.01" readonly>
                    <button type="button" class="remove-articulo" onclick="removeArticulo(this)">Eliminar</button>
                </div>
            </div>
            <div class="form-group">
                <button type="button" onclick="addArticulo()">Agregar Artículo</button>
            </div>
            <div class="form-group">
                <button type="submit">Registrar Factura</button>
                <button type="button" class="btn btn-secondary no-print" onclick="printInvoice()">Imprimir Factura</button>
            </div>
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        let articuloIndex = 1;

        function addArticulo() {
            const container = document.getElementById('articulosContainer');
            const itemHTML = `
                <div class="articulo-item">
                    <input type="text" name="articulos[${articuloIndex}][nombre]" placeholder="Nombre del Artículo" required onblur="checkArticulo(this)">
                    <input type="number" name="articulos[${articuloIndex}][precio]" placeholder="Precio" step="0.01" readonly required oninput="calculateTotal(this)">
                    <input type="number" name="articulos[${articuloIndex}][cantidad]" placeholder="Cantidad" required oninput="calculateTotal(this)">
                    <input type="number" name="articulos[${articuloIndex}][total]" placeholder="Total" step="0.01" readonly>
                    <button type="button" class="remove-articulo" onclick="removeArticulo(this)">Eliminar</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHTML);
            articuloIndex++;
        }

        function removeArticulo(button) {
        const item = button.closest('.articulo-item');
        item.remove();
        calculateGrandTotal(); // Recalcula el total general después de eliminar
    }

        function checkCliente() {
            const codigo_cliente = document.getElementById('codigo_cliente').value;
            if (codigo_cliente) {
                $.ajax({
                    url: 'check_cliente.php',
                    type: 'POST',
                    data: { codigo_cliente: codigo_cliente },
                    success: function(response) {
                        console.log("Respuesta del servidor (cliente):", response);
                        const data = JSON.parse(response);
                        if (data.exists) {
                            document.getElementById('nombre_cliente').value = data.nombre_cliente;
                        } else {
                            document.getElementById('nombre_cliente').value = '';
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX (cliente):', textStatus, errorThrown);
                        alert('Error al verificar el cliente.');
                    }
                });
            }
        }

        function checkArticulo(input) {
            const nombreArticulo = input.value;
            if (nombreArticulo) {
                $.ajax({
                    url: 'check_articulo.php',
                    type: 'POST',
                    data: { nombre_articulo: nombreArticulo },
                    success: function(response) {
                        console.log("Respuesta del servidor (artículo):", response);
                        const data = JSON.parse(response);
                        if (data.exists) {
                            input.closest('.articulo-item').querySelector('input[name*="[precio]"]').value = data.precio_articulo;
                            calculateTotal(input.closest('.articulo-item').querySelector('input[name*="[cantidad]"]'));
                        } else {
                            input.closest('.articulo-item').querySelector('input[name*="[precio]"]').value = '';
                            alert('Artículo no encontrado.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX (artículo):', textStatus, errorThrown);
                        alert('Error al verificar el artículo.');
                    }
                });
            }
        }

        function calculateTotal(input) {
            const item = input.closest('.articulo-item');
            const cantidad = parseFloat(item.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = parseFloat(item.querySelector('input[name*="[precio]"]').value) || 0;
            const total = cantidad * precio;
            item.querySelector('input[name*="[total]"]').value = total.toFixed(2);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            const totalInputs = document.querySelectorAll('input[name*="[total]"]');
            let grandTotal = 0;
            totalInputs.forEach(input => {
                grandTotal += parseFloat(input.value) || 0;
            });
            document.getElementById('total').value = grandTotal.toFixed(2);
        }

        function printInvoice() {
            document.querySelectorAll('.no-print').forEach(elem => elem.style.display = 'none');
            window.print();
            window.onafterprint = () => {
                document.querySelectorAll('.no-print').forEach(elem => elem.style.display = '');
            };
        }

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
