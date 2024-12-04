<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "CRUD";
$password = "]][sv)ncWfB/khVr";
$dbname = "prueba tecnica 24siete";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de una nueva ubicación
        $nombreUbicacion = $_POST['nombreUbicacion'];

        $sql = "INSERT INTO Ubicacion (Titulo) VALUES ('$nombreUbicacion')";

        if ($conn->query($sql) === TRUE) {
            echo "Nueva ubicación creada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de una ubicación
        $ubicacionID = $_POST['ubicacionID'];
        $nuevoNombre = $_POST['nuevoNombre'];

        $sql = "UPDATE Ubicacion SET Titulo='$nuevoNombre' WHERE ID=$ubicacionID";

        if ($conn->query($sql) === TRUE) {
            echo "Ubicación actualizada exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de una ubicación
        $ubicacionID = $_POST['ubicacionID'];

        $sql = "DELETE FROM Ubicacion WHERE ID=$ubicacionID";

        if ($conn->query($sql) === TRUE) {
            echo "Ubicación eliminada exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Obtener las ubicaciones disponibles
$ubicaciones = [];
$result = $conn->query("SELECT ID, Titulo FROM Ubicacion");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ubicaciones[] = $row;
    }
}

$conn->close();
?>

<!-- Formulario HTML para insertar, actualizar y eliminar -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ubicaciones</title>
</head>
<body>

<h2>Insertar Ubicación</h2>
<form method="POST">
    Nombre de la Ubicación: <input type="text" name="nombreUbicacion" required><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Ubicación</h2>
<form method="POST">
    Ubicación: 
    <select name="ubicacionID" required>
        <option value="">Seleccione una ubicación</option>
        <?php foreach ($ubicaciones as $ubicacion): ?>
            <option value="<?php echo $ubicacion['ID']; ?>"><?php echo $ubicacion['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Nuevo Nombre: <input type="text" name="nuevoNombre" required><br>
    <button type="submit" name="actualizar">Actualizar</button>
</form>

<h2>Eliminar Ubicación</h2>
<form method="POST">
    Ubicación: 
    <select name="ubicacionID" required>
        <option value="">Seleccione una ubicación</option>
        <?php foreach ($ubicaciones as $ubicacion): ?>
            <option value="<?php echo $ubicacion['ID']; ?>"><?php echo $ubicacion['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit" name="eliminar">Eliminar</button>
</form>

</body>
</html>
