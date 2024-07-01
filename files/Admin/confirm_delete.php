<?php

$admin_id = $_GET["admin_id"];

$query_name = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE admin_id='$admin_id'");

$row_ = mysqli_fetch_assoc($query_name);

$db_first_name = $row_["first_name"];
$db_middle_name = $row_["middle_name"];
$db_last_name = $row_["last_name"];
$db_gender = $row_["gender"];

$gender_preffix = "";

if ($db_gender == "Male") {

	$gender_preffix = "Mr. ";

}else {

	$gender_preffix = "Ms. ";

}

$full_name = $gender_preffix . " " . ucfirst($db_first_name) . " " . ucfirst($db_middle_name[0]) . ". " . ucfirst($db_last_name);

if (isset($_POST["btnDelete"])) {

	mysqli_query($connections, "DELETE FROM tbl_admin WHERE admin_id='$admin_id' ");

	echo "<script>window.location.href='ViewRecord?notify=$full_name has been successfully deleted!';</script>";
}

?>

<br>
<br>
<br>

<center>
	<form method="POST">
		
		<h4> You're about to delete this admin: <font color="red"><?php echo $full_name; ?></h4>

			<input type="submit" name="btnDelete" value="Confirm" class="btn-primary"> &nbsp; <a href="?" class="btn-delete" >Cancel</a>

	</form>
</center>

<hr>