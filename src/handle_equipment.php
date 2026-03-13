<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO equipment (name, category, total_quantity, available_quantity) VALUES (:name, :category, :quantity, :quantity)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':quantity', $quantity);
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    }
}

if ($action == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE equipment SET name = :name, category = :category, total_quantity = :quantity, available_quantity = :quantity WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':id', $id);
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $query = "DELETE FROM equipment WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    }
}
?>
