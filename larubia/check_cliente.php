<?php
include 'conexion.php';

// Obtener el código del cliente desde la solicitud AJAX
$codigo_cliente = $_POST['codigo_cliente'];

// Preparar la consulta
$stmt = $conn->prepare("SELECT nombre_cliente FROM clientes WHERE codigo_cliente = ?");
$stmt->bind_param("s", $codigo_cliente);

// Ejecutar la consulta
$stmt->execute();
$stmt->bind_result($nombre_cliente);

// Verificar si el cliente existe
$response = [];
if ($stmt->fetch()) {
    $response['exists'] = true;
    $response['nombre_cliente'] = $nombre_cliente;
} else {
    $response['exists'] = false;
}

// Cerrar la consulta y la conexión
$stmt->close();
$conn->close();

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
