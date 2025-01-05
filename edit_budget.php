<?php
session_start();
require_once('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if (isset($_GET['id'])) {
    $budgetId = $_GET['id'];
    $userId = $_SESSION['user_id']; // Logged-in user's ID

    // Fetch the budget details for the provided budget ID
    $query = "SELECT * FROM budget WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ii', $budgetId, $userId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $budget = $result->fetch_assoc();
    } else {
        echo "Error fetching budget details.";
        exit();
    }
} else {
    echo "No budget ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Budget</title>
</head>
<body>
    <h2>Edit Budget</h2>
    <form id="editBudgetForm">
        <div class="form-group">
            <label for="budget_name">Budget Name/Category</label>
            <input type="text" id="budget_name" name="budget_name" value="<?php echo $budget['budget_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="monthly_limit">Monthly Limit</label>
            <input type="number" id="monthly_limit" name="monthly_limit" value="<?php echo $budget['monthly_limit']; ?>" required>
        </div>
        <div class="form-group">
            <label for="savings">Savings</label>
            <input type="number" id="savings" name="savings" value="<?php echo $budget['savings']; ?>" required>
        </div>
        <div class="form-group">
            <label for="reminder_date">Reminder Date</label>
            <input type="date" id="reminder_date" name="reminder_date" value="<?php echo $budget['reminder_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="reminder_threshold">Reminder Threshold</label>
            <input type="number" id="reminder_threshold" name="reminder_threshold" value="<?php echo $budget['reminder_threshold']; ?>" required>
        </div>
        <button type="submit">Update Budget</button>
    </form>

    <script>
    document.getElementById('editBudgetForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append('budget_id', '<?php echo $budget['id']; ?>');
        formData.append('budget_name', document.getElementById('budget_name').value);
        formData.append('monthly_limit', document.getElementById('monthly_limit').value);
        formData.append('savings', document.getElementById('savings').value);
        formData.append('reminder_date', document.getElementById('reminder_date').value);
        formData.append('reminder_threshold', document.getElementById('reminder_threshold').value);

        fetch('update_budget.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Budget updated successfully!');
                window.location.href = 'budget.html'; // Redirect to budget page
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        });
    });
    </script>
</body>
</html>
