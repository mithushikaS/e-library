<?php
session_start();
$conn = new mysqli("localhost", "root", "", "elibrary", 3307);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch book data from the database without publication_year
$sql = "SELECT id, title, author FROM books"; // Removed publication_year
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Report - My e-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Book Report</h1>
    </header>
    <section>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['author']}</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

