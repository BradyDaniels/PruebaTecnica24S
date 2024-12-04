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
$result = $conn->query("SELECT ID, NombreCompleto FROM Pacientes");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Obtener todos los Doctor
$Doctor = [];
$result = $conn->query("SELECT ID, NombreCompleto FROM Doctor");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $Doctor[] = $row;
    }
}

// Obtener todas las citas
$citas = [];
$result = $conn->query("SELECT Citas.ID, Pacientes.NombreCompleto AS PacienteNombreCompleto, Doctor.NombreCompleto AS DoctorNombreCompleto, FechaCita, HoraCita FROM Citas JOIN Pacientes ON Citas.PacienteID = Pacientes.ID JOIN Doctor ON Citas.DoctorID = Doctor.ID");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $citas[] = $row;
    }
}

// Verificar qué acción se está realizando
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertar'])) {
        // Inserción de una nueva cita
        $PacienteID = $_POST['PacienteID'];
        $DoctorID = $_POST['DoctorID'];
        $FechaCita = $_POST['FechaCita'];
        $HoraCita = $_POST['HoraCita'];

        $sql = "INSERT INTO Citas (PacienteID, DoctorID, FechaCita, HoraCita) VALUES ($PacienteID, $DoctorID, '$FechaCita', '$HoraCita')";

        if ($conn->query($sql) === TRUE) {
            echo "Nueva cita creada exitosamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['actualizar'])) {
        // Actualización de una cita
        $CitaID = $_POST['CitaID'];
        $PacienteID = $_POST['PacienteID'];
        $DoctorID = $_POST['DoctorID'];
        $FechaCita = $_POST['FechaCita'];
        $HoraCita = $_POST['HoraCita'];

        $sql = "UPDATE Citas SET PacienteID=$PacienteID, DoctorID=$DoctorID, FechaCita='$FechaCita', HoraCita='$HoraCita' WHERE ID=$CitaID";

        if ($conn->query($sql) === TRUE) {
            echo "Cita actualizada exitosamente";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['eliminar'])) {
        // Eliminación de una cita
        $CitaID = $_POST['CitaID'];

        $sql = "DELETE FROM Citas WHERE ID=$CitaID";

        if ($conn->query($sql) === TRUE) {
            echo "Cita eliminada exitosamente";
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
    <title>Gestión de Citas</title>
</head>
<body>

<h2>Insertar Cita</h2>
<form method="POST">
    Paciente: 
    <select name="PacienteID" required>
        <option value="">Seleccione un paciente</option>
        <?php foreach ($pacientes as $paciente): ?>
            <option value="<?php echo $paciente['ID']; ?>"><?php echo $paciente['NombreCompleto']; ?></option>
        <?php endforeach; ?>
    </select><br>

    Doctor: 
    <select name="DoctorID" required>
        <option value="">Seleccione un doctor</option>
        <?php foreach ($Doctor as $doctor): ?>
            <option value="<?php echo $doctor['ID']; ?>"><?php echo $doctor['NombreCompleto']; ?></option>
        <?php endforeach; ?>
    </select><br>

    FechaCita: <input type="date" name="FechaCita" required><br>
    HoraCita: <input type="time" name="HoraCita" required><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Actualizar Cita</h2>
<form method="POST">
    Cita: 
    <select name="CitaID" required onchange="this.form.submit()">
        <option value="">Seleccione una cita</option>
        <?php foreach ($citas as $cita): ?>
            <option value="<?php echo $cita['ID']; ?>"><?php echo $cita['PacienteNombreCompleto'] . ' ' . ' - ' . $cita['DoctorNombreCompleto'] . ' - ' . $cita['FechaCita'] . ' ' . $cita['HoraCita']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <?php if (isset($_POST['CitaID'])): ?>
        <?php
        // Obtener los datos de la cita seleccionada
        $CitaID = $_POST['CitaID'];
        $sql = "SELECT * FROM Citas WHERE ID=$CitaID";
        $result = $conn->query($sql);
        
        // Verificar si se encontró la cita
        if ($result && $result->num_rows > 0) {
            $cita = $result->fetch_assoc();
        ?>
            Paciente: 
            <select name="PacienteID" required>
                <?php foreach ($pacientes as $paciente): ?>
                    <option value="<?php echo $paciente['ID']; ?>" <?php echo $paciente['ID'] == $cita['PacienteID'] ? 'selected' : ''; ?>>
                        <?php echo $paciente['NombreCompleto']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            Doctor: 
            <select name="DoctorID" required>
                <?php foreach ($Doctor as $doctor): ?>
                    <option value="<?php echo $doctor['ID']; ?>" <?php echo $doctor['ID'] == $cita['DoctorID'] ? 'selected' : ''; ?>>
                        <?php echo $doctor['NombreCompleto']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            FechaCita: <input type="date" name="FechaCita" value="<?php echo $cita['FechaCita']; ?>" required><br>
            HoraCita: <input type="time" name="HoraCita" value="<?php echo $cita['HoraCita']; ?>" required><br>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php
        } else {
            echo "Cita no encontrada.";
        }
        ?>
    <?php endif; ?>
</form>

<h2>Eliminar Cita</h2>
<form method="POST">
    Cita: 
    <select name="CitaID" required>
        <option value="">Seleccione una cita</option>
        <?php foreach ($citas as $cita): ?>
            <option value="<?php echo $cita['ID']; ?>"><?php echo $cita['PacienteNombreCompleto'] . ' ' . ' - ' . $cita['DoctorNombreCompleto'] . ' - ' . $cita['FechaCita'] . ' ' . $cita['HoraCita']; ?></option>
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