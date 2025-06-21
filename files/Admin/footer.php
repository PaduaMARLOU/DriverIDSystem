<style>
    footer {
        color: #48484C;
    }
</style>

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

        if($account_type != 1 && $account_type != 2){
            header("Location: ../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
?>
		<!-- Footer -->
		<footer class="main">
			
			<span title="copyright">&copy;</span> <span title="Year Accomplished">2024</span> <strong title="Brgy. Estefania Driver's ID System">Barangay Estefania Driver ID's System</strong> || <span title="Capstone Project">Capstone Project by</span> <a href="../../img/Capstone Proposal.jpg" target="_blank" title="Members">BSIS-4A</a><link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
		
		</footer>