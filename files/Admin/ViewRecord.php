<?php

session_start();
include("../../connections.php");

if(isset($_SESSION["email"])) {

	$email = $_SESSION["email"];

	$authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
	$fetch = mysqli_fetch_assoc($authentication);
	$account_type = $fetch["account_type"];

	/*if($account_type != 1){
		echo "<script>window.location.href='../Forbidden';</script>";
	}*/
}

include("nav.php");

?>

<script type="text/javascript" src="js/jQuery.js"></script>

<script type="text/javascript">
	
setInterval(function() {

		$('#retriever').load('retriever.php');

	},1000);

</script>

<?php

if (empty($_GET["getDelete"])) {
	
}else{
	include("confirm_delete.php");
}

if(empty($_GET["getUpdate"])) {

?>

<div id="retriever">

<?php include("retriever.php"); ?>
	
</div>

<?php

}else{
	include("updating_admin.php");
}

if(empty($_GET["notify"])) {

}else {
	echo "<font color=green><h3><center>" . $_GET["notify"] . "</center></h3></font>";
}

?>


<hr>
