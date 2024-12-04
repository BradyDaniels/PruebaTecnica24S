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
$resource = $requestUri[2]; // Ejemplo: "ubicaciones"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;
switch ($method) {
    case 'GET':
        if ($resource === 'ubicaciones' && $id==null) {
            // Obtener todas las ubicaciones
            $result = $conn->query("SELECT * FROM Ubicacion");
            $ubicaciones = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($ubicaciones);
        } elseif ($resource === 'ubicaciones' && $id) {
            // Obtener una ubicación específica
            $result = $conn->query("SELECT * FROM Ubicacion WHERE ID = $id");
            $ubicacion = $result->fetch_assoc();
            echo json_encode($ubicacion);
        }
        break;

    case 'POST':
        if ($resource === 'ubicaciones') {
            // Crear una nueva ubicación
            $data = json_decode(file_get_contents("php://input"), true);
            $Titulo = $data['Titulo'];
            $sql = "INSERT INTO Ubicacion (Titulo) VALUES ('$Titulo')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Ubicación creada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear ubicación"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'ubicaciones' && $id) {
            // Actualizar una ubicación
            $data = json_decode(file_get_contents("php://input"), true);
            $Titulo = $data['Titulo'];
            $sql = "UPDATE Ubicacion SET Titulo='$Titulo' WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Ubicación actualizada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar ubicación"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'ubicaciones' && $id) {
            // Eliminar una ubicación
            $sql = "DELETE FROM Ubicaciones WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Ubicación eliminada exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar ubicación"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>