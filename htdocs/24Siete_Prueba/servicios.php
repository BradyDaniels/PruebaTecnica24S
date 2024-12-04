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

// Obtener todos los servicios
$servicios = [];
$result = $conn->query("SELECT ID, Titulo, Precio, IDDoctor FROM Servicios");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
}

// Obtener todas las especialidad
$especialidad = [];
$result = $conn->query("SELECT ID, Titulo FROM especialidad");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $especialidad[] = $row;
    }
}

// Obtener todos los doctor
$doctor = [];
$result = $conn->query("SELECT ID, NombreCompleto FROM doctor");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $doctor[] = $row;
    }
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de un nuevo servicio
        $Titulo = $_POST['Titulo'];
        $Precio = $_POST['Precio'];
        $IDEspecialidad = $_POST['IDEspecialidad'];
        $IDDoctor = $_POST['IDDoctor'];

        $sql = "INSERT INTO Servicios (Titulo, Precio, IDEspecialidad, IDDoctor) VALUES ('$Titulo', '$Precio', $IDEspecialidad, $IDDoctor)";

        if ($conn->query($sql) === TRUE) {
            echo "Nuevo servicio creado exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de un servicio
        $servicioID = $_POST['servicioID'];
        $Titulo = $_POST['Titulo'];
        $Precio = $_POST['Precio'];
        $IDEspecialidad = $_POST['IDEspecialidad'];
        $IDDoctor = $_POST['IDDoctor'];

        $sql = "UPDATE Servicios SET Titulo='$Titulo', Precio='$Precio', IDEspecialidad=$IDEspecialidad, IDDoctor=$IDDoctor WHERE ID=$servicioID";

        if ($conn->query($sql) === TRUE) {
            echo "Servicio actualizado exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de un servicio
        $servicioID = $_POST['servicioID'];

        $sql = "DELETE FROM Servicios WHERE ID=$servicioID";

        if ($conn->query($sql) === TRUE) {
            echo "Servicio eliminado exitosamente";
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
    <title>Gestión de Servicios</title>
</head>
<body>

<h2>Insertar Servicio</h2>
<form method="POST">
    Titulo: <input type="text" name="Titulo" required><br>
    Precio: <input type="text" name="Precio" required><br>
    Especialidad: 
    <select name="IDEspecialidad" required>
        <option value="">Seleccione una especialidad</option>
        <?php foreach ($especialidad as $especialidad): ?>
            <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Doctor: 
    <select name="IDDoctor" required>
        <option value="">Seleccione un doctor</option>
        <?php foreach ($doctor as $doctor): ?>
            <option value="<?php echo $doctor['ID']; ?>"><?php echo $doctor['NombreCompleto']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Servicio</h2>
<form method="POST">
    Servicio: 
    <select name="servicioID" required onchange="this.form.submit()">
        <option value="">Seleccione un servicio</option>
        <?php foreach ($servicios as $servicio): ?>
            <option value="<?php echo $servicio['ID']; ?>"><?php echo $servicio['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <?php if (isset($_POST['servicioID'])): ?>
        <?php
        // Obtener los datos del servicio seleccionado
        $servicioID = $_POST['servicioID'];
        $sql = "SELECT * FROM Servicios WHERE ID=$servicioID";
        $result = $conn->query($sql);
        
        // Verificar si se encontró el servicio
        if ($result && $result->num_rows > 0) {
            $servicio = $result->fetch_assoc();
        ?>
            Titulo: <input type="text" name="Titulo" value="<?php echo $servicio['Titulo']; ?>" required><br>
            Precio: <input type="text" name="Precio" value="<?php echo $servicio['Precio']; ?>" required><br>
            Especialidad: 
            <select name="IDEspecialidad" required>
                <?php foreach ($especialidad as $especialidadItem): ?>
                    <option value="<?php echo $especialidadItem['ID']; ?>" <?php echo $especialidadItem['ID'] == $servicio['IDEspecialidad'] ? 'selected' : ''; ?>>
                        <?php echo $especialidadItem['Titulo']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            Doctor: 
            <select name="IDDoctor" required>
                <?php foreach ($doctor as $doctor): ?>
                    <option value="<?php echo $doctor['ID']; ?>" <?php echo $doctor['ID'] == $servicio['IDDoctor'] ? 'selected' : ''; ?>>
                        <?php echo $doctor['NombreCompleto']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php
        } else {
            echo "Servicio no encontrado.";
        }
        ?>
    <?php endif; ?>
</form>

<h2>Eliminar Servicio</h2>
<form method="POST">
    Servicio: 
    <select name="servicioID" required>
        <option value="">Seleccione un servicio</option>
        <?php foreach ($servicios as $servicio): ?>
            <option value="<?php echo $servicio['ID']; ?>"><?php echo $servicio['Titulo']; ?></option>
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