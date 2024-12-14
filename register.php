<?php
include "conexion.php";

if ($_POST) {
    if (isset($_POST['btn-registrar-contratista'])) {
        // Obtener los datos del formulario
        $runcontratista = $_POST['runcontratista'];
        $nombre = $_POST['nombre'];
        $clave = $_POST['clave'];
        $contacto = $_POST['contacto'];
        $telcontacto = $_POST['telcontacto'];
        $whatsapp = $_POST['whatsapp'];
        $correo = $_POST['correo'];

        // Insertar datos en la tabla "contratista"
        $sql = "INSERT INTO contratista (runcontratista, nombre, clave, contacto, telcontacto, whatsapp, correo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Enlazar los parámetros
            $stmt->bind_param("sssssss", $runcontratista, $nombre, $clave, $contacto, $telcontacto, $whatsapp, $correo);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Contratista registrado exitosamente.";
            } 
        }
    } elseif (isset($_POST['btn-registrar-temporero'])) {
        if (!empty($_POST["nombre_usuario"]) && !empty($_POST["clave_usuario"]) && !empty($_POST["correo_usuario"]) && !empty($_POST["fecha_nac"])) {
            $nombre = $_POST['nombre_usuario'];
            $fecha_nac = $_POST['fecha_nac'];
            $correo = $_POST['correo_usuario'];
            $clave = $_POST['clave_usuario'];
            $telefono = $_POST['telefono'];
            $ciudad = $_POST['ciudad'];
            $direccion = $_POST['direccion'];
            //$tipo_usuario = $_POST['tipo_usuario'];
        
            $sistema_prevision = $_POST['sistema_prevision'];
            $sistema_salud = $_POST['sistema_salud'];
            $jubilacion = $_POST['jubilacion'];
            $detalle_jubilacion = $_POST['detalle_jubilacion'];
            $fecha_ingreso = date('Y-m-d');
            $experiencia = $_POST['experiencia'];
            $detalle_experiencia = $_POST['detalle_experiencia'];
            $directorioDeImagenes = "imagenes/";
            $rutaDeImagen1 = $directorioDeImagenes . basename($_FILES["foto1"]["name"]);
            $rutaDeImagen2 = $directorioDeImagenes . basename($_FILES["foto2"]["name"]);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], $rutaDeImagen1);
            move_uploaded_file($_FILES["foto2"]["tmp_name"], $rutaDeImagen2);

            $sql = "SELECT nombre_usuario FROM temporeros WHERE nombre_usuario = '$nombre'";
            $result = $conn->query($sql);

            $sql2 = "SELECT correo_usuario FROM temporeros WHERE correo_usuario = '$correo'";
            $result2 = $conn->query($sql2);

            if ($result->num_rows > 0 || $result2->num_rows > 0) {
                echo "El usuario ya existe en la base de datos.";
            } else {
                $sql_temporero = "INSERT INTO temporeros (nombre_usuario, fecha_nac, correo_usuario, clave, telefono, ciudad, direccion, fecha_de_ingreso, experiencia, detalle_experiencia, sistema_prevision, sistema_salud, jubilacion, detalle_jubilacion, foto1, foto2) 
                VALUES ('$nombre', '$fecha_nac', '$correo', '$clave', '$telefono', '$ciudad', '$direccion', '$fecha_ingreso', '$experiencia', '$detalle_experiencia', '$sistema_prevision', '$sistema_salud', '$jubilacion', '$detalle_jubilacion', '$rutaDeImagen1', '$rutaDeImagen2')";
            
                // Ejecuta la consulta
                $result = $conn->query($sql_temporero);
            
                if (!$result) {
                    die("Error al ejecutar la consulta: " . $conn->error);
                } else {
                    echo "Registro exitoso como Temporero";
                }
            }
        }
    } elseif (isset($_POST['btn-registrar-transportista'])) {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre_usuario'];
        $fecha_nac = $_POST['fecha_nac_transportista'];
        $correo = $_POST['correo_usuario'];
        $clave = $_POST['clave_usuario'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];

        $tipo_licencia = $_POST['tipo_licencia'];
        $rutaDeImagen1Licencia = "imagenes/" . basename($_FILES["foto1_licencia"]["name"]);
        $rutaDeImagen2Licencia = "imagenes/" . basename($_FILES["foto2_licencia"]["name"]);
        $rutaDeImagen1 = "imagenes/" . basename($_FILES["foto1"]["name"]);
        $rutaDeImagen2 = "imagenes/" . basename($_FILES["foto2"]["name"]);

        move_uploaded_file($_FILES["foto1_licencia"]["tmp_name"], $rutaDeImagen1Licencia);
        move_uploaded_file($_FILES["foto2_licencia"]["tmp_name"], $rutaDeImagen2Licencia);
        move_uploaded_file($_FILES["foto1"]["tmp_name"], $rutaDeImagen1);
        move_uploaded_file($_FILES["foto2"]["tmp_name"], $rutaDeImagen2);

        // Verificar si el usuario ya existe en la base de datos
        $sql_usuario_existente = "SELECT nombre_transportista FROM transportista WHERE nombre_transportista = '$nombre'";
        $result_usuario_existente = $conn->query($sql_usuario_existente);

        if ($result_usuario_existente->num_rows > 0) {
            echo "El usuario ya existe en la base de datos.";
        } else {
            if (!empty($_POST['patente1']) && !empty($_POST['modelo1'])) {
                // Insertar datos en la tabla "transportistas"
                $sql_transportista = "INSERT INTO transportista (nombre_transportista, fecha_nac_transportista, correo_transportista, clave_transportista, telefono_transportista, ciudad_transportista, direccion_transportista, tipo_licencia, foto1, foto2, foto1_licencia, foto2_licencia) 
                    VALUES ('$nombre', '$fecha_nac', '$correo', '$clave', '$telefono', '$ciudad', '$direccion', '$tipo_licencia', '$rutaDeImagen1', '$rutaDeImagen2', '$rutaDeImagen1Licencia', '$rutaDeImagen2Licencia')";

                // Ejecutar la consulta
                $result_transportista = $conn->query($sql_transportista);

                if (!$result_transportista) {
                    die("Error al ejecutar la consulta del transportista: " . $conn->error);
                } else {
                    // Obtener el ID del transportista recién insertado
                    $id_transportista = $conn->insert_id;

                    // Insertar datos en la tabla de vehículos
                    $patente1 = $_POST['patente1'];
                    $modelo1 = $_POST['modelo1'];

                    $sql_vehiculo = "INSERT INTO vehiculos (id_transportista, patente_vehiculo, modelo_vehiculo) VALUES ('$id_transportista', '$patente1', '$modelo1')";

                    // Ejecutar la consulta del vehículo
                    $result_vehiculo = $conn->query($sql_vehiculo);

                    if (!$result_vehiculo) {
                        die("Error al ejecutar la consulta del vehículo: " . $conn->error);
                    }

                    echo "Registro exitoso como Transportista";
                }
            } else {
                echo "Debe proporcionar al menos un vehículo.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="css/register.css">
        <script>
        function toggleDetallesJubilacion() {
            var checkBox = document.getElementById("jubilado");
            var textArea = document.getElementById("detalles_jubilacion");
            var label = document.getElementById("label_detalles_jubilacion");
            
            if (checkBox.checked == true){
                textArea.style.display = "block";
                label.style.display = "block";
            } else {
                textArea.style.display = "none";
                label.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Registro SolarUSM</h1>
        
        <button onclick="mostrarFormulario('temporero')">Registrarse como Temporero</button>
        <button onclick="mostrarFormulario('transportista')">Registrarse como Transportista</button>
        <button onclick="mostrarFormulario('contratista')">Registrarse como Contratista</button>

        <!-- Formulario para Temporero -->
        <div id="formulario-temporero" style="display: none;">
        <h2>Registro de Temporero</h2>
            <form method="POST" id="form-temporero">
                    <label for="nombre_usuario">Nombre:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" required>

                    <label for="fecha_nac">Fecha Nacimiento:</label>
                    <input type="date" class="form-control" name="fecha_nac" placeholder="Indique su Fecha Nacimiento">

                    <label for="correo_usuario">Correo Electrónico:</label>
                    <input type="email" id="correo_usuario" name="correo_usuario" required>

                    <label for="clave_usuario">Contraseña:</label>
                    <input type="password" id="clave_usuario" name="clave_usuario" required>

                    <label for="telefono_usuario">Teléfono:</label>
                    <input type="tel" class="form-control" name="telefono" placeholder="Escriba su número de teléfono">

                    <label for="ciudad_usuario">Ciudad:</label>
                    <input type="text" class="form-control" name="ciudad" placeholder="Escriba su ciudad">

                    <label for="direccion_usuario">Dirección:</label>
                    <input type="text" class="form-control" name="direccion" placeholder="Escriba su dirección">

                    <label for="sistema_prevision">Sistema de prevision:</label>
                    <input type="text" class="form-control" name="sistema_prevision" placeholder="Escriba el nombre de su sistema de previsión">

                    <label for="sistema_salud">Sistema de salud:</label>
                    <input type="text" class="form-control" name="sistema_salud" placeholder="Escriba el nombre de su sistema de salud">

                    <label for="jubilado">¿Está jubilado?</label>
                    <input type="checkbox" id="jubilado" name="jubilacion" value="1" onclick="toggleDetallesJubilacion()">

                    <label for="detalles_jubilacion">Detalles de Jubilación (omitir este campo si lo desea):</label>
                    <input type="text" class="form-control" name="detalle_jubilacion" placeholder="(omitir este campo si lo desea)">

                    <label for="experiencia">Años de experiencia:</label>
                    <input type="text" class="form-control" name="experiencia" placeholder="Escriba sus años de experiencia">

                    <label for="detalle_experiencia">Historial de trabajos:</label>
                    <input type="text" class="form-control" name="detalle_experiencia" placeholder="Escriba su experiencia en los trabajos">

                    <label for="foto1">Cédula identidad cara 1/Visa cara 1:</label>
                    <input type="file" id="foto1" name="foto1" required>

                    <label for="foto2">Cédula identidad cara 2/Visa cara 2:</label>
                    <input type="file" id="foto2" name="foto2" required>

                    <input type="submit" value="Registrarse" name="btn-registrar-temporero">
            </form>
        </div>

        <!-- Formulario para Transportista -->
        <div id="formulario-transportista" style="display: none;">
        <h2>Registro de Transportista</h2>
            <form method="POST" enctype="multipart/form-data">

                <label for="nombre_usuario">Nombre:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>

                <label for="fechanac_transportista">Fecha Nacimiento</label>
                <input type="date" class="form-control" name = "fecha_nac_transportista" placeholder="Indique su Fecha Nacimiento">

                <label for="correo_usuario">Correo Electrónico:</label>
                <input type="email" id="correo_usuario" name="correo_usuario" required>
                
                <label for="clave_usuario">Contraseña:</label>
                <input type="password" id="clave_usuario" name="clave_usuario" required>

                <label for="telefono_usuario">Teléfono</label>
                <input type="tel" class="form-control" name="telefono" placeholder="Escriba su número de teléfono" required>

                <label for="ciudad_usuario">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" placeholder="Escriba su ciudad">

                <label for="direccion_usuario">Dirección</label>
                <input type="text" class="form-control" name="direccion" placeholder="Escriba su dirección">

                <label for="tipo_licencia">Tipo de licencia</label>
                <input type="text" class="form-control" name="tipo_licencia" placeholder="Ejemplo: Clase-A" required>

                <label for="foto1_licencia">Licencia de conducir cara 1:</label>
                <input type="file" id="foto1_licencia" name="foto1_licencia" required>

                <label for="foto2_licencia">Licencia de conducir cara 2:</label>
                <input type="file" id="foto2_licencia" name="foto2_licencia" required>

                <label for="foto1">Cédula identidad cara 1/Visa cara 1:</label>
                <input type="file" id="foto1" name="foto1" required>

                <label for="foto2">Cédula identidad cara 2/Visa cara 2:</label>
                <input type="file" id="foto2" name="foto2" required>

                <label for="patente1">Patente del vehículo:</label>
                <input type="text" name="patente1" required>

                <label for="modelo1">Modelo del vehículo:</label>
                <input type="text" name="modelo1" required>

                <input type="submit" value="Registrarse" name="btn-registrar-transportista">

            </form>
        </div>

         <!-- Formulario para Contratista -->
        <div id="formulario-contratista" style="display: none;">
            <h2>Registro de Contratista</h2>
            <form method="POST" id="form-contratista">
                <label for="runcontratista">RUN del Contratista:</label>
                <input type="text" name="runcontratista" required placeholder="Ejemplo: 12345678-9">
                
                <label for="nombre">Nombre del Contratista:</label>
                <input type="text" name="nombre" required placeholder="Ejemplo: Juan Pérez">

                <label for="clave">Clave:</label>
                <input type="password" name="clave" required placeholder="Introduce tu contraseña">

                <label for="contacto">Contacto:</label>
                <input type="text" name="contacto" required placeholder="Ejemplo: María López">

                <label for="telcontacto">Teléfono de Contacto:</label>
                <input type="tel" name="telcontacto" required placeholder="Ejemplo: +56 9 1234 5678">

                <label for="whatsapp">Número de WhatsApp:</label>
                <input type="tel" name="whatsapp" required placeholder="Ejemplo: +56 9 1234 5678">

                <label for="correo">Correo Electrónico:</label>
                <input type="email" name="correo" required placeholder="Ejemplo: correo@ejemplo.com">

                <input type="submit" value="Registrar Contratista" name="btn-registrar-contratista">
            </form>
        </div>


        <div class="links">
            <a href="logint.php">¿Ya tienes cuenta como temporero?</a>
            <a href="loginc.php">¿Ya tienes cuenta como contratista?</a>
            <a href="logintrans.php">¿Ya tienes cuenta como transportista?</a>
        </div>

        <script>
            function mostrarFormulario(tipo) {
                var formularios = document.querySelectorAll('div[id^="formulario-"]');
                for (var i = 0; i < formularios.length; i++) {
                    formularios[i].style.display = 'none';
                }

                document.getElementById("formulario-" + tipo).style.display = "block";

                // Actualiza el valor del campo oculto 'tipo_usuario'
                document.getElementById("tipo_usuario").value = tipo;
            }
        </script>
    </div>
</body>
</html>