<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ambil semua tugas
        $stmt = $db->prepare("SELECT * FROM tasks");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        // Tambahkan tugas baru
        $data = json_decode(file_get_contents("php://input"));
        $query = "INSERT INTO tasks (title, description, status, due_date, priority, created_at) 
                  VALUES (:title, :description, :status, :due_date, :priority, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":status", $data->status);
        $stmt->bindParam(":due_date", $data->due_date);
        $stmt->bindParam(":priority", $data->priority);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task created successfully."]);
        } else {
            echo json_encode(["message" => "Failed to create task."]);
        }
        break;

    case 'PUT':
        // Perbarui tugas
        $data = json_decode(file_get_contents("php://input"));
        $query = "UPDATE tasks SET title = :title, description = :description, status = :status, 
                  due_date = :due_date, priority = :priority, updated_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $data->id);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":status", $data->status);
        $stmt->bindParam(":due_date", $data->due_date);
        $stmt->bindParam(":priority", $data->priority);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task updated successfully."]);
        } else {
            echo json_encode(["message" => "Failed to update task."]);
        }
        break;

    case 'DELETE':
        // Hapus tugas
        $data = json_decode(file_get_contents("php://input"));
        $query = "DELETE FROM tasks WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $data->id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Task deleted successfully."]);
        } else {
            echo json_encode(["message" => "Failed to delete task."]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method."]);
        break;
}
