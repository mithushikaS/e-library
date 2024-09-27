<?php
session_start(); // Start the session

// Connect to the database
$conn = new mysqli("localhost", "root", "", "elibrary", 3307);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch both the member ID and password from the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($member_id, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Store both username and member_id in the session
        $_SESSION['username'] = $username;
        $_SESSION['member_id'] = $member_id;

        // Redirect to the home page
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My e-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>My e-Library</h1>
        </div>
    </header>
    <section class="login">
        <h2>Login to Your Account</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="cta-button">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </section>
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 My e-Library. All rights reserved.</p>
            <p>Follow us: 
                <a href="#">Facebook</a> | 
                <a href="#">Twitter</a> | 
                <a href="#">Instagram</a>
            </p>
        </div>
    </footer>
</body>
</html>


