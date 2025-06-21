<?php
include('connections.php');

// Fetch driver details based on formatted_id passed via URL
if (isset($_GET['formatted_id'])) {
    $formatted_id = $_GET['formatted_id'];
    
    // Prepare SQL query to get driver details
    $sql = "SELECT driver_id, formatted_id, verification_stat, renew_stat FROM tbl_driver WHERE formatted_id = ?";
    $stmt = $connections->prepare($sql);
    $stmt->bind_param("s", $formatted_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $driver_found = true;
        $fk_driver_id = $row['driver_id']; // Get driver_id for storing in tbl_comment
    } else {
        $driver_found = false;
    }
    
    $stmt->close();
} else {
    // Redirect back if no formatted_id is provided
    header("Location: scan.php");
    exit();
}

// Handle form submission for comments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment_details = $_POST['comment'];
    
    // Check if the comment is not empty
    if (!empty(trim($comment_details))) {
        // Set the timezone to Asia/Manila and get the current date
        date_default_timezone_set('Asia/Manila');
        $comment_date = date('Y-m-d H:i:s');

        // Insert the comment into tbl_comment
        $sql_comment = "INSERT INTO tbl_comment (fk_driver_id, comment_details, comment_date) VALUES (?, ?, ?)";
        $stmt_comment = $connections->prepare($sql_comment);
        $stmt_comment->bind_param("iss", $fk_driver_id, $comment_details, $comment_date);

        if ($stmt_comment->execute()) {
            echo "<p>Comment submitted successfully.</p>";
        } else {
            echo "<p>Error submitting the comment.</p>";
        }

        $stmt_comment->close();
    } else {
        echo "<p>Comment cannot be empty.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="driverportalcss/scan_detected.css">
    <link rel="icon" href="img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Brgy Estefania Driver Details</title>
</head>
<body>
    <div class="container">
        <h2>Driver Details</h2>

        <?php if ($driver_found): ?>
            <p>
                <strong>Formatted ID:</strong> 
                <span style="color: 
                    <?php 
                    // Check if both statuses are active/registered
                    echo ($row['verification_stat'] === 'Registered' && $row['renew_stat'] === 'Active') ? 'green' : 'red'; 
                    ?>">
                    <?php echo htmlspecialchars($row['formatted_id']); ?>
                </span><br>
                <strong>Verification Status:</strong>
                <span style="color: <?php echo ($row['verification_stat'] === 'Registered' ? 'green' : 'red'); ?>">
                    <?php echo htmlspecialchars($row['verification_stat']); ?>
                </span><br>
                <strong>Renewal Status:</strong>
                <span style="color: <?php echo ($row['renew_stat'] === 'Active' ? 'green' : 'red'); ?>">
                    <?php echo htmlspecialchars($row['renew_stat']); ?>
                </span>
            </p>

            <!-- Comment Form -->
            <h3>Leave a Comment for This Driver</h3>
            <form method="POST" action="">
                <label for="comment">Comment:</label><br>
                <textarea id="comment" name="comment" rows="4" required></textarea><br>
                <input type="submit" value="Submit Comment">
                <a href="scan.php" class="back-btn">Back</a>
            </form>

        <?php else: ?>
            <p>No driver found with the given ID.</p>
            <a href="scan.php" class="back-btn">Back</a>
        <?php endif; ?>
    </div>
</body>
</html>
