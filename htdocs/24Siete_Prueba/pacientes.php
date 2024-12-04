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

// Obtener todos los pacientes
$pacientes = [];
$result = $conn->query("SELECT ID, NombreCompleto, Telefono FROM Pacientes");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de un nuevo paciente
        $NombreCompleto = $_POST['NombreCompleto'];
        $Edad = $_POST['Edad'];
        $Telefono = $_POST['Telefono'];

        $sql = "INSERT INTO Pacientes (NombreCompleto, Telefono) VALUES ('$NombreCompleto', '$Telefono')";

        if ($conn->query($sql) === TRUE) {
            echo "Nuevo paciente creado exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de un paciente
        $pacienteID = $_POST['pacienteID'];
        $NombreCompleto = $_POST['NombreCompleto'];
        $Edad = $_POST['Edad'];
        $Telefono = $_POST['Telefono'];

        $sql = "UPDATE Pacientes SET NombreCompleto='$NombreCompleto', Telefono='$Telefono' WHERE ID=$pacienteID";

        if ($conn->query($sql) === TRUE) {
            echo "Paciente actualizado exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de un paciente
        $pacienteID = $_POST['pacienteID'];

        $sql = "DELETE FROM Pacientes WHERE ID=$pacienteID";

        if ($conn->query($sql) === TRUE) {
            echo "Paciente eliminado exitosamente";
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
    <title>Gestión de Pacientes</title>
</head>
<body>

<h2>Insertar Paciente</h2>
<form method="POST">
    NombreCompleto: <input type="text" name="NombreCompleto" required><br>
    Edad: <input type="number" name="Edad" required><br>
    Teléfono: <input type="text" name="Telefono" required><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Paciente</h2>
<form method="POST">
    Paciente: 
    <select name="pacienteID" required onchange="this.form.submit()">
        <option value="">Seleccione un paciente</option>
        <?php foreach ($pacientes as $paciente): ?>
            <option value="<?php echo $paciente['ID']; ?>"><?php echo $paciente['NombreCompleto'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <?php if (isset($_POST['pacienteID'])): ?>
        <?php
        // Obtener los datos del paciente seleccionado
        $pacienteID = $_POST['pacienteID'];
        $sql = "SELECT * FROM Pacientes WHERE ID=$pacienteID";
        $result = $conn->query($sql);
        
        // Verificar si se encontró el paciente
        if ($result && $result->num_rows > 0) {
            $paciente = $result->fetch_assoc();
        ?>
            NombreCompleto: <input type="text" name="NombreCompleto" value="<?php echo $paciente['NombreCompleto']; ?>" required><br>
            Teléfono: <input type="text" name="Telefono" value="<?php echo $paciente['Telefono']; ?>" required><br>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php
        } else {
            echo "Paciente no encontrado.";
        }
        ?>
    <?php endif; ?>
</form>

<h2>Eliminar Paciente</h2>
<form method="POST">
    Paciente: 
    <select name="pacienteID" required>
        <option value="">Seleccione un paciente</option>
        <?php foreach ($pacientes as $paciente): ?>
            <option value="<?php echo $paciente['ID']; ?>"><?php echo $paciente['NombreCompleto']; ?></option>
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