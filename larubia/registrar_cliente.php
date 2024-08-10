<?php
include 'conexion.php';

// Recoger datos del formulario
$codigo_cliente = $_POST['codigoCliente'];
$nombre_cliente = $_POST['nombreCliente'];

// Preparar y ejecutar la consulta para insertar el cliente
$stmt = $conn->prepare("INSERT INTO clientes (codigo_cliente, nombre_cliente) VALUES (?, ?)");
$stmt->bind_param("ss", $codigo_cliente, $nombre_cliente);

if ($stmt->execute()) {
    echo "Cliente registrado con éxito";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la consulta y la conexión
$stmt->close();
$conn->close();
?>
