<?php
include("template/headerc.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contratista</title>
    <link rel="stylesheet" href="css/trabajos.css">
    <style>
        .boton {
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
            padding: 10px 20px; /* Espaciado interno */
            border: none; /* Borde */
            border-radius: 5px; /* Borde redondeado */
            cursor: pointer; /* Cambiar el cursor al pasar por encima */
            margin-right: 10px; /* Margen derecho para separar los botones */
        }

        .boton:hover {
            background-color: #45a049; /* Cambiar el color de fondo al pasar el cursor */
        }

        .boton-container {
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Indique lo que desea hacer</h1>
    <form method="post">
        <div class="boton-container">
            <button class="boton" type="submit" name="button3">Añadir una oferta para Temporero</button>
        </div>
        <div class="boton-container">
            <button class="boton" type="submit" name="button4">Añadir oferta para Transportista</button>
        </div>
        <div class="boton-container">
            <button class="boton" type="submit" name="button2">Borrar una Oferta para Temporero</button>
        </div>
        <div class="boton-container">
            <button class="boton" type="submit" name="button5">Borrar una Oferta para Transportista</button>
        </div>
        <div class="boton-container">
            <button class="boton" type="submit" name="button1">Seleccionar postulantes a mis ofertas</button>
        </div>
    </form>

    <?php
    if (isset($_POST['button1'])) {
        // Acción a realizar cuando se hace clic en Botón 1
        echo "Has hecho clic en Botón 1";
    } elseif (isset($_POST['button2'])) {
        // Acción a realizar cuando se hace clic en Botón 2
        header("Location: BorrarOfertas.php");
        exit();
    } elseif (isset($_POST['button3'])) {
        // Redireccionar a IngresoOfertas.php
        header("Location: IngresoOfertas.php");
        exit();
    } elseif (isset($_POST['button4'])) {
        // Acción para el botón 4
        header("Location: IngresoOfertasT.php");
        exit();
    } elseif (isset($_POST['button5'])) {
        // Acción a realizar cuando se hace clic en Botón 2
        header("Location: BorrarOfertasT.php");
        exit();
    }
    ?>
</body>
</html>
