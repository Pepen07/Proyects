<?php
include 'conexion.php';

$articulo_id = $_POST['articulo_id'];

$query = $mysqli->prepare("SELECT precio_articulo FROM articulos WHERE id = ?");
$query->bind_param("i", $articulo_id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$query->close();
$mysqli->close();
?>
