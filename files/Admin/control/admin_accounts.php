<?php
// Include the database connection file
include '../../../connections.php';

// Fetch admin details from the database
$query = "SELECT admin_id, first_name, middle_name, last_name, sex, mobile_number, username, password, attempt, relog_time, login_time, logout_time, account_type, date_registered, img, status FROM tbl_admin";
$result = mysqli_query($connections, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connections));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Update the path to your Bootstrap CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            text-align: center;
            color: #343a40;
        }
        .table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        .table img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
        .table .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .table .action-buttons button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4">Admin Details</h1>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Admin ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                    <th>Mobile Number</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Attempt</th>
                    <th>Relog Time</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Account Type</th>
                    <th>Date Registered</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['admin_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['sex']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['password']); ?></td>
                        <td><?php echo htmlspecialchars($row['attempt']); ?></td>
                        <td><?php echo htmlspecialchars($row['relog_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['login_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['logout_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_registered']); ?></td>
                        <td>
                            <?php if (!empty($row['img'])): ?>
                                <img src="../../../uploads/profile/<?php echo htmlspecialchars($row['img']); ?>" alt="Admin Image">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td class="action-buttons">
                            <button class="btn btn-primary" onclick="window.location.href='edit_admin.php?id=<?php echo $row['admin_id']; ?>'">Edit</button>
                            <button class="btn btn-danger" onclick="deleteAdmin(<?php echo $row['admin_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="path/to/jquery.min.js"></script> <!-- Update the path to your jQuery file -->
    <script src="path/to/bootstrap.bundle.min.js"></script> <!-- Update the path to your Bootstrap JS file -->
    <script>
        function deleteAdmin(adminId) {
            if (confirm("Are you sure you want to delete this admin?")) {
                window.location.href = 'delete_admin.php?id=' + adminId;
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connections);
?>
