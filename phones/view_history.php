<?php
include 'includes/config.php'; // Include database connection
include 'navbar.php'; // Include navigation bar

// Initialize filters
$filter_query = "WHERE 1=1"; // Default: no filters

// Apply filters based on GET parameters
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['date'])) {
        $filter_query .= " AND DATE(time_submitted) = '" . $conn->real_escape_string($_GET['date']) . "'";
    }
    if (!empty($_GET['name'])) {
        $filter_query .= " AND Name LIKE '%" . $conn->real_escape_string($_GET['name']) . "%'";
    }
    if (!empty($_GET['occupation'])) {
        $filter_query .= " AND occupation LIKE '%" . $conn->real_escape_string($_GET['occupation']) . "%'";
    }
    if (isset($_GET['payment_status'])) {
        $paymentStatus = $_GET['payment_status'] === '' ? "" : " AND paid = " . (int)$conn->real_escape_string($_GET['payment_status']);
        $filter_query .= $paymentStatus;
    }
    if (!empty($_GET['phone_model'])) {
        $filter_query .= " AND phone_model LIKE '%" . $conn->real_escape_string($_GET['phone_model']) . "%'";
    }
}

// Fetch records from the database
$query = "SELECT * FROM phones_charged $filter_query";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View History</title>
    <link rel="stylesheet" href="css/main-styles.css"> <!-- Link to CSS -->
</head>
<body>
    <div class="container">
        <h1>Phone Charging History</h1>
        
        <!-- Filter Form -->
        <form method="GET">
            <div>
                <label>Filter by Date:</label>
                <input type="date" name="date">
            </div>

            <div>
                <label>Filter by Name:</label>
                <input type="text" name="name">
            </div>

            <div>
                <label>Filter by Occupation:</label>
                <input type="text" name="occupation">
            </div>

            <div>
                <label>Filter by Payment Status:</label>
                <select name="payment_status">
                    <option value="">All</option>
                    <option value="1">Paid</option>
                    <option value="0">Unpaid</option>
                </select>
            </div>

            <div>
                <label>Filter by Phone Model:</label>
                <input type="text" name="phone_model">
            </div>

            <div>
                <button type="submit">Filter</button>
            </div>
        </form>

        <!-- Records Table -->
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Teacher Name</th>
                    <th>Phone Model</th>
                    <th>Occupation</th>
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
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['phone_model'] . "</td>";
                        echo "<td>" . $row['occupation'] . "</td>";
                        echo "<td>" . $row['time_submitted'] . "</td>";
                        echo "<td>" . ($row['time_taken'] ? $row['time_taken'] : 'Not Collected Yet') . "</td>";
                        echo "<td>" . ($row['paid'] ? 'Paid' : 'Unpaid') . "</td>";
                        echo "<td><a href='checkout.php?customer_id=" . $row['customer_id'] . "'>Checkout</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found.</td></tr>";
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
