<?php
session_start();

// Replace these with your database credentials
$host = "localhost";
$username = "root";
$password = "";
$dbname = "lostnfound";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $login_input = filter_input(INPUT_POST, 'username'); // Assuming you use 'username' for login
        $password = filter_input(INPUT_POST, 'password');

        // Query the database to fetch the user's data by username
        $query = "SELECT username, password FROM user_details WHERE username = :login_input";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login_input', $login_input);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Check if the submitted password matches the password in the database (no hashing)
            if ($password === $row['password']) {
                // Successful login
                $_SESSION['username'] = $row['username']; // Store the username in a session variable
                header("Location: home.html"); // Redirect to the home page upon successful login
                exit();
            } else {
                // Invalid password
                echo "Invalid password. Please try again.";
            }
        } else {
            // User not found
            echo "User not found. Please check your username.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Your existing CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            <div class="input-group">
                <input type="submit" value="Login">
            </div>
            <div class="input-group">
                <a href='sign up page.html'>Don't have an account? Sign up</a>
            </div>
            <div class="input-group">
                <a href="#">Forgot Password?</a><!-- We are yet to add the forgot password page -->
            </div>
            <?php
            // Check if the user is logged in
            if (isset($_SESSION['username'])) {
                // User is logged in, display a logout button
                echo '<input type="submit" value="Logout" form="logoutForm">';
            }
            ?>
        </form>
        <form id="logoutForm" method="post" action="logout.php"></form>
    </div>
    <div class="footer">
        <!-- Your footer content here... -->
    </div>
</body>
</html>