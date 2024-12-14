<?php
include("template/headerc.php");
// Asegúrate de que la sesión se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION["idusuario"];

// Función para borrar todas las solicitudes relacionadas con un trabajo
function borrarSolicitudes($conexion, $idTrabajo) {
    $sql = "DELETE FROM solicitudes WHERE id_trabajo = ?";
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
        $sql = "DELETE FROM trabajos WHERE id_trabajo = ?";
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
    $idTrabajo = $_POST['id_trabajo'];

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
            border: 1px solid black; /* Agrega un borde a todas las celdas */
        }

        /* Estilo para los encabezados de la tabla */
        th {
            background-color: #f2f2f2; /* Color de fondo */
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 10px;">Mis ofertas</h2>
    <?php
        // Consulta SQL para seleccionar los trabajos
        $sql = "SELECT id_trabajo, nombre_trabajo, ubicacion, fechainicio, detalle_trabajo, imagen_referencia, fechatermino, valorpagar, horainicio, horafin, horafincolacion, horainiciocolacion FROM trabajos WHERE runcontratista = ?";
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
            echo "<tr><th>ID Trabajo</th><th>Nombre del Trabajo</th><th>Ubicación</th><th>Fecha de Inicio</th><th>Detalle del Trabajo</th><th>Imagen de Referencia</th><th>Fecha de Término</th><th>Valor a Pagar</th><th>Hora de Inicio</th><th>Hora de Término</th><th>Hora de Inicio de Colación</th><th>Hora de Término de Colación</th><th>Acciones</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_trabajo'] . "</td>";
                echo "<td>" . $row['nombre_trabajo'] . "</td>";
                echo "<td>" . $row['ubicacion'] . "</td>";
                echo "<td>" . $row['fechainicio'] . "</td>";
                echo "<td>" . $row['detalle_trabajo'] . "</td>";
                echo "<td>" . $row['imagen_referencia'] . "</td>";
                echo "<td>" . $row['fechatermino'] . "</td>";
                echo "<td>" . $row['valorpagar'] . "</td>";
                echo "<td>" . $row['horainicio'] . "</td>";
                echo "<td>" . $row['horafin'] . "</td>";
                echo "<td>" . $row['horafincolacion'] . "</td>";
                echo "<td>" . $row['horainiciocolacion'] . "</td>";
                echo "<td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='id_trabajo' value='" . $row['id_trabajo'] . "'>";
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

