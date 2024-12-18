<?php
include("template/headerc.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Ingreso Ofertas Transportista</title>
    <style>

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .titulo-centrado {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-top: 50px;
        }
        
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.2);
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        input[type="submit"]:hover {
            background-color: #218838;
        }
        
        select {
            height: 40px;
        }
        
        textarea {
            resize: vertical;
        }

    </style>
</head>
<body>

<form action="IngresoOfertasT.php" method="post">
    <div class="form-container">
        <label for="nombre">Nombre trabajo:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" required><br>

        <label for="capacidad">Capacidad miníma del vehículo:</label>
        <input type="number" id="capacidad" name="capacidad" required><br>

        <label for="detalletrabajo">Detalle del Trabajo:</label>
        <textarea id="detalletrabajo" name="detalletrabajo" rows="4" required></textarea><br>

        <label for="fecha">Fecha de Inicio:</label>
        <input type="date" id="fecha" name="fecha" required><br>

        <label for="duracion">Fecha de Término:</label>
        <input type="date" id="duracion" name="duracion" required><br>

        <label for="horarioinicio">Horario Transporte:</label>
        <label for="inicio">Inicio:</label>
        <input type="time" id="inicio" name="inicio" value="07:00" required>
        <label for="termino">Termino:</label>
        <input type="time" id="termino" name="termino" value="15:30" required><br>

        <label for="valorpagar">Valor a pagar:</label>
        <input type="number" id="valorpagar" name="valorpagar" required><br>

        <input type="submit" name="guardaroferta" value="Guardar Oferta">
    </div>
</form>

<?php
if (isset($_POST['guardaroferta'])) {
    // Procesar el formulario cuando se envía
    $nombre_actual = $_SESSION["nombre_u"];

    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];
    $detalletrabajo = $_POST['detalletrabajo'];
    $fecha = $_POST['fecha'];
    $duracion = $_POST['duracion'];
    $inicio = $_POST['inicio'];
    $termino = $_POST['termino'];
    $valorpagar = $_POST['valorpagar'];
    $capacidad = $_POST['capacidad'];

    $sql = "SELECT runcontratista FROM contratista WHERE nombre = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Asociar el valor de $nombre_actual a la consulta
        $stmt->bind_param("s", $nombre_actual);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $runcontratista = $row['runcontratista'];
        }
    }

    // Consulta SQL para insertar datos en la tabla trabajos
    $sql = "INSERT INTO trabajos_transportes (nombre_trabajo, ubicacion, detalle_trabajo, fecha_inicio, fecha_termino, valor_pagar, hora_inicio, hora_fin, capacidad_minima, rut_contratista) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Asociar los valores a la consulta
        $stmt->bind_param("ssssssssss", $nombre, $ubicacion, $detalletrabajo, $fecha, $duracion, $valorpagar, $inicio, $termino, $capacidad, $runcontratista);

        // Ejecutar la consulta
        $stmt->execute();
        echo '<script type="text/javascript">window.location = "contratista.php";</script>';
        exit();
    }
}
?>

</body>
</html>