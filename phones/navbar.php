<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
    <title>Phone Charging Tracker</title>
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6; /* Light background */
        }

        /* Modern navbar styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 123, 255, 0.9); /* Transparent background with blue tint */
            padding: 15px 30px;
            position: sticky;
            top: 0;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            z-index: 100;
        }

        .navbar .brand {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase; /* More futuristic look */
        }

        .navbar .nav-links {
            display: flex;
            gap: 30px;
        }

        .navbar .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 500;
            position: relative;
            padding: 5px 10px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        /* Hover effect for links */
        .navbar .nav-links a:hover {
            color: #FFD700; /* Gold color on hover */
        }

        /* Add a futuristic underline effect */
        .navbar .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #FFD700; /* Gold underline */
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .navbar .nav-links a:hover::after {
            transform: scaleX(1);
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar .nav-links {
                width: 100%;
                justify-content: space-evenly;
                margin-top: 10px;
                padding: 10px 0;
            }

            .navbar .nav-links a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="brand">Phone Charging Tracker</div>
        <div class="nav-links">
            <a href="add_phones.php">Add Record</a>
            <a href="checkout.php">Checkout</a>
            <a href="view_history.php">View Records</a>
            <a href="earnings.php">Earnings</a>
            <a href="settings.php">Settings</a>
            <a href="about.php">About</a>
        </div>
    </div>

</body>
</html>
