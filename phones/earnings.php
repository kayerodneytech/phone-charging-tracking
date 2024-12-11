<?php
// Include the database connection file (Assuming you have a db connection file)
include('includes/config.php');
include 'navbar.php';

// Function to fetch the total earnings and total money due
function getEarnings() {
    global $conn;

    // Get the total amount paid (earned money)
    $sql = "SELECT SUM(amount) AS total_earned FROM payments WHERE payment_status = 'paid'";
    $result = $conn->query($sql);
    $total_earned = $result->fetch_assoc()['total_earned'];

    // Get the total amount due (unpaid money)
    $sql = "SELECT SUM(amount) AS total_due FROM payments WHERE payment_status = 'unpaid'";
    $result = $conn->query($sql);
    $total_due = $result->fetch_assoc()['total_due'];

    return [
        'total_earned' => $total_earned ?: 0,
        'total_due' => $total_due ?: 0
    ];
}

// Get charging rate from charging_rates table
function getChargingRate() {
    global $conn;

    $sql = "SELECT rate FROM charging_rates ORDER BY last_updated DESC LIMIT 1";
    $result = $conn->query($sql);
    $rate = $result->fetch_assoc()['rate'];

    return $rate ?: 0;
}

// Function to calculate total due for phones that haven't paid
function calculatePhonesDue() {
    global $conn;

    // Get the charging rate
    $charging_rate = getChargingRate();

    // Get the number of people with pending status (paid = 0)
    $sql = "SELECT COUNT(*) AS pending_count FROM phones_charged WHERE paid = 0";
    $result = $conn->query($sql);
    $pending_count = $result->fetch_assoc()['pending_count'];

    // Calculate total due for pending payments
    return $pending_count * $charging_rate;
}

// Update the payment status to 'paid' when a payment is made
if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $sql = "UPDATE payments SET payment_status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $stmt->close();
}

// Get total earnings, due payments, and total phones due
$earnings = getEarnings();
$charging_rate = getChargingRate();
$phones_due = calculatePhonesDue();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings Overview</title>
    <style>
        /* General Body Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Header Style */
        h1, h2 {
            color: #333;
            text-align: center;
            margin-top: 40px;
        }

        /* Earnings Summary Section */
        .earnings-summary {
            display: flex;
            justify-content: space-around;
            gap: 30px;
            margin-top: 40px;
        }

        .summary-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
        }

        .summary-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .summary-card p {
            font-size: 1.5rem;
            color: #007BFF;
        }

        .summary-card .total {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
        }

        /* Charging Rate Section */
        .charging-rate {
            background-color: #fff;
            padding: 30px;
            margin: 40px auto;
            width: 70%;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .charging-rate p {
            font-size: 1.5rem;
            color: #ff7711;
            font-weight: bold;
        }

        /* Payment Update Form */
        .update-payment-form {
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            width: 50%;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .update-payment-form label {
            font-size: 1rem;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        .update-payment-form input {
            font-size: 1.2rem;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
            margin-bottom: 20px;
        }

        .update-payment-form button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-payment-form button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .earnings-summary {
                flex-direction: column;
                align-items: center;
            }

            .summary-card {
                width: 80%;
                margin-bottom: 20px;
            }

            .charging-rate {
                width: 90%;
            }

            .update-payment-form {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <h1>Earnings Overview</h1>

    <!-- Earnings Summary Cards -->
    <div class="earnings-summary">
        <div class="summary-card">
            <h3>Total Earned</h3>
            <p class="total">$<?php echo number_format($earnings['total_earned'], 2); ?></p>
        </div>

        <div class="summary-card">
            <h3>Total Due</h3>
            <p class="total" style="color:#ff0000">$<?php echo number_format($phones_due, 2); ?></p>
        </div>
    </div>

   
    <!-- Charging Rate Section -->
    <div class="charging-rate">
        <h2>Current Charging Rate</h2>
        <p>$<?php echo number_format($charging_rate, 2); ?></p>
    </div>

    <!-- Payment Status Update Form -->
    <div class="update-payment-form">
        <h2>Update Payment Status</h2>
        <form action="earnings.php" method="post">
            <label for="payment_id">Payment ID:</label>
            <input type="number" id="payment_id" name="payment_id" required>
            <button type="submit" name="update_payment">Mark as Paid</button>
        </form>
    </div>
</body>
</html>
