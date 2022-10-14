
<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	redirect();

	if(isset($_POST['submit'])){
		
		require 'Access_Functions/connect.php';

		$cols = array("email"); 
		$vals = array($_POST["email"]);

		$data = Fetch($conn,"users",$cols,$vals);
		
		if(count($data)==0){
			if($_POST['email']=="admin@gmail.com"){
				echo '<script>document.getElementById("eid").innerHTML="!!Email Already Existed";</script>';
			}

			$cols = array("name","email","age","address","passwords","blood_group"); 
			$vals = array($_POST["name"],$_POST["email"],$_POST["age"],$_POST["address"],sha1($_POST["password"]),$_POST['bloodgroup']);

			if(Insert($conn,"users",$cols,$vals)){
				header("Location: http://localhost/Zoho_sports/index.php",TRUE,301);
			}
		}
		else{
			echo '<script>document.getElementById("eid").innerHTML="!!Email Already Existed";</script>';
		}	
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<style type="text/css">
		.gradient-custom-3 {
			background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
		}
		.gradient-custom-4 {
			background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
		}
		span{
			font-size: 13px;
			color: #FF0000;
			padding-top: 0px;
			margin-top: -5px;
		}
	</style>


	<script>
		function validate(){
			document.getElementById("nid").innerHTML="&nbsp";
			document.getElementById("eid").innerHTML="&nbsp";
			document.getElementById("aid").innerHTML="&nbsp";
			document.getElementById("adid").innerHTML="&nbsp";
			document.getElementById("pid").innerHTML="&nbsp";
			document.getElementById("bid").innerHTML="&nbsp";
			
			var user=document.getElementById("name").value;
			var mail=document.getElementById("email").value;
			var age=document.getElementById("age").value;
			var add=document.getElementById("address").value;
			var pwd=document.getElementById("password").value;
			var bg=document.getElementById("blood").value;
			
			var f=mail.indexOf("@"),l=mail.lastIndexOf(".");
			var flag=1;
			
			var u1=user.length , p1=pwd.length, bl=bg.length;
			
			if(u1<6){
				document.getElementById("nid").innerHTML="!!name should contain atleast 6 letters";
				flag=0;
			}
			if(bl<1){
				document.getElementById("bid").innerHTML="!!Enter your blood group";
				flag=0;
			}
			if(p1<8){
				document.getElementById("pid").innerHTML="!!password should contain atleast 8 letters";
				flag=0;
			}
			if(add.length<1){
				document.getElementById("adid").innerHTML="!!Address is mandatory";
			}
			if(age<12){
				document.getElementById("aid").innerHTML="!!You should be atleast 12 Years old";
				flag=0;
			}
			if(f==-1 || l==-1 || f>l){
				document.getElementById("eid").innerHTML="!!Standard mail pattern(name@domain.com)";
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
<body style="background-image: url('images/regbg.jpg'); 
  background-repeat: no-repeat;
  background-size: 1550px 800px;">

<section class="vh-100 bg-image">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h4 class="text-uppercase text-center mb-2">Register for ZOHO Sports</h4>

              <form  onsubmit="return validate()" method="POST" action="register.php">
              		
                <div class="form-outline mb-2">
                	
                  <input type="text" id="name" name="name" placeholder="Name" class="form-control form-control-lg" />
                	<span id="nid">&nbsp</span>
                </div>

                <div class="form-outline mb-2">
                  <input type="email" id="email" name="email" placeholder="Email" class="form-control form-control-lg" />
                	<span id="eid">&nbsp</span>
                </div>

                <div class="form-outline mb-2">
                  <input type="number" id="age" name="age" placeholder="Age" class="form-control form-control-lg" />
                	<span id="aid">&nbsp</span>
                </div>

                <div class="form-outline mb-2">
                  <input type="text" id="address" name="address" placeholder="Address" class="form-control form-control-lg" />
                    <span id="adid">&nbsp</span>
                </div>

                <!-- <div class="form-outline mb-2">
                  <input type="text" id="blood" name="bloodgroup" placeholder="Blood Group" class="form-control form-control-lg" />
                <span id="bid">*</span>
                </div> -->
                <div class="form-outline mb-2">
	                <select name="bloodgroup" id="blood" class="form-control form-control-lg">
	                	<option value="" disabled selected>Blood Group</option>
	                  <option value="A+">A+</option>
	                  <option value="A-">A-</option>
	                  <option value="B+">B+</option>
	                  <option value="B-">B-</option>
	                  <option value="AB+">AB+</option>
	                  <option value="AB-">AB-</option>
	                  <option value="O+">O+</option>
	                  <option value="O-">O-</option>
	                </select>
	                <span id="bid">&nbsp</span>
              	</div>

                <div class="form-outline mb-3">
                  <input type="password" id="password" name="password" placeholder="Password" class="form-control form-control-lg" />
	            	<span id="pid">&nbsp</span>
                </div>

                <div class="d-flex justify-content-center">
                  <button type="submit" name="submit"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                </div>

                <p class="text-center text-muted mt-2 mb-0">Have already an account? <a href="index.php"
                    class="fw-bold text-body"><u>Login here</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>