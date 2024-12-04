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

// Obtener todos los doctores
$doctores = [];
$result = $conn->query("SELECT ID, NombreCompleto, Telefono, UbicacionID, EspecialidadID FROM Doctor");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $doctores[] = $row;
    }
}

// Obtener todas las especialidades
$especialidades = [];
$result = $conn->query("SELECT ID, Titulo FROM Especialidad");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
}

// Obtener ubicaciones
$ubicaciones = [];
$result = $conn->query("SELECT ID, Titulo FROM Ubicacion");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ubicaciones[] = $row;
    }
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de un nuevo doctor
        $nombreCompleto = $_POST['nombreCompleto'];
        $telefono = $_POST['telefono'];
        $ubicacionID = $_POST['ubicacionID'];
        $especialidadID = $_POST['especialidadID'];

        $sql = "INSERT INTO Doctor (NombreCompleto, Telefono, UbicacionID, EspecialidadID) VALUES ('$nombreCompleto', '$telefono', $ubicacionID, $especialidadID)";

        if ($conn->query($sql) === TRUE) {
            echo "Nuevo doctor creado exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de un doctor
        $doctorID = $_POST['doctorID'];
        $nombreCompleto = $_POST['nombreCompleto'];
        $telefono = $_POST['telefono'];
        $ubicacionID = $_POST['ubicacionID'];
        $especialidadID = $_POST['especialidadID'];

        $sql = "UPDATE Doctor SET NombreCompleto='$nombreCompleto', Telefono='$telefono', UbicacionID=$ubicacionID, EspecialidadID=$especialidadID WHERE ID=$doctorID";

        if ($conn->query($sql) === TRUE) {
            echo "Doctor actualizado exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de un doctor
        $doctorID = $_POST['doctorID'];

        $sql = "DELETE FROM Doctor WHERE ID=$doctorID";

        if ($conn->query($sql) === TRUE) {
            echo "Doctor eliminado exitosamente";
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
    <title>Gestión de Doctores</title>
</head>
<body>

<h2>Insertar Doctor</h2>
<form method="POST">
    Titulo Completo: <input type="text" name="nombreCompleto" required><br>
    Teléfono: <input type="text" name="telefono" required><br>
    Ubicación: 
    <select name="ubicacionID" required>
        <option value="">Seleccione una ubicación</option>
        <?php foreach ($ubicaciones as $ubicacion): ?>
            <option value="<?php echo $ubicacion['ID']; ?>"><?php echo $ubicacion['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Especialidad: 
    <select name="especialidadID" required>
        <option value="">Seleccione una especialidad</option>
        <?php foreach ($especialidades as $especialidad): ?>
            <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Titulo']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Doctor</h2>
<form method="POST">
    Doctor: 
    <select name="doctorID" required onchange="this.form.submit()">
        <option value="">Seleccione un doctor</option>
        <?php foreach ($doctores as $doctor): ?>
            <option value="<?php echo $doctor['ID']; ?>"><?php echo $doctor['NombreCompleto']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <?php if (isset($_POST['doctorID'])): ?>
        <?php
        // Obtener los datos del doctor seleccionado
        $doctorID = $_POST['doctorID'];
        $sql = "SELECT * FROM Doctor WHERE ID=$doctorID";
        $result = $conn->query($sql);
        
        // Verificar si se encontró el doctor
        if ($result && $result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
        ?>
            Titulo Completo: <input type="text" name="nombreCompleto" value="<?php echo $doctor['NombreCompleto']; ?>" required><br>
            Teléfono: <input type="text" name="telefono" value="<?php echo $doctor['Telefono']; ?>" required><br>
            Ubicación: 
            <select name="ubicacionID" required>
                <?php foreach ($ubicaciones as $ubicacion): ?>
                    <option value="<?php echo $ubicacion['ID']; ?>" <?php echo $ubicacion['ID'] == $doctor['UbicacionID'] ? 'selected' : ''; ?>>
                        <?php echo $ubicacion['Titulo']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            Especialidad: 
            <select name="especialidadID" required>
                <?php foreach ($especialidades as $especialidad): ?>
                    <option value="<?php echo $especialidad['ID']; ?>" <?php echo $especialidad['ID'] == $doctor['EspecialidadID'] ? 'selected' : ''; ?>>
                        <?php echo $especialidad['Titulo']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php
        } else {
            echo "Doctor no encontrado.";
        }
        ?>
    <?php endif; ?>
</form>

<h2>Eliminar Doctor</h2>
<form method="POST">
    Doctor: 
    <select name="doctorID" required>
        <option value="">Seleccione un doctor</option>
        <?php foreach ($doctores as $doctor): ?>
            <option value="<?php echo $doctor['ID']; ?>"><?php echo $doctor['NombreCompleto']; ?></option>
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