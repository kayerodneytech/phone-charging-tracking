<?php
include 'includes/config.php'; // Include database connection
include 'navbar.php'; // Include the navigation bar if applicable

// Function to update payment status, time taken, and the payments table
function checkoutDevice($customerId, $paymentStatus) {
    global $conn; // Use the global connection variable

    // Get the current time for the time taken
    $timeTakenFormatted = date('Y-m-d H:i:s'); // Current time in 'Y-m-d H:i:s' format
    
    // Fetch the current rate from the charging_rates table
    $sqlRate = "SELECT rate FROM charging_rates ORDER BY last_updated DESC LIMIT 1";
    $rate = 0; // Default value in case the rate is not found
    if ($stmtRate = $conn->prepare($sqlRate)) {
        $stmtRate->execute();
        $stmtRate->bind_result($rate);
        $stmtRate->fetch();
        $stmtRate->close();
    } else {
        echo "<p class='error-message'>Error fetching charging rate.</p>";
        return; // Stop execution if fetching the rate fails
    }

    // Debugging output for rate value
    echo "<p class='debug-message'>Charging rate fetched: $rate</p>";

    if ($rate <= 0) {
        echo "<p class='error-message'>No valid rate found for charging.</p>";
        return; // Stop execution if no valid rate is found
    }

    // SQL query to update the time taken and payment status in phones_charged
    $sqlPhonesCharged = "UPDATE phones_charged SET time_taken = ?, paid = ? WHERE customer_id = ?";
    
    // SQL query to update payment status in the payments table
    $sqlPayment = "UPDATE payments SET payment_status = ?, amount = ? WHERE customer_id = ? AND payment_status = 'unpaid'";

    // Prepare the statement for phones_charged update
    if ($stmtPhonesCharged = $conn->prepare($sqlPhonesCharged)) {
        // Bind parameters for the phones_charged update statement
        $paidStatus = $paymentStatus === 'paid' ? 1 : 0;
        $stmtPhonesCharged->bind_param("sii", $timeTakenFormatted, $paidStatus, $customerId);
        
        // Execute the query to update the phones_charged table
        if ($stmtPhonesCharged->execute()) {
            echo "<p class='success-message'>Device checkout successful for customer ID: $customerId</p>";
        } else {
            echo "<p class='error-message'>Error updating device record for customer ID: $customerId</p>";
        }
        
        $stmtPhonesCharged->close();
    } else {
        echo "<p class='error-message'>Error preparing device query.</p>";
    }

    // Prepare the statement for payments update
    if ($stmtPayment = $conn->prepare($sqlPayment)) {
        // Ensure that customerId is a reference by using a variable, not directly in bind_param
        $stmtPayment->bind_param("sdi", $paymentStatus, $sqlRate, $customerId);
        
        // Debugging output to check if the prepared statement was successful
        if ($stmtPayment) {
            echo "<p class='debug-message'>Payment update prepared successfully.</p>";
        } else {
            echo "<p class='error-message'>Error preparing payment update.</p>";
        }

        // Execute the query to update the payments table
        if ($stmtPayment->execute()) {
            echo "<p class='success-message'>Payment status updated successfully for customer ID: $customerId.</p>";
        } else {
            // Output any SQL errors encountered during execution
            echo "<p class='error-message'>Error executing payment update: " . $stmtPayment->error . "</p>";
        }

        $stmtPayment->close();
    } else {
        echo "<p class='error-message'>Error preparing payment query.</p>";
    }
}

// Handle form submission for updating device checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout_device'])) {
    $customerId = $_POST['device_id'];
    $paymentStatus = $_POST['payment_status'] == 'paid' ? 'paid' : 'unpaid'; // 'paid' or 'unpaid'

    // Call the function to update the device checkout and payments status
    checkoutDevice($customerId, $paymentStatus);
}

// Fetch all customers with unpaid or not yet collected devices
$sql = "SELECT * FROM phones_charged WHERE paid = 0 OR time_taken IS NULL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Device - Phone Charging System</title>
    <link rel="stylesheet" href="css/main-styles.css">
</head>
<body>
    <div class="container">
        <h1>Checkout Device</h1>
        
        <!-- Device Checkout Form -->
        <form method="POST" action="checkout.php">
            <label for="device_id">Customer ID:</label>
            <input type="number" name="device_id" id="device_id" required>
            
            <label for="payment_status">Payment Status:</label>
            <select name="payment_status" id="payment_status" required>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>
            
            <button type="submit" name="checkout_device">Checkout Device</button>
        </form>

        <h2>Devices Pending Checkout</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Phone Model</th>
                    <th>Time Submitted</th>
                    <th>Time Taken</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['customer_id'] . "</td>";
                        echo "<td>" . $row['phone_model'] . "</td>";
                        echo "<td>" . $row['time_submitted'] . "</td>";
                        echo "<td>" . ($row['time_taken'] ? $row['time_taken'] : 'Not Collected Yet') . "</td>";
                        echo "<td>" . ($row['paid'] ? 'Paid' : 'Unpaid') . "</td>";
                        echo "<td><a href='checkout.php?device_id=" . $row['customer_id'] . "'>Checkout</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No pending devices for checkout.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
