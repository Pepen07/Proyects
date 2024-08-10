<?php
include 'conexion.php';

// Obtener el nombre del artículo desde la solicitud
$nombre_articulo = $_POST['nombre_articulo'] ?? '';

// Consultar la base de datos para obtener el precio del artículo
$sql = "SELECT precio_articulo FROM articulos WHERE nombre_articulo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre_articulo);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el artículo existe y devolver el precio
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['exists' => true, 'precio_articulo' => $row['precio_articulo']]);
} else {
    echo json_encode(['exists' => false]);
}

// Cerrar conexión
$conn->close();
?>
