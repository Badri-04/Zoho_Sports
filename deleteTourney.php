<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	redirect();

		
	require 'Access_Functions/connect.php';

	$cols = array("tournament_id"); 
	$vals = array($_GET['id']);

	if(Delete($conn,"tournaments",$cols,$vals)){
		Delete($conn,"participants",$cols,$vals);
		header("Location: admin.php",TRUE,301);
	}



?>