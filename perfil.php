<?php
ob_start();
include("template/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Perfil</title>
</head>
<body>

<?php
// Asegúrate de que la sesión está iniciada y la clave "idusuario" está definida
if (isset($_SESSION["idusuario"])) {
    $dato_usuario = $_SESSION["idusuario"];
    $id_u = $dato_usuario["id_temporero"];

    // Consulta para obtener la información específica del usuario
    $stmt = $conn->prepare("SELECT nombre_usuario, correo_usuario, telefono, ciudad, direccion FROM temporeros WHERE id_temporero = '$id_u'");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            // El usuario existe, muestra la información
            $row = $result->fetch_assoc();

            // Mostrar la información en HTML con un estilo mejorado
            echo "<div class='profile-container'>";
            echo "<h2>Perfil de " . $row['nombre_usuario'] . "</h2>";
            echo "<div class='info-container'>";
            echo "<p><strong>Correo:</strong> " . $row['correo_usuario'] . "</p>";
            echo "<p><strong>Teléfono:</strong> " . $row['telefono'] . "</p>";
            echo "<p><strong>Ciudad:</strong> " . $row['ciudad'] . "</p>";
            echo "<p><strong>Dirección:</strong> " . $row['direccion'] . "</p>";
            echo "</div>"; // Cierre del div 'info-container'
            echo "</div>"; // Cierre del div 'profile-container'
        } else {
            // El usuario no existe
            echo "Usuario no encontrado";
        }
    } else {
        // Error en la consulta
        echo "Error al obtener la información del usuario: " . $conn->error;
    }

    // Cierra la conexión
    $stmt->close();
    $conn->close();
} else {
    // Redirige al usuario a la página de inicio de sesión o muestra un mensaje.
    header("Location: login.php");
    exit();
}

include("template/footer.php");
?>

</body>
</html>