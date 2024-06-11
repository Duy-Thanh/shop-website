<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['fullName']) && isset($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Read the data from the JSON file
$data = json_decode(file_get_contents('data.json'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user credentials
    foreach ($data['users'] as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullName'] = $user['fullName'];
            $_SESSION['cart'] = $user['cart'];

            // Redirect to home page or dashboard
            header('Location: index.php');
            exit();
        }
    }

    // Invalid credentials
    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
        }
        input[type="text"],
        input[type="password"] {
            width: 93%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST">
            <h2>Login</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <label>Username: <input style="margin-top: 10px;" type="text" name="username" required></label>
            <label>Password: <input style="margin-top: 10px;" type="password" name="password" required></label>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
