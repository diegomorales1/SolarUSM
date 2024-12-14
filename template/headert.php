<?php

if(!isset ($_SESSION))

// Inicializar la sesion
session_start();
$nombre_actual = $_SESSION["nombre_u"];

// Conexion a la base de datos
require_once "conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio web</title>

    <link rel = "stylesheet" href="./css/bootstrap.min.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <ul class="nav navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="trabajos_transportistas.php">SolarUSM 🌿</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trabajos_transportistas.php">Transportes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="perfilt.php">Mi Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="desconectar.php">Desconectar</a>
            </li>
        </ul>
        <div>Bienvenido: <?php echo $nombre_actual ?></div>
    </nav>
    <div class="container">
        <div class = "row">