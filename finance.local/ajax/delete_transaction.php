<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$response = ['success' => false, 'message' => ''];

try {
    $id = $_POST['id'] ?? 0;

    if (empty($id)) {
        throw new Exception('ID транзакции не указан');
    }

    $query = "DELETE FROM transactions WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Транзакция успешно удалена';
    } else {
        throw new Exception('Ошибка при удалении транзакции');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>