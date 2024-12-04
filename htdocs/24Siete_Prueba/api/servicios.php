<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: DELETE, GET ,PUT ,POST, OPTIONS");
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
$resource = $requestUri[2]; // Ejemplo: "servicios"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;

switch ($method) {
    case 'GET':
        if ($resource === 'servicios' && $id==null) {
            // Obtener todos los servicios
            $result = $conn->query("SELECT * FROM Servicios");
            $servicios = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($servicios);
        } elseif ($_GET['IDDoctor']) {
            // Obtener servicios según IDDoctor
            $IDDoctor = intval($_GET['IDDoctor']);
            $result = $conn->query("SELECT * FROM Servicios WHERE IDDoctor = $IDDoctor");
            $servicios = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($servicios);
        } elseif ($resource === 'servicios' && $id) {
            // Obtener un servicio específico
            $result = $conn->query("SELECT * FROM Servicios WHERE ID = $id");
            $servicio = $result->fetch_assoc();
            echo json_encode($servicio);
        }
        break;

    case 'POST':
        if ($resource === 'servicios') {
            // Crear un nuevo servicio
            $data = json_decode(file_get_contents("php://input"), true);
            $IDDoctor = $data['IDDoctor'];
            $IDEspecialidad = $data['IDEspecialidad'];
            $Precio = $data['Precio'];
            $Titulo = $data['Titulo'];
            $sql = "INSERT INTO Servicios (IDDoctor, IDEspecialidad, Precio, Titulo) VALUES ($IDDoctor, $IDEspecialidad, $Precio, '$Titulo')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Servicio creado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear servicio"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'servicios' && $id) {
            // Actualizar un servicio
            $data = json_decode(file_get_contents("php://input"), true);
            $IDDoctor = $data['IDDoctor'];
            $IDEspecialidad = $data['IDEspecialidad'];
            $Precio = $data['Precio'];
            $Titulo = $data['Titulo'];
            $sql = "UPDATE Servicios SET IDDoctor=$IDDoctor, IDEspecialidad=$IDEspecialidad, Precio=$Precio, Titulo='$Titulo' WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Servicio actualizado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar servicio"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'servicios' && $id) {
            // Eliminar un servicio
            $sql = "DELETE FROM Servicios WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Servicio eliminado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar servicio"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
