<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $first_name = $fetch["first_name"];
    $admin_id = $fetch["admin_id"];
    $img = $fetch["img"] ? $fetch["img"] : 'noprofile.jpg';

    if($account_type != 1 && $account_type != 2){
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

?>

<style>
.icon {
    position: relative;
    right: -2px;
    font-size: 18px;
}

.upload img {
    border-radius: 10%;
    border: 3px solid #B9BFC5;
    transition: .3s; 
}

.upload img:hover {
    border: 3px solid #BEC3C6;
    box-shadow: 1px 1px 7px #E9EEF3;
}

.upload .round {
    position: absolute;
    bottom: 3em;
    left: 4em;
    width: 27px;
    height: 27px;
    line-height: 38px;
    text-align: center;
    overflow: hidden;
}

.upload .round input[type="file"] {
    position: absolute;
    transform: scale(2);
    top: 2em;
    opacity: 0;
}

input[type="file"]::-webkit-file-upload-button {
    position: relative;
    top: -1.6em;
    cursor: pointer;
}

#x {
    font-size: 25px;
    color: white;
}

.nav-icon {
    position: relative;
    display: flex;
    text-align: center;
    font-size: 13px;
}

.user {
    position: relative;
    bottom: -1em;
}

.logo a {
    transition: .28s;
}

.logo a:active {
    display: inline-flex;
    transform: scale(.9);
}

.logo a {
    transition: .28s;
}

.logo a:active {
    display: inline-flex;
    transform: scale(.9);
}

.brgy-logo {
    transition: .3s;
}

.brgy-logo:hover {
    filter: drop-shadow(1px 1px 5px white);
}

.main-menu li a {
    transition: .3s;
}

.main-menu li a:hover {
    font-size: 14px;
    font-size: 14px;
    background: linear-gradient(to right, #233F80, #375AAD);
}

.main-menu li a:active {
    transform: scale(.9);
    background: linear-gradient(to right, #213A72, #314D90);
}

.main-menu li a:active {
    transform: scale(.9);
    background: linear-gradient(to right, #213A72, #314D90);
}

.sidebar-menu-inner.collapsed .main-menu li a {
    display: none;
}

.sidebar-menu-inner.collapsed .logo {
    display: none;
}

.sidebar-menu-inner.collapsed .sidebar-user-info {
    display: none;
}
.main-menu li a.collapsed {
    display: none;
}

.sidebar-collapse-icon {
    transition: transform 0.5s ease-in-out;
}

.sidebar-menu-inner.collapsed .sidebar-collapse-icon {
    transform: rotate(180deg); /* Rotate the icon when collapsed */
    transition: .3s;
}

.nav-icon:hover {
    font-size: 15px;
}

.nav-icon:active {
    transform: scale(.9);
}

.x {
    transition: .2s;
}

.x:hover {
    font-size: 30px;
}

.close-sui-popup:active {
    transform: scale(.9);
}

#icon {
    position: relative;
    right: -3px;
    padding-right: 7px;
}

/*Drop down */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

/* Rotate the arrow when the dropdown is shown */
.dropdown > a .arrow-down {
    transition: transform 0.3s;
}

.dropdown .show + .arrow-down {
    transform: rotate(-135deg);
}

ion-icon {
    margin-right: 8px; 
    vertical-align: -1.4px; 
    font-size: 14px;
}

</style>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<div class="sidebar-menu">

    <div class="sidebar-menu-inner">

        <header class="logo-env">

            <!-- logo -->
            <div class="logo">
                <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
                <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
                    <img src="assets/images/barangayestefanialogo.png" width="120" alt="Brgy. Estefania Logo" class="brgy-logo" title="Brgy. Estefania Logo"/>
                </a>
            </div>

            <!-- logo collapse icon -->
            <div class="sidebar-collapse">
                <a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                    <i class="entypo-menu"></i>
                </a>
            </div>

            <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
            <div class="sidebar-mobile-menu visible-xs">
                <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                    <i class="entypo-menu"></i>
                </a>
            </div>

        </header>

        <div class="sidebar-user-info">

            <div class="sui-normal">
                <form action="" class="form" id="form" enctype="multipart/form-data" method="post">
                    <div class="upload">
                        <img src="../../uploads/profile/<?php echo htmlspecialchars($img); ?>" height="75px" width="75px" class="pp" title="Default Profile" alt="Profile Image" id="profileImg">
                        <div class="round">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin_id); ?>">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($username); ?>">
                            <ion-icon name="camera-sharp" style="color: #DCE1E7; filter: drop-shadow(1px 1px 5px black)" class="icon"></ion-icon>
                            <input type="file" name="img" id="img" accept=".jpg, .jpeg, .png" title="Upload Picture">                        
                        </div>
                    </div>
                </form>
                <script type="text/javascript">
                    document.getElementById("img").onchange = function() {
                        document.getElementById("form").submit();
                    }
                </script>
                <?php
                if (isset($_FILES["img"]["name"])) {
                    $id = $_POST["id"];
                    $username = $_POST["name"];

                    $imgName = $_FILES["img"]["name"];
                    $imgSize = $_FILES["img"]["size"];
                    $tmpName = $_FILES["img"]["tmp_name"];

                    // Image Validation
                    $validImgExtension = ['jpg', 'jpeg', 'png'];
                    $imgExtension = explode('.', $imgName);
                    $imgExtension = strtolower(end($imgExtension));

                    if (!in_array($imgExtension, $validImgExtension)) {
                        echo "<script>alert('Invalid Image Extension. Please select a JPEG, JPG, or PNG image.');</script>";
                        exit;
                    } elseif ($imgSize > 5000000) {
                        echo "<script>alert('Image size exceeds 5MB. Please select an image less than 5MB.');</script>";
                        exit;
                    } else {
                        $newImgName = $username . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Generate new Img name
                        $newImgName .= "." . $imgExtension;
                        $uploadDir = '../../uploads/profile/';
                        $uploadFile = $uploadDir . $newImgName;
                        
                        // Update database and move file
                        $query = "UPDATE tbl_admin SET img = '$newImgName' WHERE admin_id = '$id'";
                        if (mysqli_query($connections, $query)) {
                            if (move_uploaded_file($tmpName, $uploadFile)) {
                                echo "<script>alert('Image uploaded successfully.');</script>";
                            } else {
                                echo "<script>alert('Failed to upload image.');</script>";
                            }
                        } else {
                            echo "<script>alert('Failed to update database.');</script>";
                        }
                    }
                }
                ?>
                <a href="#" class="user-link">
                    <span class="user" id="welcome">Welcome,</span>
                    <strong class="user" id="username" title="<?php echo htmlspecialchars($first_name) ?>"><?php echo htmlspecialchars($first_name); ?></strong>
                </a>
            </div>

            <div class="sui-hover inline-links animate-in">
                <br><br>

                <a href="admin_records.php" class="nav-icon">
                    <i class='bx bxs-user-account'></i>
                    Admins
                </a>

                <a href="admin_approval.php" class="nav-icon">
                    <i class="entypo-bell"></i>
                    Info
                </a>

                <a href="dev.php" class="nav-icon">
                    <i class='bx bx-code-alt'></i>
                    Dev
                </a>

                <span class="close-sui-popup" id="x"><ion-icon name="close-outline" class="x"></ion-icon></span><!-- this is mandatory -->
            </div>
        </div>

        <ul id="main-menu" class="main-menu">
            <li>
                <a href="index.php">
                    <i class="entypo-gauge"></i>
                    <span class="title" title="Dashboard icon">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="driver.php">
                    <i class="entypo-users"></i>
                    <span class="title" title="Drivers icon">Driver</span>
                </a>
            </li>
            <li>
                <a href="verify.php">
                    <i class="entypo-check"></i>
                    <span class="title" title="Verification icon">To Verify</span>
                </a>
            </li>
            <li>
                <a href="renewal.php">
                    <i class="entypo-arrows-ccw"></i>
                    <span class="title" title="Renewal icon">Renewal</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown('violationDropdown')">
                    <i class="entypo-flag" title="Violation icon"></i>
                    <span class="title">Violation</span>
                    <i class="arrow-down"></i> <!-- Icon for dropdown arrow -->
                </a>
                <ul id="violationDropdown" class="dropdown-content">
                    <li>
                        <a href="violation.php"><ion-icon name="person"></ion-icon>Driver's Violation</a>
                    </li>
                    <li>
                        <a href="assoc_violation.php"><ion-icon name="business"></ion-icon>Associations Violation</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="generate.php">
                    <i class="entypo-vcard"></i>
                    <span class="title" title="ID Generation icon">Generate ID</span>
                </a>
            </li>
            <li class="has-sub">
                <a href="driver_data.php">
                    <i class="entypo-doc-text"></i>
                    <span class="title">Generate Report</span>
                </a>
                <ul>
                    <li>
                        <a href="driver_data.php">
                            <i class="entypo-users"></i>
                            <span class="title">Drivers and Associations</span>
                        </a>
                    </li>
                    <li>
                        <a href="violation_data.php">
                            <i class="entypo-pencil"></i>
                            <span class="title">Violations</span>
                        </a>
                    </li>
                    <li>
                        <a href="comment_data.php">
                            <i class="entypo-attach"></i>
                            <span class="title">Concerns and Complaints</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="register.php">
                    <i class="entypo-monitor"></i>
                    <span class="title" title="Register icon">Registration</span>
                </a>
            </li>
            <li>
                <a href="control_panel.php">
                    <i class="entypo-cog"></i>
                    <span class="title" title="Control Panel icon">Control Panel</span>
                </a>
            </li>
            <li>
                <a href="only_acc.php">
                    <i class='bx bxs-edit' id="icon"></i>
                    <span class="title" title="Modify your account">Update User</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-videos' id="icon"></i>
                    <span class="title" title="Video Tutorial">Tutorial</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-joystick' id="icon"></i>
                    <span class="title" title="Games">Games</span>
                </a>
            </li>
            <li>
                <a href="instructions.php">
                    <i class='bx bx-question-mark' id="icon"></i>
                    <span class="title" title="Instructions">Instructions</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const sidebarCollapseIcon = document.querySelector('.sidebar-collapse-icon');
    const sidebarMenuItems = document.querySelectorAll('.main-menu li a');

    sidebarCollapseIcon.addEventListener('click', () => {
        document.querySelector('.sidebar-menu-inner').classList.toggle('collapsed');

        sidebarMenuItems.forEach(item => {
            item.classList.toggle('collapsed');
        });
    });
});

function toggleDropdown(id) {
    let dropdown = document.getElementById(id);
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    } else {
        dropdown.classList.add('show');
    }
}

// Optional: Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropdown > a')) {
        let dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

</script>
