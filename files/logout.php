<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="adminportalcss/adminlogout.css">
  <title>Logging Out</title>
  <link rel="icon" type="image/jpg" href="../img/Brgy Estefania Logo.png">
</head>
<body>

  <div class="main">
    <div class="leave">
      <img src="../img/loadleave-leave.gif" alt="leaving...">
    </div>

    <div class="main-log">
      <center>
        <div class="loader"></div>
      </center>

      <?php
      session_start();
      include '../connections.php';

      if (isset($_SESSION['username'])) {
          $username = $_SESSION['username'];
          $logout = md5($username);
          $username_md5 = md5($logout);

          date_default_timezone_set("Asia/Manila");
          $logout_time = date('Y-m-d H:i:s');

          // Fetch admin ID for logging
          $query = mysqli_query($connections, "SELECT admin_id FROM tbl_admin WHERE username='$username'");
          $admin = mysqli_fetch_assoc($query);
          $admin_id = $admin['admin_id'];

          // Update logout time
          $sql = "UPDATE tbl_admin SET logout_time = '$logout_time' WHERE username = '$username'";

          if ($connections->query($sql) === TRUE) {
              // Successfully updated logout_time
              
              // Log the logout action
              $action_details = "Admin with ID $admin_id logged out.";
              if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$logout_time')")) {
                  error_log("Error logging logout action: " . mysqli_error($connections));
              }
          } else {
              echo "Error updating record: " . $connections->error;
          }

          unset($_SESSION['username']);
          session_unset();
          session_destroy();

          echo "<center class='log-out'><h2>Logging out...</h2></center>";
      } else {
          echo "<center class='log-out'><h2>No user is logged in.</h2></center>";
      }

      // Redirect after 3 seconds
      echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php?logout=$logout&v_1=$username_md5';
            }, 3000); 
          </script>";
      exit();
      ?>
    </div>
  </div>
</body>
</html>
