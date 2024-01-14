<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username');
    $password = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT); // Hash the password
    $email = filter_input(INPUT_POST, 'email');
    $confirm_password = password_hash(filter_input(INPUT_POST, 'confirm_password'), PASSWORD_DEFAULT); // Hash the confirmation password

    if (!empty($username) && !empty($password)) {
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = ""; // No password for the default MySQL user
        $dbname = "lostnfound";

        // Create a connection to the database using MySQLi
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        // Check if the connection was successful
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        } else {
            // Check if the provided email or username already exists in the database
            $checkQuery = "SELECT username, email FROM user_details WHERE username='$username' OR email='$email'";
            $result = $conn->query($checkQuery);

            if ($result->num_rows > 0) {
                // User with the same email or username is already registered
                echo "User with the same email or username is already registered.";
            } else {
                // Modify your SQL query to include email and confirm_password
                $sql = "INSERT INTO user_details (username, password, email, confirm_password) VALUES ('$username', '$password', '$email', '$confirm_password')";


                // Check if the query was executed successfully
                if ($conn->query($sql)) {
                    // Registration successful, display a styled message and then redirect to the login page
                    echo '<div style="text-align: center; background-color: #4CAF50; color: white; padding: 10px;">User registered successfully</div>';
                    header("refresh:2;url=login.html"); // Redirect after 2 seconds
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Close the database connection
            $conn->close();
        }
    } else {
        echo "Username or password cannot be empty";
    }
}
?>