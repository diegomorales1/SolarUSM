<?php
ob_start();
include("template/headerc.php");
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
    $id_u = $_SESSION["idusuario"];

    $query = "SELECT runcontratista, nombre, contacto, telcontacto, whatsapp, correo, clave
          FROM contratista
          WHERE runcontratista = '$id_u'
          ORDER BY runcontratista ASC";

    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Agrega esta línea para verificar el contenido de $row
            var_dump($row);

            echo "<div class='profile-container'>";
            echo "<h2>Perfil de " . $row['nombre'] . "</h2>";
            echo "<div class='info-container'>";
            echo "<p><strong>Correo:</strong> " . $row['correo'] . "</p>";
            echo "<p><strong>Contacto:</strong> " . $row['contacto'] . "</p>";
            echo "<p><strong>Teléfono de Contacto:</strong> " . $row['telcontacto'] . "</p>";
            echo "<p><strong>Número de WhatsApp:</strong> " . $row['whatsapp'] . "</p>";
            echo "</div>"; // Cierre del div 'info-container'
            echo "</div>"; // Cierre del div 'profile-container'
        }
    }

} else {
    // Redirige al usuario a la página de inicio de sesión o muestra un mensaje.
    header("Location: login.php");
    exit();
}

include("template/footer.php");
?>

</body>
</html>
