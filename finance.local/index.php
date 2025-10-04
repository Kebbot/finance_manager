<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учет доходов и расходов</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Система учета финансов</h1>

    <!-- Форма добавления транзакции -->
    <div class="form-section">
        <h2>Добавить транзакцию</h2>
        <form id="transactionForm">
            <div class="form-group">
                <label for="type">Тип:</label>
                <select id="type" name="type" required>
                    <option value="income">Доход</option>
                    <option value="expense">Расход</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Сумма:</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="category">Категория:</label>
                <input type="text" id="category" name="category" required>
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="form-group">
                <label for="date">Дата:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <button type="submit">Добавить</button>
        </form>
    </div>

    <!-- Статистика -->
    <div class="stats-section">
        <h2>Статистика</h2>
        <div class="stats">
            <div class="stat-item">
                <span>Общий доход:</span>
                <span id="totalIncome">0</span>
            </div>
            <div class="stat-item">
                <span>Общий расход:</span>
                <span id="totalExpense">0</span>
            </div>
            <div class="stat-item">
                <span>Баланс:</span>
                <span id="balance">0</span>
            </div>
        </div>
    </div>

    <!-- Список транзакций -->
    <div class="transactions-section">
        <h2>История транзакций</h2>
        <div class="filters">
            <select id="filterType">
                <option value="all">Все транзакции</option>
                <option value="income">Доходы</option>
                <option value="expense">Расходы</option>
            </select>
            <input type="date" id="filterDate">
            <button onclick="loadTransactions()">Фильтровать</button>
        </div>
        <div id="transactionsList"></div>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>