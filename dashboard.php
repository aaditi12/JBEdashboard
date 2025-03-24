<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Database connection
$host = 'localhost';
$db = 'etms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch additional user details if needed
$sql = "SELECT * FROM tbl_admin WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle case where user is not found
    $error = "User not found.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
       welcome-message {
            margin-bottom: 20px;
        }

        .logout-button {
            padding: 10px 20px;
            background-color: #ff7e5f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #feb47b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="welcome-message">This is your dashboard where you can manage your account and view your details.</p>

        <!-- Display additional user details if needed -->
        <?php if (isset($user)) { ?>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <?php } else { ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>