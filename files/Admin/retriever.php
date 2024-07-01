<br>
<br>
<br>
<br>


<center>
<table border="1" width="80%">
	
<tr>
	<td width="16%"><b>Name</b></td>
	<td width="16%"><b>Gender</b></td>
	<td width="16%"><b>Contact</b></td>
	<td width="16%"><b>Email</b></td>
	<td width="16%"><b>Password</b></td>
	<td width="16%"><center><b>Action</b></td>
</tr>

<tr>
	<td colspan='6'> <hr> </td>
</tr>


<?php

if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../connections.php");

    if(isset($_SESSION["email"])) {
        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1){
            header("Location: ../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }

$retrieve_query = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE account_type='2' ");

while ($row_admins = mysqli_fetch_assoc( $retrieve_query)) {

	$admin_id = $row_admins["admin_id"];

	$db_first_name = $row_admins["first_name"];
	$db_middle_name = $row_admins["middle_name"];
	$db_last_name = $row_admins["last_name"];

	$db_gender = ucfirst($row_admins["gender"]);

	$db_mobile_number = $row_admins["mobile_number"];
	$db_email = $row_admins["email"];
	$db_password = $row_admins["password"];


	$full_name = ucfirst($db_first_name) . " " . ucfirst($db_middle_name[0]) . ". " . ucfirst($db_last_name);
	$contact = $db_mobile_number;

	$jScript = md5(rand(1,9));
	$newScript = md5(rand(1,9));
	$getUpdate = md5(rand(1,9));
	$getDelete = md5(rand(1,9));



	echo "
	<tr>
		<td>$full_name</td>

		<td>$db_gender</td>
		<td>$contact</td>
		<td>$db_email</td>
		<td>$db_password</td>

		<td>
			<center>
			<br>
			<br>
				<a href='?jScript=$jScript && newScript=$newScript && getUpdate=$getUpdate && admin_id=$admin_id' class='btn-update'>Update</a>

			&nbsp;

				<a href='?jScript=$jScript && newScript=$newScript && getDelete=$getDelete && admin_id=$admin_id' class='btn-delete'>Delete</a>


			<br>
			<br>

			</center>
		</td>

	</tr>";

	echo "

		<tr>
			<td colspan='6'> <hr> </td>
		</tr>

	";

}

?>

</table>
