<?php

$admin_id = $_GET["admin_id"];

$get_record = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE admin_id='$admin_id'");

while ($get = mysqli_fetch_assoc ( $get_record)) {
	$db_first_name = $get["first_name"];
	$db_middle_name = $get["middle_name"];
	$db_last_name = $get["last_name"];
	$db_gender = $get["gender"];
	$db_mobile_number = $get["mobile_number"];
	$db_email = $get["email"];
	$db_password = $get["password"];
}

$new_first_name = $new_middle_name = $new_last_name = $new_gender = $new_mobile_number = $new_email = "";
$new_first_nameErr = $new_middle_nameErr = $new_last_nameErr = $new_genderErr = $new_mobile_numberErr = $new_emailErr = "";

if(isset($_POST["btnUpdate"])) {
	if(empty($_POST["new_first_name"])) {
		$new_first_name ="This field must not be empty.";
	}else {
		$new_first_name = $_POST["new_first_name"];
		$db_first_name = $new_first_name;
	}

	if(empty($_POST["new_middle_name"])) {
		$new_middle_name ="This field must not be empty.";
	}else {
		$new_middle_name = $_POST["new_middle_name"];
		$db_middle_name = $new_middle_name;
	}

	if(empty($_POST["new_last_name"])) {
		$new_last_name ="This field must not be empty.";
	}else {
		$new_last_name = $_POST["new_last_name"];
		$db_last_name = $new_last_name;
	}

	if(empty($_POST["new_mobile_number"])) {
		$new_mobile_number ="This field must not be empty.";
	}else {
		$new_mobile_number = $_POST["new_mobile_number"];
		$db_mobile_number = $new_mobile_number;
	}

	if(empty($_POST["new_email"])) {
		$new_email ="This field must not be empty.";
	}else {
		$new_email = $_POST["new_email"];
		$db_email = $new_email;
	}

	$db_gender = $_POST["new_gender"];


	if ($new_first_name && $new_middle_name && $new_last_name && $new_mobile_number && $new_email) {

		mysqli_query($connections, "UPDATE tbl_admin SET

		first_name = '$db_first_name',
		middle_name = '$db_middle_name',
		last_name = '$db_last_name',
		gender = '$db_gender',
		mobile_number = '$db_mobile_number',

		email = '$db_email' WHERE admin_id='$admin_id'
		");

		$encrypted = md5(rand(1,9));
		echo "<script>window.location.href='ViewRecord?$encrypted&&notify=Record has been updated!';</script>";
		echo "<script language='javascript'>alert('Record has been updated!')</script>";
		echo "<script>window.location.href='ViewRecord';</script>";
	}
}


?>

<center>
	
<br>
<br>
<br>

<form method="POST">
	<table border="0" width="50%">
		<tr>
			<td> <input type="text" name="new_first_name" value="<?php echo  $db_first_name; ?>"> <span class="error"><?php echo $new_first_nameErr; ?></span></td>
		</tr>

		<tr>
			<td> <input type="text" name="new_middle_name" value="<?php echo  $db_middle_name; ?>"> <span class="error"><?php echo $new_middle_nameErr; ?></span></td>
		</tr>

		<tr>
			<td> <input type="text" name="new_last_name" value="<?php echo  $db_last_name; ?>"> <span class="error"><?php echo $new_last_nameErr; ?></span></td>
		</tr>

		<tr>
			<td>
				<select name="new_gender">
					<option name="new_gender" value="Male" <?php if($db_gender == "Male") { echo "selected"; } ?> >Male</option>
					<option name="new_gender" value="Female" <?php if($db_gender == "Female") { echo "selected"; } ?> >Female</option>
					
				</select>
				<span class="error"><?php echo $new_genderErr; ?></span>
			</td>
		</tr>

		<tr>
			<td>
				<input type="text" name="new_mobile_number" value="<?php echo $db_mobile_number; ?>">
				<span class="error"><?php echo $new_mobile_numberErr; ?></span>

			</td>
		</tr>

		<tr>
			<td>
				<input type="text" name="new_email" value="<?php echo $db_email; ?>">
				<span class="error"><?php echo $new_emailErr; ?></span>
			</td>
		</tr>

		<tr>
			<td><input type="submit" name="btnUpdate" value="update" class="btn-primary"> </td>
		</tr>
		
	</table>
	
</form>

</center>