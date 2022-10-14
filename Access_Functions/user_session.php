<?php
	function is_loggedin(){
		if(isset($_SESSION['user_id'])!=TRUE){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}

	function is_admin(){
		if($_SESSION['user_id']!="admin"){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}

	function redirect(){
		if(is_loggedin() && is_admin()){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
			return;
		}
		if(is_loggedin()){
			header("Location: http://localhost/Zoho_sports/homepage.php",TRUE,301);
			return;
		}
		return;
	}

	function redirect_from_user(){
		if(!is_loggedin()){
			header("Location: http://localhost/Zoho_sports/index.php",TRUE,301);
			return;
		}
		if(is_admin()){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
			return;
		}
		return;
	}

	function redirect_from_admin(){
		if(!is_admin()){
			if(is_loggedin()){
				header("Location: http://localhost/Zoho_sports/homepage.php",TRUE,301);
				return;
			}
			header("Location: http://localhost/Zoho_sports/index.php",TRUE,301);
			return;
		}
		return;
	}
?>