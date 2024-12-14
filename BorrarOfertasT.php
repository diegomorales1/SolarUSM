<?php
include("template/headerc.php");
// Inicia la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION["idusuario"];

// Función para borrar todas las solicitudes relacionadas con un trabajo
function borrarSolicitudes($conexion, $idTrabajo) {
    $sql = "DELETE FROM solicitudes_trans WHERE id_transportes = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $idTrabajo);
        if ($stmt->execute()) {
            return true;
        }
    }
    return false;
}

// Función para borrar un trabajo por su ID
function borrarTrabajo($conexion, $idTrabajo) {
    // Primero borra las solicitudes asociadas
    if (borrarSolicitudes($conexion, $idTrabajo)) {
        // Luego borra el trabajo
        $sql = "DELETE FROM trabajos_transportes WHERE id = ?";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idTrabajo);
            if ($stmt->execute()) {
                return true;
            }
        }
    }
    return false;
}

if (isset($_POST['borrar'])) {
    $idTrabajo = $_POST['id'];

    if (borrarTrabajo($conn, $idTrabajo)) {
        echo "El trabajo y las solicitudes asociadas han sido eliminadas con éxito.";
    } else {
        echo "Error al eliminar el trabajo y/o las solicitudes.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contratista</title>
    <style>
        /* Estilo para la tabla */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        /* Estilo para las celdas de la tabla */
        table, th, td {
            border: 1px solid black;
        }
        
        /* Estilo para los encabezados de la tabla */
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 10px;">Mis ofertas de transportistas</h2>
    <?php
        // Consulta SQL para seleccionar los trabajos
        $sql = "SELECT id_transportes, nombre_trabajo, ubicacion, detalle_trabajo, fecha_inicio,  fecha_termino, valor_pagar, hora_inicio, hora_fin, capacidad_minima FROM trabajos_transportes WHERE rut_contratista = $id";
        if ($stmt = $conn->prepare($sql)) {
            // Enlazar el valor de $id a la consulta
            $stmt->bind_param("s", $id);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener resultados
            $result = $stmt->get_result();

            echo '<div style="margin-top: 20px;">';

            // Mostrar los resultados en una tabla con cada atributo separado
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombre del Trabajo</th><th>Ubicación</th><th>Fecha de Inicio</th><th>Detalle del Trabajo</th><th>Fecha de Término</th><th>Valor a Pagar</th><th>Hora de Inicio</th><th>Hora de Término</th><th>Capacidad Minima</th><th>Acciones</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre_trabajo'] . "</td>";
                echo "<td>" . $row['ubicacion'] . "</td>";
                echo "<td>" . $row['fecha_inicio'] . "</td>";
                echo "<td>" . $row['detalle_trabajo'] . "</td>";
                echo "<td>" . $row['fecha_termino'] . "</td>";
                echo "<td>" . $row['valor_pagar'] . "</td>";
                echo "<td>" . $row['hora_inicio'] . "</td>";
                echo "<td>" . $row['hora_fin'] . "</td>";
                echo "<td>" . $row['capacidad_minima'] . "</td>";
                echo "<td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name='borrar' value='Borrar'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo '</div>';
        }
    ?>
</body>
</html>


