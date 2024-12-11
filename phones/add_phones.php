<?php
include 'includes/config.php'; // Include database connection
include 'navbar.php';

$message = ""; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_model = $_POST['phone_model'];
    $name = $_POST['name'];
    $occupation = $_POST['occupation'];
    $paid = $_POST['payment_status'] === 'Paid' ? 1 : 0; // Match "Paid" from the form
    $time_submitted = date('Y-m-d H:i:s'); // Current timestamp

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into phones_charged table
        $query = "INSERT INTO phones_charged (phone_model, Name, occupation, time_submitted, paid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $phone_model, $name, $occupation, $time_submitted, $paid);

        if (!$stmt->execute()) {
            throw new Exception("Error inserting into phones_charged: " . $stmt->error);
        }

        // Get the last inserted customer_id
        $customer_id = $conn->insert_id;

        // Fetch the most recent rate from charging_rates
        $rateQuery = "SELECT rate FROM charging_rates ORDER BY last_updated DESC LIMIT 1";
        $rateResult = $conn->query($rateQuery);

        if ($rateResult->num_rows === 0) {
            throw new Exception("No rate found in charging_rates.");
        }

        $rateRow = $rateResult->fetch_assoc();
        $rate = $rateRow['rate'];

        // Insert into payments table
        $payment_status = $paid ? 'Paid' : 'Pending'; // Convert paid to 'Paid' or 'Pending'
        $payment_date = date('Y-m-d H:i:s');
        $paymentQuery = "INSERT INTO payments (customer_id, payment_status, amount, payment_date) VALUES (?, ?, ?, ?)";
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param('isss', $customer_id, $payment_status, $rate, $payment_date);

        if (!$paymentStmt->execute()) {
            throw new Exception("Error inserting into payments: " . $paymentStmt->error);
        }

        // Commit the transaction
        $conn->commit();
        $message = "Record added successfully, and payment details saved!";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Phone</title>
    <link rel="stylesheet" href="css/main-styles.css"> <!-- Link to CSS -->
    <style>
        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Phone Record</h1>
        <form method="POST">
            <label>Phone Model:</label>
            <input type="text" name="phone_model" required><br><br>
            
            <label>Name:</label>
            <input type="text" name="name" required><br><br>

            <label>Occupation:</label>
            <select name="occupation" required>
                <option value="Teacher">Teacher</option>
                <option value="General">General</option>
            </select><br><br>

            <p><b>Payment Status:</b></p><br><br>
            <div class="radio-group">
                <input type="radio" id="paid_yes" name="payment_status" value="Paid" required>
                <label for="paid_yes">Paid</label><br>
                <input type="radio" id="paid_pending" name="payment_status" value="Pending">
                <label for="paid_pending">Pending</label><br>
            </div>

            <button type="submit">Add Record</button>
        </form>
        
        <?php if ($message): ?>
            <p class="success-message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
