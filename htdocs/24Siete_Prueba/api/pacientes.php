<?php
header("Content-Type: application/json");
header("Content-Type: application/json");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, PUT, POST, OPTIONS");
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
$resource = $requestUri[2]; // Ejemplo: "pacientes"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;

switch ($method) {
    case 'GET':

        if ($resource === 'pacientes' && $id==null) {
            // Obtener todos los pacientes
            $result = $conn->query("SELECT * FROM Pacientes");
            $pacientes = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($pacientes);
        } elseif ($resource === 'pacientes' && $id) {
            // Obtener un paciente específico
            $result = $conn->query("SELECT * FROM Pacientes WHERE ID = $id");
            $paciente = $result->fetch_assoc();
            echo json_encode($paciente);
        }
        break;

    case 'POST':
        if ($resource === 'pacientes') {
            // Crear un nuevo paciente
            $data = json_decode(file_get_contents("php://input"), true);
            $NombreCompleto = $data['NombreCompleto'];
            $Telefono = $data['Telefono'];
            $sql = "INSERT INTO Pacientes (NombreCompleto, Telefono) VALUES ('$NombreCompleto', '$Telefono')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Paciente creado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear paciente"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'pacientes' && $id) {
            // Actualizar un paciente
            $data = json_decode(file_get_contents("php://input"), true);
            $NombreCompleto = $data['NombreCompleto'];
            $Telefono = $data['Telefono'];
            $sql = "UPDATE Pacientes SET NombreCompleto='$NombreCompleto', Telefono='$Telefono' WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Paciente actualizado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar paciente"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'pacientes' && $id) {
            // Eliminar un paciente
            $sql = "DELETE FROM Pacientes WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Paciente eliminado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar paciente"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
