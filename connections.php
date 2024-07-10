 <?php

$connections = mysqli_connect("localhost", "root", "", "driver_id_system2");

if(mysqli_connect_errno()) {
	echo "Failed to connect to MySQL:" . mysqli_connect_errno();
}

?>

<!--rawr
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

.btn-primary {
    position: relative;
    text-decoration: none;
    font-size: 20px;
    top: .2em;
    padding-left: .8em;
    padding-right: .8em;
    padding-top: .1em;
    padding-bottom: .1em;
    border-radius: 3px;
    color: black;
    box-shadow: 1px 1px 6px gray;
    border: 1px solid black;
    transition: .4s ease;
}

.btn-primary:hover {
    text-shadow: 1px 1px 10px white;
    text-decoration: none;
}

.btn-update {
    font-family: Arial;
    color: #ffffff;
    font-size: 15px;
    background: #005eff;
    padding: 10px 20px 10px 20px;
    text-decoration: none;
}

.btn-update:hover {
    background: #076dad;
    text-decoration: none;
}

.btn-delete {
    font-family: Georgia;
    color: #ffffff;
    font-size: 15px;
    background: #d93434;
    padding: 10px 20px 10px 20px;
    text-decoration: none;
}

.btn-delete:hover {
    background: #fc3c3c;
    text-decoration: none;
}

</style>
-->