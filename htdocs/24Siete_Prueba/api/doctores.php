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
$resource = $requestUri[2]; // Ejemplo: "doctores"
$id = isset($requestUri[3]) ? intval($requestUri[3]) : null;

switch ($method) {
    case 'GET':
        if ($resource === 'doctores'  && $id==null) {
            // Obtener todos los doctores
            $result = $conn->query("SELECT * FROM Doctor");
            $doctores = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($doctores);
        } elseif (isset($_GET['IDEspecialidad']) && isset($_GET['IDUbicacion'])) {
            $especialidadID = intval($_GET['IDEspecialidad']);
            $ubicacionID = intval($_GET['IDUbicacion']);
            $result = $conn->query("SELECT * FROM Doctor WHERE EspecialidadID = $especialidadID AND UbicacionID = $ubicacionID");
            if ($result->num_rows > 0) {
                $doctores = [];
                while ($doctor = $result->fetch_assoc()) {
                    $doctores[] = $doctor;
                }
                echo json_encode($doctores);
            } else {
                echo json_encode([]); // Retorna un array vacío si no hay resultados
            }
        } elseif ($resource === 'doctores' && $id) {
            // Obtener un doctor específico
            $result = $conn->query("SELECT * FROM Doctor WHERE ID = $id");
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } 
        break;

    case 'POST':
        if ($resource === 'doctores') {
            // Crear un nuevo doctor
            $data = json_decode(file_get_contents("php://input"), true);
            $EspecialidadID = $data['EspecialidadID'];
            $NombreCompleto = $data['NombreCompleto'];
            $Telefono = $data['Telefono'];
            $UbicacionID = $data['UbicacionID'];
            $sql = "INSERT INTO Doctor (EspecialidadID, NombreCompleto, Telefono, UbicacionID) VALUES ($EspecialidadID, '$NombreCompleto', '$Telefono', $UbicacionID)";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Doctor creado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al crear doctor"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'doctores' && $id) {
            // Actualizar un doctor
            $data = json_decode(file_get_contents("php://input"), true);
            $EspecialidadID = $data['EspecialidadID'];
            $NombreCompleto = $data['NombreCompleto'];
            $Telefono = $data['Telefono'];
            $UbicacionID = $data['UbicacionID'];
            $sql = "UPDATE Doctor SET EspecialidadID=$EspecialidadID, NombreCompleto='$NombreCompleto', Telefono='$Telefono', UbicacionID=$UbicacionID WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Doctor actualizado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al actualizar doctor"]);
            }
        }
        break;

    case 'DELETE':
        if ($resource === 'doctores' && $id) {
            // Eliminar un doctor
            $sql = "DELETE FROM Doctor WHERE ID=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Doctor eliminado exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar doctor"]);
            }
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
