document.addEventListener('DOMContentLoaded', function() {
    // Установить сегодняшнюю дату по умолчанию
    document.getElementById('date').valueAsDate = new Date();

    // Загрузить транзакции при загрузке страницы
    loadTransactions();

    // Обработчик формы
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addTransaction();
    });
});

function addTransaction() {
    const formData = new FormData(document.getElementById('transactionForm'));

    fetch('ajax/add_transaction.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('transactionForm').reset();
                document.getElementById('date').valueAsDate = new Date();
                loadTransactions();
                alert('Транзакция успешно добавлена!');
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при добавлении транзакции');
        });
}

function loadTransactions() {
    const filterType = document.getElementById('filterType').value;
    const filterDate = document.getElementById('filterDate').value;

    fetch(`ajax/get_transactions.php?type=${filterType}&date=${filterDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTransactions(data.transactions);
                updateStats(data.stats);
            } else {
                console.error('Error loading transactions:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при загрузке транзакций');
        });
}

function displayTransactions(transactions) {
    const container = document.getElementById('transactionsList');

    if (transactions.length === 0) {
        container.innerHTML = '<p>Транзакций не найдено</p>';
        return;
    }

    container.innerHTML = transactions.map(transaction => `
        <div class="transaction-item ${transaction.type}">
            <div class="transaction-info">
                <strong>${transaction.category}</strong>
                <br>
                <small>${transaction.description || ''}</small>
                <br>
                <small>${transaction.date}</small>
            </div>
            <div class="transaction-amount">
                ${transaction.type === 'income' ? '+' : '-'}${parseFloat(transaction.amount).toFixed(2)} руб.
            </div>
            <div class="transaction-actions">
                <button class="delete-btn" onclick="deleteTransaction(${transaction.id})">Удалить</button>
            </div>
        </div>
    `).join('');
}

function updateStats(stats) {
    document.getElementById('totalIncome').textContent = parseFloat(stats.total_income).toFixed(2) + ' руб.';
    document.getElementById('totalExpense').textContent = parseFloat(stats.total_expense).toFixed(2) + ' руб.';
    document.getElementById('balance').textContent = parseFloat(stats.balance).toFixed(2) + ' руб.';
}

function deleteTransaction(id) {
    if (!confirm('Вы уверены, что хотите удалить эту транзакцию?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', id);

    fetch('ajax/delete_transaction.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTransactions();
                alert('Транзакция удалена!');
            } else {
                alert('Ошибка при удалении: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при удалении транзакции');
        });
}