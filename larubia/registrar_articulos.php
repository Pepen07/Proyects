<?php
include 'conexion.php';

// Recoger datos del formulario
$nombre_articulo = $_POST['nombreArticulo'];
$precio_articulo = $_POST['precioArticulo'];

// Preparar y ejecutar la consulta para insertar el artículo
$stmt = $conn->prepare("INSERT INTO articulos (nombre_articulo, precio_articulo) VALUES (?, ?)");
$stmt->bind_param("sd", $nombre_articulo, $precio_articulo);

if ($stmt->execute()) {
    echo "Artículo registrado con éxito";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la consulta y la conexión
$stmt->close();
$conn->close();
?>
