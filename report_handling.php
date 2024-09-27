<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Handling - My e-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
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

    <!-- Report Handling Section -->
    <section class="report-handling">
        <h2>Report Handling</h2>

        <div class="report-summary">
            <h3>Reports Overview</h3>
            <p>This section provides an overview of various reports related to books and members.</p>
        </div>

        <div class="report-buttons">
            <a href="book_report.php" class="cta-button">View Book Report</a>
            <a href="member_report.php" class="cta-button">View Member Report</a>
            
        </div>

        <h3>Statistics</h3>
        <div class="statistics">
            <div class="statistic">
                <h4>Total Books</h4>
                <p>150</p> <!-- Replace with dynamic data from database -->
            </div>
            <div class="statistic">
                <h4>Total Members</h4>
                <p>75</p> <!-- Replace with dynamic data from database -->
            </div>
            <div class="statistic">
                <h4>Books Borrowed Today</h4>
                <p>5</p> <!-- Replace with dynamic data from database -->
            </div>
        </div>
    </section>

    <!-- Footer Section -->
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
