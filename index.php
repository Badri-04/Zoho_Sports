<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";

	redirect();

	if(isset($_POST['submit'])){
		unset($_SESSION['user_id']);

		require 'Access_Functions/connect.php';

		if($_POST["email"]=="admin@gmail.com" && $_POST["password"]=="admin"){
			$_SESSION['user_id'] = "admin";
			header("Location: admin.php",TRUE,301);
			return;
		}

		$cols = array("email"); 
		$vals = array($_POST["email"]);

		$data = Fetch($conn,"users",$cols,$vals);

		if(count($data)==0){
			$_SESSION['error'] = "<span>Email not Found</span>";
			header("Location: index.php",TRUE,301);
			return;
		}
		else{
			if($data[0]["passwords"]==sha1($_POST["password"])){
				$_SESSION['user_id'] = $data[0]["user_id"];
				header("Location: register.php",TRUE,301);
				return;
			}
			else{
				$_SESSION['error'] = "<span>Incorrect Password</span>";
				header("Location: index.php",TRUE,301);
				return;
			}
		}	
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<style type="text/css">
		.divider:after,
		.divider:before {
		content: "";
		flex: 1;
		height: 1px;
		background: #eee;
		}

		span{
			font-size: 16px;
			color: #FF0000;
		}
	</style>

	<script>
		function validate(){
			document.getElementById("eid").innerHTML="";
			document.getElementById("pid").innerHTML="";
			
			var mail=document.getElementById("email").value;
			var pwd=document.getElementById("password").value;
			
			var f=mail.indexOf("@"),l=mail.lastIndexOf(".");
			var flag=1;
			
			var u1=mail.length , p1=pwd.length;
			


			if(u1==0){
				document.getElementById("eid").innerHTML="!!Email field Should Not Be Empty";
				flag=0;
			}
			else if(f==-1 || l==-1 || f>l){
				document.getElementById("eid").innerHTML="!!Standard mail pattern(name@domain.com)";
				flag=0;
			}
			if(p1==0){
				document.getElementById("pid").innerHTML="!!Password field Should Not Be Empty";
				flag=0;
			}
			if(flag==0){
				return false
			}
			if(flag==1){
				return true;
			}
		}
		</script>

</head>
<body>
<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex align-items-center justify-content-center h-100">
      <div class="col-md-8 col-lg-7 col-xl-6">
        <img src="images/zoho_logo.jpg"
          class="img-fluid" alt="Phone image">
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
        <form onsubmit="return validate()" method="POST" action="index.php">
          <!-- Email input -->
          <?php
			if(isset($_SESSION['error'])){
				echo "<br><p class='alert'>".$_SESSION['error']."</p>";
				unset($_SESSION['error']);
			}			
		  ?>
          <div class="form-outline mb-4">
          	<span id="eid" style="color:red;"></span>
            <input type="email" id="email" name="email" placeholder="Email address" class="form-control form-control-lg" />
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
          	<span id="pid" style="color:red;"></span>
            <input type="password" id="password" name="password" placeholder="Password" class="form-control form-control-lg" />
          </div>

          <!-- <div class="d-flex justify-content-around align-items-center mb-4">
            <a href="forgotPassword.php">Forgot password?</a>
          </div> -->

          <!-- Submit button -->
          <button type="submit" name="submit" value="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
          

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
          </div>

          <a class="btn btn-primary btn-lg btn-block" name="register" style="background-color: #3b5998" href="register.php"
            role="button">Register
          </a>


        </form>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>