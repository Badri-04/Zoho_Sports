<?php
	$conn = mysqli_connect("localhost","root","","zoho_sports");
	if(mysqli_connect_errno()){
		echo "Error : ".mysqli_connect_error();
		exit();
	}
?>