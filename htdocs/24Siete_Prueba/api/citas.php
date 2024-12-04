<?php
header("Content-Type: application/json");
header("Content-Type: application/json");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: DELETE, GET, PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
$servername = "localhost";
$username = "CRUD";
$password = "]][sv)ncWfB/khVr";
$dbname = "prueba tecnica 24siete";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $requestUri[2]; // Ejemplo: "citas"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;

switch ($method) {
    case 'GET':
        if ($resource === 'citas' && $id==null) {
            // Obtener todas las citas
            $result = $conn->query("SELECT * FROM Citas");
            $citas = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($citas);
        } elseif ($id==null && isSet($_GET['IDDoctor'])) {
            // Obtener servicios según IDDoctor
            $IDDoctor = intval($_GET['IDDoctor']);
            $result = $conn->query("SELECT * FROM Citas WHERE DoctorID = $IDDoctor");
            $cita = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($cita);   
        } elseif ($id==null && isSet($_GET['IDPaciente'])) {
            // Obtener servicios según IDDoctor
            $IDPaciente = intval($_GET['IDPaciente']);
            $result = $conn->query("SELECT * FROM Citas WHERE PacienteID = $IDPaciente");
            $cita = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($cita);        
        } elseif ($resource === 'citas' && $id) {
            // Obtener una cita específica
            $result = $conn->query("SELECT * FROM Citas WHERE ID = $id");
            $cita = $result->fetch_assoc();
            echo json_encode($cita);
        }
        break;

    case 'POST':
        if ($resource === 'citas') {
            // Crear una nueva cita
            $data = json_decode(file_get_contents("php://input"), true);
            $DoctorID = $data['DoctorID'];
            $PacienteID = $data['PacienteID'];
            $FechaCita = $data['FechaCita'];
            $HoraCita = $data['HoraCita'];
            $Titulo = $data['Titulo'];
            $Estado = $data['Estado'];
            $sql = "INSERT INTO Citas (DoctorID, PacienteID, FechaCita, HoraCita, Titulo, Estado) VALUES ($DoctorID, $PacienteID, '$FechaCita','$HoraCita', '$Titulo','$Estado')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Cita creada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear cita"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'citas' && $id) {
            // Actualizar una cita
            $data = json_decode(file_get_contents("php://input"), true);
            $DoctorID = $data['DoctorID'];
            $PacienteID = $data['PacienteID'];
            $FechaCita = $data['FechaCita'];
            $HoraCita = $data['HoraCita'];
            $Titulo = $data['Titulo'];
            $Estado = $data['Estado'];
            $sql = "UPDATE Citas SET DoctorID=$DoctorID, PacienteID=$PacienteID, HoraCita='$HoraCita', FechaCita='$FechaCita', Titulo='$Titulo', Estado='$Estado' WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Cita actualizada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar cita"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'citas' && $id) {
            // Eliminar una cita
            $sql = "DELETE FROM Citas WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Cita eliminada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar cita"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
