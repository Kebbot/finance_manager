<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$type = $_GET['type'] ?? 'all';
$date = $_GET['date'] ?? '';

try {
    // Базовый запрос для транзакций
    $query = "SELECT * FROM transactions WHERE 1=1";
    $params = [];

    if ($type !== 'all') {
        $query .= " AND type = :type";
        $params[':type'] = $type;
    }

    if (!empty($date)) {
        $query .= " AND date = :date";
        $params[':date'] = $date;
    }

    $query .= " ORDER BY date DESC, created_at DESC";

    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Статистика
    $statsQuery = "SELECT 
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                    SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance
                   FROM transactions WHERE 1=1";

    $statsParams = [];

    if ($type !== 'all') {
        $statsQuery .= " AND type = :type";
        $statsParams[':type'] = $type;
    }

    if (!empty($date)) {
        $statsQuery .= " AND date = :date";
        $statsParams[':date'] = $date;
    }

    $statsStmt = $db->prepare($statsQuery);
    foreach ($statsParams as $key => $value) {
        $statsStmt->bindValue($key, $value);
    }
    $statsStmt->execute();
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    // Заполняем нулями если NULL
    $stats['total_income'] = $stats['total_income'] ?? 0;
    $stats['total_expense'] = $stats['total_expense'] ?? 0;
    $stats['balance'] = $stats['balance'] ?? 0;

    echo json_encode([
        'success' => true,
        'transactions' => $transactions,
        'stats' => $stats
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'transactions' => [],
        'stats' => ['total_income' => 0, 'total_expense' => 0, 'balance' => 0]
    ], JSON_UNESCAPED_UNICODE);
}
?>