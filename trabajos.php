<?php
include("template/header.php");

// Obtener la lista de trabajos disponibles
$id_temporero = $_SESSION["idusuario"]["id_temporero"];
$sql_trabajos = "SELECT * FROM trabajos";
$result_trabajos = $conn->query($sql_trabajos);

//Procesar la solicitud si se hace clic en el botón
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_trabajo"]) && isset($_POST["solicitar_trabajo"])) {
        $id_trabajo = $_POST["id_trabajo"];
        $id_temporero = $_SESSION["idusuario"]["id_temporero"];

        //Consultar si ya existe una solicitud para este temporero y trabajo
        $sql_check_solicitud = "SELECT * FROM solicitudes WHERE id_temporero = '$id_temporero' AND id_trabajo = '$id_trabajo'";
        $result_check_solicitud = $conn->query($sql_check_solicitud);

        if ($result_check_solicitud->num_rows == 0) {
            //Si no existe la solicitud, la insertamos
            $sql_insert_solicitud = "INSERT INTO solicitudes (id_temporero, id_trabajo) VALUES ('$id_temporero', '$id_trabajo')";
            $result_insert_solicitud = $conn->query($sql_insert_solicitud);

            if (!$result_insert_solicitud) {
                echo "Error al insertar la solicitud: " . $conn->error;
            } else {
                echo "<div class='message'>Has postulado exitosamente al trabajo.</div>";
            }
        } else {
            echo "<div class='message2'>Ya has postulado a este trabajo.</div>";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Trabajos Disponibles</title>
    <link rel="stylesheet" href="css/trabajos.css">

    <style>
        .message {
            background-color: #4CAF50; /* Fondo verde para indicar éxito */
            color: #fff; /* Texto blanco para contraste */
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
        }

        .message2 {
            background-color: #FFD700; /* Fondo verde para indicar éxito */
            color: black; /* Texto blanco para contraste */
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
        }

        img {
            max-width: 100%; /* Ancho máximo */
            
            height: 100%;
        }
    </style>

</head>
<body>
        <br>
        <h1 style="margin-top: 20px; margin-bottom: 20px;">Trabajos Disponibles</h1>
        
        <div class = "contenedor">
        <?php 
            if ($result_trabajos->num_rows > 0) {
                while ($row_trabajo = $result_trabajos->fetch_assoc()) {

            //while ($row_trabajo = mysqli_fetch_assoc($result_trabajos)) {
        ?>
          
            <div class="card">
                <div class="card-img-top">   
                    <img src="<?php echo $row_trabajo['imagen_referencia']; ?>" alt='Imagen de referencia'>
                </div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $row_trabajo['nombre_trabajo'] ?></h4>
                    <p class="card-description"> <?php echo "Ubicación: {$row_trabajo['ubicacion']}" ?></p>
                    <p class="card-description"> <?php echo "Fecha de inicio: {$row_trabajo['fechainicio']}" ?></p>
                    <p class="card-description"> <?php echo "Fecha de termino: {$row_trabajo['fechatermino']}" ?></p>
                    <p class="card-description"> <?php echo "Valor a pagar: {$row_trabajo['valorpagar']}" ?></p>
                </div>
                
                <?php

                    $sql_check_solicitud = "SELECT * FROM solicitudes WHERE id_temporero = '$id_temporero' AND id_trabajo = '{$row_trabajo['id_trabajo']}'";
                    $result_check_solicitud = $conn->query($sql_check_solicitud);

                    if ($result_check_solicitud->num_rows == 0) {
                        //Si no se ha solicitado, mostrar el botón
                        ?>
                        <form method='POST'>
                            <input type='hidden' name='id_trabajo' value='<?php echo $row_trabajo['id_trabajo']; ?>'>
                            <button class="btn-solicitar" type='submit' name='solicitar_trabajo'>Solicitar Trabajo</button>
                            
                        </form>
                        <a href="detalles_job.php?id_trabajo=<?php echo $row_trabajo['id_trabajo']; ?>" class="btn-detalles" style="text-decoration: none; display: block;">
                            <button class="btn-detalles">Detalles</button>
                        </a>
                    <?php }   else {?>
                        <!-- #Si ya se ha solicitado, mostrar mensaje y botón bloqueado -->
                        <button class='btn-solicitado' disabled>Solicitado</button>
                        <a href="detalles_job.php?id_trabajo=<?php echo $row_trabajo['id_trabajo']; ?>" class="btn-detalles" style="text-decoration: none; display: block;">
                            <button class="btn-detalles">Detalles</button>
                        </a>
                        
                   <?php }  ?>
            </div>
       
        <?php

            }
        } else {
            echo "No hay trabajos disponibles.";
        }

        ?>
          </div>
</body>
</html>
