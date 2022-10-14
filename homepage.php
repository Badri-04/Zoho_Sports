<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/menu.php";
	redirect_from_user();

		
	require 'Access_Functions/connect.php';
	

	if(isset($_POST['create'])){

		$cols = array('team_name','leader_id');
		$vals = array($_POST["tname"],$_SESSION['user_id']);

		$status = Insert($conn,"teams",$cols,$vals);

		if ($status){
			$data = Fetch($conn,"teams",array("leader_id"),array($_SESSION['user_id']))[0];
			if(Update($conn,"users",array("team_id"),array($data["team_id"]),array("user_id"),array($_SESSION["user_id"]))){
				header("Location: http://localhost/Zoho_sports/homepage.php",TRUE,301);
			}
		}
		else{
			header("Location: http://localhost/Zoho_sports/homepage.php",TRUE,301);
		}
	}

	if(isset($_GET['join_id'])){
		$tdata = Fetch($conn,"tournaments",array('tournament_id'),array($_GET['join_id']))[0];
		$ttype = $tdata['type'];
		if($ttype=="Single"){
			Insert($conn,'participants',array("tournament_id","participant_id"),array($_GET['join_id'],$_SESSION['user_id']));
			header("Location: homepage.php",True,301);
		}
		elseif ($ttype=="Team") {
			$pdata = Fetch($conn,"users",array('user_id'),array($_SESSION['user_id']))[0];
			if($pdata['team_id']){
				$part = Fetch($conn,"participants",array('tournament_id','participant_id'),array($_GET['join_id'],$pdata['team_id']));
				if(count($part)==0 AND Insert($conn,'participants',array("tournament_id","participant_id"),array($_GET['join_id'],$pdata['team_id']))){}
				header("Location: homepage.php",True,301);
			}
			else{
				header("Location: homepage.php",True,301);
			}
		}
		header("Location: homepage.php",True,301);
	}

	if(isset($_GET['exit_id'])){
		$tdata = Fetch($conn,"tournaments",array("tournament_id"),array($_GET['exit_id']))[0];
		if($tdata['type']=="Single"){
			Delete($conn,"participants",array("tournament_id","participant_id"),array($_GET['exit_id'],$_SESSION["user_id"]));
		}
		else{
			Delete($conn,"participants",array("tournament_id","participant_id"),array($_GET['exit_id'],teamId($conn,$_SESSION["user_id"])));
		}
		header("Location: homepage.php",True,301);
	}
	
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


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	
		<link rel="stylesheet" href="popup/css/ionicons.min.css">
		<link rel="stylesheet" href="popup/css/style.css">
		<link rel="stylesheet" type="text/css" href="css/menu.css">

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
		.pointer:hover{
			cursor: pointer;
	    border: 0;
	    box-shadow: 5px 5px 15px 5px rgba(82, 63, 105, 0.4);
	    -webkit-box-shadow: 5px 5px 15px 5px rgba(82, 63, 105, 0.4);
	    -moz-box-shadow: 5px 5px 15px 5px rgba(82, 63, 105, 0.4);
	    -ms-box-shadow: 5px 5px 15px 5px rgba(82, 63, 105, 0.4);
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
			.form-group>input{
				color: black !important;
			}
			
	</style>

	<script type="text/javascript">
		function analytics(link){
			window.location.href = link;
		}
	</script>

</head>
<body>
	

	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px;">
    <div style="padding:0px 10px 0px 60px; width: 100%;">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports</span>
    
      <!-- <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

          <li class="nav-item active">
            <a class="nav-link" href="homepage.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>	

          <li class="nav-item active">
            <a class="nav-link" data-toggle="modal" data-target="#exampleModalCenter" href="#">Create Team
              <span class="sr-only">(current)</span>
            </a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="join.php">Join Team
              <span class="sr-only">(current)</span>
            </a>
          </li>

					<li class="nav-item">
            <a class="nav-link" href="profile.php"><img src="images/profile.png" width="25px"></a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>

        </ul>
      </div> -->

      <?php 
      	$list = array("Home"=>"homepage.php" , "Create Team"=>"#" , "Join Team"=>"join.php" , "Profile"=>"profile.php","Logout"=>"logout.php");
      	menu($list);

      ?>

    </div>
  	</nav>

<!-------------------------------------------------------------------------------------------->
<div style="padding: 120px 70px 30px 70px;">
<div class="row">

	<?php
		
		$data = Fetch($conn,"tournaments");
		$pdata = Fetch($conn,"users",array('user_id'),array($_SESSION['user_id']))[0];

		foreach ($data as $row) {
			$date = explode("-", $row['start_date']);
			$monthNum  = intval($date[1]);
			$dateObj   = DateTime::createFromFormat('!m', $monthNum);
			$month = substr($dateObj->format('F'),0,3);
			echo '
		    <div class="col-lg-3">
		        <div class="pointer card card-margin react">
		            
		            <div class="card-body pt-3">
		                <div class="widget-49">
		                    <div class="widget-49-title-wrapper"  onclick="analytics(\'info.php?id='.$row['tournament_id'].'\')">
		                        <div class="widget-49-date-primary">
		                            <span class="widget-49-date-day">'.$date[2].'</span>
		                            <span class="widget-49-date-month">'.$month.'</span>
		                        </div>
		                        <div class="widget-49-meeting-info">
		                            <span class="widget-49-pro-title">'.$row['name'].'</span>
		                            <span class="widget-49-pro-title">'.$row['sport'].'</span>
		                        </div>

		                        
		                    </div>
		                    
		                    <div class="widget-49-meeting-action" style="color: red;">
		                    		';

		                    		$ttype = Fetch($conn,"tournaments",array("tournament_id"),array($row['tournament_id']))[0]['type'];

		                    		$checkuser = Fetch($conn,"participants",array("tournament_id","participant_id"),array($row['tournament_id'],$_SESSION['user_id']));
		                    		$checkteam = Fetch($conn,"participants",array("tournament_id","participant_id"),array($row['tournament_id'],teamId($conn,$_SESSION['user_id'])));
		                    		$cc = team_count($conn,$row['tournament_id']);

		                    		if($row['reg_status']=="Open" AND $ttype=="Single" AND count($checkuser)>0){
		                    			echo '
						                        <a href="homepage.php?exit_id='.$row['tournament_id'].'" class="btn btn-sm btn-flash-border-primary" style="text-decoration: underline; font-family: cursive;">Exit</a>
						                        ';
		                    		}
		                    		elseif($row['reg_status']=="Open" AND $ttype=="Team" AND count($checkteam)>0){
		                    			echo '
						                        <a href="homepage.php?exit_id='.$row['tournament_id'].'" class="btn btn-sm btn-flash-border-primary" style="text-decoration: underline; font-family: cursive;">Exit</a>
						                        ';
		                    		}
		                    		elseif($row['reg_status']=="Close" AND $ttype=="Single" AND count($checkuser)>0){
		                    			echo $checkuser[0]['status'];
		                    		}
		                    		elseif($row['reg_status']=="Close" AND $ttype=="Team" AND count($checkteam)>0){
		                    			echo $checkteam[0]['status'];
		                    		}
		                    		elseif($row['reg_status']=="Close"){
		                    			echo "Registrations Closed";
		                    		}
		                    		elseif($row['type']=="Team" AND !$pdata['team_id']){
		                    			echo "Create or Join Team To Register";
		                    		}
		                    		elseif($cc<$row['max_participation']){
		                    			echo '
						                        <a href="homepage.php?join_id='.$row['tournament_id'].'" class="btn btn-sm btn-flash-border-primary" style="color: blue; text-decoration: underline; font-family: cursive;">Join Now</a>
						                        ';
		                    		}
		                    		else{
		                    			echo "Max Participations Reached";
		                    		}
		                    		
		                        echo'
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>';
		}
	
	?>
    
</div>
</div>
<!-------------------------------------------------------------------------------------------->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" style="width:30%" role="document">
		    <div class="modal-content">
		      <div class="modal-header" style="color:black !important;">
				      	<h3  style="color:black !important;">Create Team</h3>
		        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="ion-ios-close"></span>
		        </button>
		      </div>
		      <div class="row">
			      <div class="col-md mb-md-0 mb-5">
				      <div class="modal-body p-0">
				      	<?php
				      		$tdata = Fetch($conn,"users",array('user_id'),array($_SESSION['user_id']))[0]['team_id'];
				      		if(!$tdata){
					      		echo '
						      	<form action="homepage.php" method="POST" onsubmit="return true" class="signin-form">
						      		<div class="form-group">
						      			<input type="text" name="tname" class="form-control" placeholder="Team Name" style="color:black;">
						      		</div>
									<div class="form-group" style="align-items:center;">
					            		<button type="submit" name="create" class="form-control btn btn-primary rounded submit px-3" style="width:30%">CREATE</button>
					                </div>
					            </form>';
				        	}
				        	else{
				        		$tname = Fetch($conn,"teams",array("team_id"),array($tdata))[0]["team_name"];
				        		echo '<p style="color:#b00;">!!You\'re Already a Member of '.$tname.' Team.</p>';
				        	}
				      	?>
				      </div>
				  </div>
				    
			  </div>
		    </div>
		  </div>
	</div>

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