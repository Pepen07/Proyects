<?php
$servername = "localhost";
$username = "root";
$password = "";  // Cambia esto por la contraseña de tu servidor MySQL
$dbname = "la_rubia";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada correctamente\n";
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}

// Seleccionar la base de datos
$conn->select_db($dbname);

// Crear tabla de artículos
$sql = "CREATE TABLE IF NOT EXISTS articulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_articulo VARCHAR(255) NOT NULL,
    precio_articulo DECIMAL(10, 2) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla 'articulos' creada correctamente\n";
} else {
    echo "Error al crear la tabla 'articulos': " . $conn->error;
}

// Crear tabla de clientes
$sql = "CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_cliente VARCHAR(50) NOT NULL,
    nombre_cliente VARCHAR(255) NOT NULL,
    UNIQUE (codigo_cliente)
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla 'clientes' creada correctamente\n";
} else {
    echo "Error al crear la tabla 'clientes': " . $conn->error;
}

// Crear tabla de facturas
$sql = "CREATE TABLE IF NOT EXISTS facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    codigo_cliente VARCHAR(50) NOT NULL,
    nombre_cliente VARCHAR(255) NOT NULL,
    comentario TEXT,
    total DECIMAL(10, 2) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla 'facturas' creada correctamente\n";
} else {
    echo "Error al crear la tabla 'facturas': " . $conn->error;
}

// Crear tabla de relación factura-artículo
$sql = "CREATE TABLE IF NOT EXISTS factura_articulo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT,
    articulo VARCHAR(50) NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla 'factura_articulo' creada correctamente\n";
} else {
    echo "Error al crear la tabla 'factura_articulo': " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
