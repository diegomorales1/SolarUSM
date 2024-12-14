<?php
include("template/headert.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_transportes"]) && isset($_POST["solicitar_trabajo"])) {
        $id_trabajo = $_POST["id_transportes"];
        $id_transportista = $_SESSION["idusuario"]["id_transportista"];

        //Consultar si ya existe una solicitud para este temporero y trabajo
        $sql_check_solicitud = "SELECT * FROM solicitudes_trans WHERE id_transportista = '$id_transportista' AND id_transportes = '$id_trabajo'";
        $result_check_solicitud = $conn->query($sql_check_solicitud);

        if ($result_check_solicitud->num_rows == 0) {
            //Si no existe la solicitud, la insertamos
            $sql_insert_solicitud = "INSERT INTO solicitudes_trans (id_transportista, id_transportes) VALUES ('$id_transportista', '$id_trabajo')";
            $result_insert_solicitud = $conn->query($sql_insert_solicitud);

            if (!$result_insert_solicitud) {
                echo "Error al insertar la solicitud: " . $conn->error;
            } else {
                echo "Solicitud exitosa";
            }
        } else {
            echo "Ya has solicitado este trabajo.";
        }
    }
}

// Obtener la lista de trabajos disponibles
$id_transportista = $_SESSION["idusuario"]["id_transportista"];
$sql_trabajos = "SELECT * FROM trabajos_transportes";
$result_trabajos = $conn->query($sql_trabajos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Trabajos Disponibles</title>
    <link rel="stylesheet" href="css/trabajos.css">
</head>
<body>
        <h1>Trabajos Disponibles</h1>
        
        <div class = "contenedor">
        <?php 
            if ($result_trabajos->num_rows > 0) {
                while ($row_trabajo = $result_trabajos->fetch_assoc()) {

            //while ($row_trabajo = mysqli_fetch_assoc($result_trabajos)) {
        ?>      
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $row_trabajo['nombre_trabajo'] ?></h4>
                    <p class="card-description"> <?php echo "Ubicación: {$row_trabajo['ubicacion']}" ?></p>
                    <p class="card-description"> <?php echo "Detalle del trabajo: {$row_trabajo['detalle_trabajo']}" ?></p>
                    <p class="card-description"> <?php echo "Fecha de inicio: {$row_trabajo['fecha_inicio']}" ?></p>
                    <p class="card-description"> <?php echo "Fecha de termino: {$row_trabajo['fecha_termino']}" ?></p>
                    <p class="card-description"> <?php echo "Valor a pagar: {$row_trabajo['valor_pagar']}" ?></p>
                </div>
                
                <?php

                    $sql_check_solicitud = "SELECT * FROM solicitudes_trans WHERE id_transportista = '$id_transportista' AND id_transportes = '{$row_trabajo['id_transportes']}'";
                    $result_check_solicitud = $conn->query($sql_check_solicitud);

                    if ($result_check_solicitud->num_rows == 0) {
                        //Si no se ha solicitado, mostrar el botón
                        ?>
                        <form method='POST'>
                            <input type='hidden' name='id_transportes' value='<?php echo $row_trabajo['id_transportes']; ?>'>
                            <button class="btn-solicitar" type='submit' name='solicitar_trabajo'>Solicitar Trabajo</button>
                        </form>
                        <button class="btn-detalles">Detalles</button>
                    <?php }   else {?>
                        <!-- #Si ya se ha solicitado, mostrar mensaje y botón bloqueado -->
                        <button class='btn-solicitado' disabled>Solicitado</button>
                        <button class="btn-detalles">Detalles</button>
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
