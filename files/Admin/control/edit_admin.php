<?php
// Include the database connection file
include '../../../connections.php';

// Check if the admin ID is provided in the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Fetch the admin details based on the admin ID
    $query = "SELECT * FROM tbl_admin WHERE admin_id = $admin_id";
    $result = mysqli_query($connections, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
    } else {
        die("Admin not found.");
    }
} else {
    die("Invalid request.");
}

// Update admin details in the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $sex = $_POST['sex'];
    $mobile_number = $_POST['mobile_number'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $account_type = $_POST['account_type'];
    $status = $_POST['status'];

    $img = $admin['img']; // Default to the current image

    // Check if an image is uploaded
    if (!empty($_FILES['admin_image']['name'])) {
        $profileDir = '../../../uploads/profile/';
        $img_name = $_FILES['admin_image']['name'];
        $img_tmp_name = $_FILES['admin_image']['tmp_name'];
        $img_size = $_FILES['admin_image']['size'];
        $img_error = $_FILES['admin_image']['error'];

        $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ext_lc = strtolower($img_ext);
        $allowed_exts = array("jpg", "jpeg", "png");

        if (in_array($img_ext_lc, $allowed_exts)) {
            $img_new_name = "";
            if (!empty($first_name) && !empty($last_name)) {
                $full_name = $first_name . ($middle_name ? '_' . $middle_name : '') . '_' . $last_name;
                $current_date = date("Y-m-d");
                $img_new_name = "{$admin_id}_{$full_name}_{$current_date}_{$img_name}";
            }
            $img_upload_path = $profileDir . $img_new_name;
            if (move_uploaded_file($img_tmp_name, $img_upload_path)) {
                $img = $img_new_name;
            } else {
                die("Failed to upload image.");
            }
        } else {
            die("Invalid image file type.");
        }
    }

    // Update query
    $query = "UPDATE tbl_admin SET 
                first_name = '$first_name', 
                middle_name = '$middle_name', 
                last_name = '$last_name', 
                sex = '$sex', 
                mobile_number = '$mobile_number', 
                username = '$username', 
                password = '$password', 
                account_type = '$account_type', 
                img = '$img', 
                status = '$status' 
              WHERE admin_id = $admin_id";

    if (mysqli_query($connections, $query)) {
        echo "Admin details updated successfully.";
    } else {
        echo "Error updating admin details: " . mysqli_error($connections);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin_control_css/edit_admin.css">
    <title>Edit Admin</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Update the path to your Bootstrap CSS file -->
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4">Edit Admin</h1>
        <form action="edit_admin.php?id=<?php echo $admin_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($admin['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($admin['middle_name']); ?>">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($admin['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="sex">Sex</label>
                <select name="sex" class="form-control" required>
                    <option value="Male" <?php echo ($admin['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($admin['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile_number">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control" value="<?php echo htmlspecialchars($admin['mobile_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($admin['password']); ?>" required>
            </div>
            <div class="form-group">
                <label for="account_type">Account Type</label>
                <input type="text" name="account_type" class="form-control" value="<?php echo htmlspecialchars($admin['account_type']); ?>" required>
            </div>
            <div class="form-group">
                <label for="admin_image">Image</label>
                <input type="file" name="admin_image" class="form-control">
                <?php if (!empty($admin['img'])): ?>
                    <p>Current Image: <?php echo htmlspecialchars($admin['img']); ?></p>
                    <img src="../../../uploads/profile/<?php echo htmlspecialchars($admin['img']); ?>" alt="Admin Image" width="100">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="<?php echo htmlspecialchars($admin['status']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Admin</button>
        </form>
    </div>

    <script src="path/to/jquery.min.js"></script> <!-- Update the path to your jQuery file -->
    <script src="path/to/bootstrap.bundle.min.js"></script> <!-- Update the path to your Bootstrap JS file -->
</body>
</html>

<?php
// Close the database connection
mysqli_close($connections);
?>
