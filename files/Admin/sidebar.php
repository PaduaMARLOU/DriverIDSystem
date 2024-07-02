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

	    if($account_type != 1 && $account_type != 2){
	        header("Location: ../../Forbidden.php");
	        exit; // Ensure script stops executing after redirection
	    }
	} else {
	    header("Location: ../../Forbidden.php");
	    exit; // Ensure script stops executing after redirection
	}

?>



<div class="sidebar-menu">

		<div class="sidebar-menu-inner">
			
			<header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="index.html">
						<img src="assets/images/barangayestefanialogo.png" width="120" alt="" />
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
					<a href="#" class="user-link">

						<span>Welcome,</span>
						<strong><?php echo $first_name; ?></strong>
					</a>
				</div>

				<div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
					<a href="#">
						<i class="entypo-pencil"></i>
						New Page
					</a>

					<a href="mailbox.html">
						<i class="entypo-mail"></i>
						Inbox
					</a>

					<a href="extra-lockscreen.html">
						<i class="entypo-lock"></i>
						Log Off
					</a>

					<span class="close-sui-popup">&times;</span><!-- this is mandatory -->				</div>
			</div>

<ul id="main-menu" class="main-menu">
				<!-- add class "multiple-expanded" to allow multiple submenus to open -->
				<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
				<li>
					<a href="index.php">
						<i class="entypo-gauge"></i>
						<span class="title">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="driver.php">
						<i class="entypo-users"></i>
						<span class="title">Driver</span>
					</a>
				</li>
				<li>
					<a href="verify.php">
						<i class="entypo-check"></i>
						<span class="title">To Verify</span>
					</a>
				</li>
				<li>
					<a href="renewal.php">
						<i class="entypo-arrows-ccw"></i>
						<span class="title">Renewal</span>
					</a>
				</li>
				<li>
					<a href="violation.php">
						<i class="entypo-flag"></i>
						<span class="title">Violation</span>
					</a>
				</li>
				<li>
					<a href="generate.php">
						<i class="entypo-vcard"></i>
						<span class="title">Generate ID</span>
					</a>
				</li>
				<li>
					<a href="register.php">
						<i class="entypo-monitor"></i>
						<span class="title">Registration</span>
					</a>
				</li>
				
			</ul>
			
		</div>

	</div>