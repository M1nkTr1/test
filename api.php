<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
$input = json_decode(file_get_contents('php://input'), true);

$mysqli = new mysqli("localhost", "root", "", "testdb");
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $mysqli->connect_error]);
    exit();
}

switch ($method) {
    case 'GET':
        if (isset($request[0]) && is_numeric($request[0])) {
            $id = intval($request[0]);
            $stmt = $mysqli->prepare("SELECT id, name, email FROM users1 WHERE id=?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $mysqli->prepare("SELECT id, name, email FROM users1");
        }
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);

            http_response_code(200);  // OK
            echo json_encode(["action" => "retrieve", "data" => $users]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare statement: " . $mysqli->error]);
        }
        break;
    
    case 'POST':
        if (isset($input['name']) && isset($input['email'])) {
            $name = $input['name'];
            $email = $input['email'];
            $stmt = $mysqli->prepare("INSERT INTO users1 (name, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $email);
            if ($stmt) {
                $stmt->execute();
                
                http_response_code(201);  // Created
                echo json_encode(["action" => "create", "id" => $stmt->insert_id, "new_data" => ["name" => $name, "email" => $email]]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to prepare statement: " . $mysqli->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'PUT':
        if (isset($request[0]) && is_numeric($request[0]) && isset($input['name']) && isset($input['email'])) {
            $id = intval($request[0]);
            $name = $input['name'];
            $email = $input['email'];
            $stmt = $mysqli->prepare("UPDATE users1 SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $email, $id);
            if ($stmt) {
                $stmt->execute();
                
                http_response_code(200);  // OK
                echo json_encode(["action" => "update", "id" => $id, "modified_data" => ["name" => $name, "email" => $email]]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to prepare statement: " . $mysqli->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid ID or input"]);
        }
        break;

    case 'DELETE':
        if (isset($request[0]) && is_numeric($request[0])) {
            $id = intval($request[0]);
            $stmt = $mysqli->prepare("DELETE FROM users1 WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt) {
                $stmt->execute();
                
                http_response_code(204);  // No Content
                echo json_encode(["action" => "delete", "id" => $id]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to prepare statement: " . $mysqli->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid ID"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

$mysqli->close();
?>
