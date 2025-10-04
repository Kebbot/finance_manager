<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$response = ['success' => false, 'message' => ''];

try {
    $type = $_POST['type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';

    // Валидация
    if (empty($type) || empty($amount) || empty($category) || empty($date)) {
        throw new Exception('Все обязательные поля должны быть заполнены');
    }

    if (!is_numeric($amount) || $amount <= 0) {
        throw new Exception('Сумма должна быть положительным числом');
    }

    $query = "INSERT INTO transactions (type, amount, category, description, date) 
              VALUES (:type, :amount, :category, :description, :date)";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Транзакция успешно добавлена';
    } else {
        throw new Exception('Ошибка при добавлении транзакции');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>