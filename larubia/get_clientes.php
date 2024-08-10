<?php
include 'conexion.php';

// Consultar la tabla visitas
$sql = "SELECT id, codigo_cliente, nombre_cliente FROM clientes";
$result = $conn->query($sql);

// Generar filas de la tabla en formato HTML
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["codigo_cliente"] . "</td>";
        echo "<td>" . $row["nombre_cliente"] . "</td>";
        echo '<td>
                <a href="EliminarClientes.php?id=' . $row["id"] . '" class="link-dark" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este registro?\');"><i class="fa-solid fa-trash fs-5"></i></a>
              </td>';
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No hay registros</td></tr>";
}

$conn->close();
?>