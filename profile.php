<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/menu.php";
	
	redirect_from_user();

		
	require 'Access_Functions/connect.php';
	

		if(isset($_POST['submit'])){

			$cols = array("name","email","age","address","blood_group"); 
			$vals = array($_POST["name"],$_POST["email"],$_POST["age"],$_POST["address"],$_POST["bloodgroup"]);

			if(Update($conn,"users",$cols,$vals,array('user_id'),array($_SESSION['user_id']))){
				//header("Location: http://localhost/Zoho_sports/homepage.php",TRUE,301);
			}
		}

		$udata = Fetch($conn,"users",array("user_id"),array($_SESSION['user_id']))[0];

	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Zoho Sports-Home</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/cards.css">

	<link rel="stylesheet" type="text/css" href="css/menu.css">
	


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	
		<link rel="stylesheet" href="popup/css/ionicons.min.css">
		<link rel="stylesheet" href="popup/css/style.css">

	<style type="text/css">
		.sticky{
			margin-bottom: 30px; 
			position: fixed; 
			top: 0; 
			left: 0; 
			width: 100%;z-index: 1;
		}
		.options{
			color: #4444ff;
			background-color: #EEEEFF;
			border-radius: 8px;
			margin: 3px;
			border: 1px solid #8888FF;
		}
		.options:hover{
			color: #60a;
			border: 2px solid #60a;
		}
		span{
			font-size: 13px;
			color: #FF0000;
			padding-top: 0px;
			margin-top: 0px;
		}
		.nav-link:hover{
		 		background-color: #0b0;
		 }
		 body{
		    background-image:
		    radial-gradient(circle, rgba(228,232,253,0.95) 0%, rgba(194,255,230,0.95) 100%),
		    url('images/bg.jpg');
		    width: 100%;
		    height: 400px;
		    background-size: cover;
			}
			.modal-content{
				background: radial-gradient(circle, rgba(228,232,253,0.95) 0%, rgba(194,255,230,0.95) 100%);
				color: black;
			}
			.form-group{
				color: black;
			}
	</style>

	<script>
		function validate(){
			document.getElementById("nid").innerHTML="*";
			document.getElementById("eid").innerHTML="*";
			document.getElementById("aid").innerHTML="*";
			document.getElementById("adid").innerHTML="*";
			document.getElementById("bid").innerHTML="*";
			
			var user=document.getElementById("name").value;
			var mail=document.getElementById("email").value;
			var age=document.getElementById("age").value;
			var add=document.getElementById("address").value;
			
			var f=mail.indexOf("@"),l=mail.lastIndexOf(".");
			var flag=1;
			
			var u1=user.length;
			
			if(u1<6){
				document.getElementById("nid").innerHTML="!!name should contain atleast 6 letters";
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
<body style="background-color: #EEFFEE;">
	

	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px;">
    <div style="padding:0px 10px 0px 60px; width: 100%;">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports</span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

          <li class="nav-item active">
            <a class="nav-link" href="homepage.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>	

          

          <li class="nav-item active">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>

        </ul>
      </div> -->
		<?php 
      	$list = array("Home"=>"homepage.php" , "Logout"=>"logout.php");
      	menu($list);

    ?>
    </div>
  	</nav>

<!-------------------------------------------------------------------------------------------->


<!-------------------------------------------------------------------------------------------->

<section class="vh-100 bg-image" style="margin-top:100px;">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-2">Update Profile</h2>

              <form  onsubmit="return validate()" method="POST" action="register.php">
              		<span id="nid">*</span>
                <div class="form-outline mb-2">
                	
                  <input type="text" id="name" name="name" placeholder="Name" value='<?php echo $udata['name']; ?>' class="form-control form-control-lg" />
                </div>

                	<span id="eid">*</span>
                <div class="form-outline mb-2">
                  <input type="email" id="email" name="email" placeholder="Email" value='<?php echo $udata['email']; ?>' class="form-control form-control-lg" />
                </div>

                	<span id="aid">*</span>
                <div class="form-outline mb-2">
                  <input type="number" id="age" name="age" placeholder="Age" value='<?php echo $udata['age']; ?>' class="form-control form-control-lg" />
                </div>

                    <span id="adid">*</span>
                <div class="form-outline mb-2">
                  <input type="text" id="address" name="address" placeholder="Address" value='<?php echo $udata['address']; ?>' class="form-control form-control-lg" />
                </div>

	            	<span id="bid">*</span>
                <div class="form-outline mb-4">
                  <input type="text" id="blood" name="bloodgroup" placeholder="Blood Group" value='<?php echo $udata['blood_group']; ?>' class="form-control form-control-lg" />
                </div>

                <div class="d-flex justify-content-center">
                  <input type="submit" name="submit"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" value="Update">
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-------------------------------------------------------------------------------------------->



<!-------------------------------------------------------------------------------------------->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="popup/js/jquery.min.js"></script>
    <script src="popup/js/popper.js"></script>
    <script src="popup/js/bootstrap.min.js"></script>
    <script src="popup/js/main.js"></script>
</body>
</body>
</html>