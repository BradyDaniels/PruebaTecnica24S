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

// Obtener todas las especialidades
$especialidades = [];
$result = $conn->query("SELECT ID, Titulo FROM Especialidad");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de una nueva especialidad
        $nombreEspecialidad = $_POST['nombreEspecialidad'];

        $sql = "INSERT INTO Especialidad (Titulo) VALUES ('$nombreEspecialidad')";

        if ($conn->query($sql) === TRUE) {
            echo "Nueva especialidad creada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de una especialidad
        $especialidadID = $_POST['especialidadID'];
        $nuevoNombre = $_POST['nuevoNombre'];

        $sql = "UPDATE Especialidad SET Titulo='$nuevoNombre' WHERE ID=$especialidadID";

        if ($conn->query($sql) === TRUE) {
            echo "Especialidad actualizada exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de una especialidad
        $especialidadID = $_POST['especialidadID'];

        $sql = "DELETE FROM Especialidad WHERE ID=$especialidadID";

        if ($conn->query($sql) === TRUE) {
            echo "Especialidad eliminada exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// No cerrar la conexión aquí
?>

<!-- Formulario HTML para insertar, actualizar y eliminar -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Especialidades</title>
</head>
<body>

<h2>Insertar Especialidad</h2>
<form method="POST">
    Titulo de la Especialidad: <input type="text" name="nombreEspecialidad" required><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Especialidad</h2>
<form method="POST">
    Especialidad: 
    <select name="especialidadID" required onchange="this.form.submit()">
        <option value="">Seleccione una especialidad</option>
        <?php foreach ($especialidades as $especialidad): ?>
            <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <?php if (isset($_POST['especialidadID'])): ?>
        <?php
        // Obtener los datos de la especialidad seleccionada
        $especialidadID = $_POST['especialidadID'];
        $sql = "SELECT * FROM Especialidad WHERE ID=$especialidadID";
        $result = $conn->query($sql);
        
        // Verificar si se encontró la especialidad
        if ($result && $result->num_rows > 0) {
            $especialidad = $result->fetch_assoc();
        ?>
            Nuevo Titulo: <input type="text" name="nuevoNombre" value="<?php echo $especialidad['Titulo']; ?>" required><br>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php
        } else {
            echo "Especialidad no encontrada.";
        }
        ?>
    <?php endif; ?>
</form>

<h2>Eliminar Especialidad</h2>
<form method="POST">
    Especialidad: 
    <select name="especialidadID" required>
        <option value="">Seleccione una especialidad</option>
        <?php foreach ($especialidades as $especialidad): ?>
            <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit" name="eliminar">Eliminar</button>
</form>

</body>
</html>

<?php
// Cerrar la conexión al final del archivo
$conn->close();
?>
