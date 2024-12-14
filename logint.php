<?php 
// Inicializar la sesion
session_start();

/*
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location:trabajos.php");;
    exit;
}*/

include "conexion.php";

if ($_POST){
    $nombreUsuario = $_POST["nombre_usuario"]; 
    $contrasena = $_POST["contrasena_usuario"];

    // Consulta SQL para obtener el hash de la contraseña del usuario
    $sql = "SELECT clave FROM temporeros WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Comprobar si se encontró algún resultado
    if ($result->num_rows > 0) {
        // Obtener el hash de la contraseña almacenada en la base de datos
        $row = $result->fetch_assoc();
        $hashContrasena = $row["clave"];
        // Verificar si la contraseña coincide con el hash almacenado
        if ($contrasena === $hashContrasena){
            $_SESSION["loggedin"] = true;

            //Esto extrae info usuario
            $query = "SELECT * FROM temporeros WHERE nombre_usuario = '$nombreUsuario'";
            $result = $conn->query($query);
            $row2 = $result->fetch_assoc();
            $id_usuario = $row2['id_usuario'];
            $nombre_usuario_actual = $row2['nombre_usuario'];
            //SESSION que guarda toda la info del usuario
            $_SESSION["idusuario"] = $row2;
            $_SESSION["nombre_u"] = $nombre_usuario_actual;

            header("location:trabajos.php");
        } else {
            echo '<div class="alert alert-danger alert-center" role="alert">El usuario y/o la contraseña son incorrectos.</div>';;
        }
    } else {
        echo '<div class="alert alert-danger alert-center" role="alert">El usuario y/o la contraseña son incorrectos.</div>';;
    }
}



?>

<!doctype html>
<html lang="en">
  <head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
    <style>
    .alert-center {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 10vh; /* Esto centra verticalmente en toda la altura de la ventana */
    }
    </style>

</head>
  <body>
      
    <div class="container">
        <div class="row">
        <div class="col-md-4">
        </div>
            <div class="col-md-4">
<br><br><br><br>
                <div class="card">
                    <div class="card-header">
                        Inicio de sesion 🌿 SolarUSM
                    </div>
                    <div class="card-body">
                        <!-- contenedor del login -->
                        <form method="POST">
                            <div class = "form-group">
                                <label for="exampleInputEmail1">Usuario</label>
                                <input type="text" class="form-control" name = "nombre_usuario"  placeholder="Escribe tu usuario" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Contraseña</label>
                                <input type="password" class="form-control" name = "contrasena_usuario" placeholder="Escribe tu contraseña" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Ingresar</button>
                            <a href="register.php">¿Aun no tienes cuenta?</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
