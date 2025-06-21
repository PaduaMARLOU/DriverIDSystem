<?php
session_start(); // Ensure session is started

include("../../connections.php");

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
$message = ""; // Variable to store status message

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Fetch the account type and ID from the database
    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $user_id = $fetch["admin_id"]; // Store logged-in user ID
    $stored_password = $fetch["password"]; // Store the current password from the database
}

// Fetch the current user's data
$current_user_query = mysqli_query($connections, "SELECT username, password FROM tbl_admin WHERE admin_id='$user_id'");
$current_user = mysqli_fetch_assoc($current_user_query);

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = mysqli_real_escape_string($connections, $_POST['username']);
    $new_password = mysqli_real_escape_string($connections, $_POST['password']);
    $admin_id = mysqli_real_escape_string($connections, $_POST['admin_id']);
    $current_password = mysqli_real_escape_string($connections, $_POST['current_password']); // Get current password from form input

    // Validate current password
    if ($current_password === $stored_password) {
        // Validate password confirmation
        if (isset($_POST['confirm_password']) && $_POST['confirm_password'] === $new_password) {
            // Update the username and password in the database
            $update_query = "UPDATE tbl_admin SET username='$new_username', password='$new_password' WHERE admin_id='$admin_id'";
            if (mysqli_query($connections, $update_query)) {
                $message = "Username and password updated successfully.";
            
                // Log the Update action
                $action_details = "Admin with ID $admin_id updated their account in the Admin Panel.";
                $logout_time = date('Y-m-d H:i:s'); // Ensure the logout time is set to the current time
                if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$logout_time')")) {
                    error_log("Error logging update action: " . mysqli_error($connections));
                }
            
                // Redirect to logout.php after successful update
                header("Location: ../logout.php");
                exit(); // Ensure no further code is executed after the redirect
            } else {
                $message = "Error updating record: " . mysqli_error($connections);
                error_log("MySQL error: " . mysqli_error($connections)); // Log error for debugging
            }                        
        } else {
            $message = "Passwords do not match!";
        }
    } else {
        $message = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Neon Admin Panel">
    <meta name="author" content="">
    <link rel="icon" type="image/jpg" href="../../img/Brgy. Estefania Logo (Old).png">
    <title>Barangay Estefania Admin - Driver ID System</title>
    
    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" type="text/css" href="../adminportalcss/register.css"> <!-- Your custom CSS -->
    
    <!-- JavaScript Links -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/js/gsap/TweenMax.min.js"></script>
    <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/joinable.js"></script>
    <script src="assets/js/resizeable.js"></script>
    <script src="assets/js/neon-api.js"></script>
    <script src="assets/js/datatables/datatables.js"></script>
    <script src="assets/js/select2/select2.min.js"></script>
    <script src="assets/js/neon-chat.js"></script>
    <script src="assets/js/neon-custom.js"></script>
    <script src="assets/js/neon-demo.js"></script>
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-body" data-url="http://neon.dev">
    <style>
        /* Styles exclusive to the form-container */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 2rem;
            border: 2px solid #74777A;
            border-radius: 4px;
            padding: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 1px 1px 10px #B1B3B5;
            backdrop-filter: blur(3px);
        }

        .form-container h2 {
            font-size: 30px;
            color: #51565D;
            margin-bottom: 1rem;
        }

        .form-container label {
            font-size: 18px;
            color: #51565D;
        }

        .form-container input {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #74777A;
            border-radius: 4px;
        }

        .form-container button {
            border-radius: 4px;
            border: 1.8px solid #51565D;
            font-size: 18px;
            background-color: transparent;
            color: #51565D;
            cursor: pointer;
            padding: 0.75rem;
            width: 100%;
            transition: 0.25s;
        }

        .form-container button:hover {
            color: white;
            background-color: #2888E5;
        }
        
        .form-container ion-icon {
            font-size: 28px;
            color: #51565D;
            transition: .2s;
        }

        .form-container ion-icon:hover {
            color: #2888E5;
        }
    </style>
    
    <div class="page-container">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <!-- Header -->
            <?php include "header.php"; ?>
            
            <hr />

            <!-- Update Account Form Content -->
            <div class="form-container">
            <p style="font-style: italic; background-color: #ffeb3b; padding: 10px; border-radius: 5px; border: 1px solid #ff9800; color: #333;">
                If you successfully update your username or password, you will be logged out automatically.
            </p>

                <h2>Update Account</h2>
                
                <!-- Display status message -->
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <form action="update_acc.php" method="POST" onsubmit="return validateForm() && confirmUpdate()">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_user['username']); ?>" required>

                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required>

                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required>

                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required>

                    <input type="hidden" name="admin_id" value="<?php echo $user_id; ?>">
                
                    <button type="submit">Update</button>
                </form>
                <!-- <a href='index.php'><ion-icon name="arrow-back-outline"></ion-icon></a> -->
            </div>

            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false; // Prevent form submission
            }
            return true; // Passwords match
        }

        function confirmUpdate() {
            return confirm("Are you sure you want to update this account?");
        }
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
