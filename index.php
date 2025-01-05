<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

echo json_encode([
    "message" => "Welcome to the To-Do List API!",
    "endpoints" => [
        "/api/tasks" => "Manage tasks"
    ]
]);
