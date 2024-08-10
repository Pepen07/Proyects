<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = $_POST['fecha'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $comentario = $_POST['comentario'];
    $total = $_POST['total'];
    $articulos = $_POST['articulos']; // Array de artículos
    
    try {
        // Iniciar una transacción
        $pdo->beginTransaction();

        // Insertar factura
        $stmt = $pdo->prepare("INSERT INTO facturas (fecha, codigo_cliente, nombre_cliente, comentario, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fecha, $codigo_cliente, $nombre_cliente, $comentario, $total]);
        
        $factura_id = $pdo->lastInsertId();
        
        // Insertar artículos
        foreach ($articulos as $articulo) {
            $stmt = $pdo->prepare("INSERT INTO factura_articulo (factura_id, articulo, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$factura_id, $articulo['nombre'], $articulo['cantidad'], $articulo['precio'], $articulo['total']]);
        }

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir a Factura.php con un mensaje de éxito
        header("Location: Factura.php?mensaje=Factura registrada exitosamente");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        echo "Error al registrar la factura: " . $e->getMessage();
    }
}
?>
