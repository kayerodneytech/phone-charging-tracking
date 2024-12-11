<?php
include 'includes/config.php'; // Include your database connection or configuration if needed
include 'navbar.php'; // Include the navigation bar if applicable
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Phone Charging System</title>
    <link rel="stylesheet" href="css/main-styles.css"> <!-- Link to your CSS file -->

    <style type="text/css">
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #444;
}

/* Section Styles */
section {
    margin-bottom: 30px;
}

section h2 {
    color: #007bff;
}

section p {
    font-size: 16px;
    line-height: 1.5;
}

section ul {
    font-size: 16px;
    list-style-type: disc;
    margin-left: 20px;
}

.contact a {
    color: #007bff;
    text-decoration: none;
}

.contact a:hover {
    text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    section {
        padding: 10px;
    }

    .container {
        padding: 10px;
    }
}

        </style>
</head>
<body>
    <div class="container">
        <h1>About Our Phone Charging System</h1>
        
        <!-- Section: Introduction -->
        <section class="intro">
            <h2>Welcome to Our Phone Charging Service</h2>
            <p>We provide a convenient and affordable phone charging service for all kinds of mobile devices. Whether you're in need of a quick charge or looking to leave your device to be charged while you go about your day, we've got you covered.</p>
        </section>

        <!-- Section: How Our System Works -->
        <section class="how-it-works">
            <h2>How the System Works</h2>
            <p>Our web-based system is designed to efficiently keep track of all devices charged throughout the day. The system logs:</p>
            <ul>
                <li>The name of the teacher who submitted the device</li>
                <li>The phone model</li>
                <li>The time the phone was submitted</li>
                <li>The time the phone was collected</li>
                <li>The charging status (whether the customer has paid or not)</li>
            </ul>
            <p>With this system, we can manage charging requests effectively, ensure accurate billing, and keep everything organized.</p>
        </section>

        <!-- Section: Cost of Service -->
        <section class="cost">
            <h2>Cost of Charging</h2>
            <p>The cost of charging a device is <strong>R5 per device</strong>. Payment is required upon pickup of the charged device. Our affordable pricing ensures that you can keep your phone powered up without breaking the bank!</p>
        </section>

        <!-- Section: Contact Us -->
        <section class="contact">
            <h2>Contact Us</h2>
            <p>If you have any questions or need more information about our service, feel free to contact us!</p>
            <p>Email: <a href="mailto:keithrodney@rodneytechinc.co.zw">info@rodneytechinc.co.zw</a></p>
        </section>
    </div>
<br><br><br><br>
<?php
include 'footer.php';
?>
</body>
</html>
