<?php
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
$resource = $requestUri[2]; // Ejemplo: "especialidades"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;

switch ($method) {
    case 'GET':
        if ($resource === 'especialidades' && $id==null) {
            // Obtener todas las especialidades
            $result = $conn->query("SELECT * FROM Especialidad");
            $especialidades = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($especialidades);
        } elseif ($resource === 'especialidades' && $id) {
            // Obtener una especialidad específica
            $result = $conn->query("SELECT * FROM Especialidad WHERE ID = $id");
            $especialidad = $result->fetch_assoc();
            echo json_encode($especialidad);
        }
        break;

    case 'POST':
        if ($resource === 'especialidades') {
            // Crear una nueva especialidad
            $data = json_decode(file_get_contents("php://input"), true);
            $Titulo = $data['Titulo'];
            $sql = "INSERT INTO Especialidad (Titulo) VALUES ('$Titulo')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Especialidad creada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear especialidad"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'especialidades' && $id) {
            // Actualizar una especialidad
            $data = json_decode(file_get_contents("php://input"), true);
            $Titulo = $data['Titulo'];
            $sql = "UPDATE Especialidad SET Titulo='$Titulo' WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Especialidad actualizada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar especialidad"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'especialidades' && $id) {
            // Eliminar una especialidad
            $sql = "DELETE FROM Especialidad WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Especialidad eliminada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar especialidad"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>