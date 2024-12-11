<?php
// Include database connection
include('includes/config.php');
include 'navbar.php';

// Update charging rate
if (isset($_POST['update_rate'])) {
    $new_rate = $_POST['rate'];

    // Update the charging rate in the database
    $sql = "INSERT INTO charging_rates (rate) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $new_rate);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color: green; text-align: center; font-size: 1.2rem;'>Charging rate updated successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Charging Rate</title>
    <style>
        /* General Body Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-align: center;
        }

        /* Header Style */
        h1 {
            color: #333;
            margin-top: 40px;
        }

        /* Form Section */
        form {
            margin-top: 30px;
            background-color: #fff;
            padding: 30px;
            width: 60%;
            max-width: 500px;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 1.2rem;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        input[type="number"] {
            font-size: 1.2rem;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 20px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <h1>Update Charging Rate</h1>
    
    <form action="settings.php" method="post">
        <label for="rate">New Charging Rate ($):</label>
        <input type="number" id="rate" name="rate" step="0.25" required>
        <button type="submit" name="update_rate">Update Rate</button>
    </form>
</body>
</html>
