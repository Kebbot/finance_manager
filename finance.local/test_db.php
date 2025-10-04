<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "✅ Подключение к базе данных успешно!";

    // Проверим существование таблицы
    $stmt = $db->query("SHOW TABLES LIKE 'transactions'");
    if ($stmt->rowCount() > 0) {
        echo "<br>✅ Таблица 'transactions' существует";
    } else {
        echo "<br>❌ Таблица 'transactions' не найдена";
    }
} else {
    echo "❌ Ошибка подключения к базе данных";

    // Проверим настройки
    echo "<br>Проверь следующие настройки:";
    echo "<br>- Хост: localhost";
    echo "<br>- Имя базы: finance_manager";
    echo "<br>- Пользователь: root";
    echo "<br>- Пароль: (пустой)";
}
?>