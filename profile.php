<?php
session_start(); // Start session

// Connect to the database
$conn = new mysqli("localhost", "root", "", "elibrary", 3307);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if member ID is set in session
if (!isset($_SESSION['member_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id']; // Get member ID from session

// Initialize memberData array
$memberData = [];

// Fetch member data
$stmt = $conn->prepare("SELECT fullname, email, phone, address, dob FROM users WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $memberData = $result->fetch_assoc();
} else {
    echo "<p>No member data found.</p>";
    exit();
}

// Update profile information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $dob = htmlspecialchars($_POST['dob']);

    // Update the database
    $updateStmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, address = ?, dob = ? WHERE id = ?");
    $updateStmt->bind_param("sssssi", $fullname, $email, $phone, $address, $dob, $member_id);

    if ($updateStmt->execute()) {
        $success_message = "Profile updated successfully!";
        // Refresh member data
        $stmt = $conn->prepare("SELECT fullname, email, phone, address, dob FROM users WHERE id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $memberData = $result->fetch_assoc();
    } else {
        $error_message = "Error updating profile: " . htmlspecialchars($updateStmt->error);
    }

    $updateStmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile - My e-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>My e-Library</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="book_managment.php">Books</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="report_handling.php">Reports</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="profile-management">
        <h2>Member Profile</h2>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <div class="profile-info">
            <h3>Your Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($memberData['fullname'] ?? 'N/A'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($memberData['email'] ?? 'N/A'); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($memberData['phone'] ?? 'N/A'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($memberData['address'] ?? 'N/A'); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($memberData['dob'] ?? 'N/A'); ?></p>
        </div>

        <h3>Edit Information</h3>
        <form action="profile.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($memberData['fullname'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($memberData['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($memberData['phone'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($memberData['address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($memberData['dob'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="cta-button">Update Profile</button>
        </form>
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




