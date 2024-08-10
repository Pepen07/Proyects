<?php
session_start();
include 'conexion.php';

// Verificar si se recibió un ID válido a través de GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta para eliminar el registro
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Establecer el mensaje de éxito en la sesión
        $_SESSION['message'] = "Registro eliminado correctamente.";
    } else {
        $_SESSION['message'] = "Error al intentar eliminar el registro: " . $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID no válido";
}

$conn->close();

// Redireccionar a la página de listado
header("Location: Factura.php");
exit();
?>