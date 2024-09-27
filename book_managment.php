<?php
session_start(); // Start session

// Connect to the database
$conn = new mysqli("localhost", "root", "", "elibrary", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $description = htmlspecialchars($_POST['description']);

    // Prepare statement for inserting book
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, description) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ssss", $title, $author, $isbn, $description);

    if ($stmt->execute()) {
        $success_message = "Book added successfully!";
        $book_id = $stmt->insert_id; // Get the ID of the newly added book
        header("Location: book_managment.php?edit=$book_id"); // Redirect to edit the new book
        exit(); // Prevent further script execution
    } else {
        $error_message = "Error adding book: " . htmlspecialchars($stmt->error);
    }
    
    $stmt->close();
}

// Handle editing a book
if (isset($_GET['edit'])) {
    $book_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        $error_message = "Book not found.";
    }
    $stmt->close();
}

// Update book details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_book'])) {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $description = htmlspecialchars($_POST['description']);
    $book_id = intval($_POST['book_id']);

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $author, $isbn, $description, $book_id);

    if ($stmt->execute()) {
        $success_message = "Book updated successfully!";
        // Optionally redirect or refresh the book list
    } else {
        $error_message = "Error updating book: " . $stmt->error;
    }
    $stmt->close();
}

// Handle deleting a book
if (isset($_GET['delete'])) {
    $book_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        $success_message = "Book deleted successfully!";
    } else {
        $error_message = "Error deleting book: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch book list for display
$query = "SELECT * FROM books";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management - My e-Library</title>
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

    <section class="book-management">
        <h2>Manage Books</h2>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <div class="book-form">
            <h3><?php echo isset($book) ? "Edit Book" : "Add New Book"; ?></h3>
            <form action="book_managment.php" method="POST">
                <?php if (isset($book)): ?>
                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
                    <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>"> <!-- Keep the ISBN hidden -->
                <?php else: ?>
                    <input type="hidden" name="book_id" value="">
                <?php endif; ?>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo isset($book) ? htmlspecialchars($book['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" value="<?php echo isset($book) ? htmlspecialchars($book['author']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="<?php echo isset($book) ? htmlspecialchars($book['isbn']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo isset($book) ? htmlspecialchars($book['description']) : ''; ?></textarea>
                </div>
                <?php if (isset($book)): ?>
                    <button type="submit" name="update_book" class="cta-button">Update Book</button>
                <?php else: ?>
                    <button type="submit" name="add_book" class="cta-button">Add Book</button>
                <?php endif; ?>
            </form>
        </div>

        <h3>Book List</h3>
        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($book['description']); ?></td>
                        <td>
                            <a href="book_managment.php?edit=<?php echo $book['id']; ?>" class="cta-button">Edit</a>
                            <a href="book_managment.php?delete=<?php echo $book['id']; ?>" class="cta-button delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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

<?php
$conn->close(); // Close the database connection
?>


