<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/menu.php";
	redirect_from_user();

		
	require 'Access_Functions/connect.php';

	if(isset($_POST['join'])){
		$tid = $_POST['tid'];
		$uid = $_SESSION['user_id'];
		$tdata = Fetch($conn,"users",array('user_id'),array($_SESSION['user_id']))[0]['team_id'];
		if(!$tdata){
			if(Update($conn,"users",array("team_id"),array($tid),array("user_id"),array($uid))){
				header("Location: join.php",TRUE,301);
			}
		}
		header("Location: join.php",TRUE,301);
	}

	if(isset($_POST['exit'])){
		$tid = $_POST['tid'];
		$uid = $_SESSION['user_id'];
		if(Update($conn,"users",array("team_id"),array("None"),array("user_id"),array($uid))){
			header("Location: join.php",TRUE,301);
		}
		header("Location: join.php",TRUE,301);
	}

	if(isset($_POST['delete'])){
		$tid = $_POST['tid'];
		$uid = $_SESSION['user_id'];
		$tours = Fetch($conn,'participants',array('participant_id'),array($tid));
		Delete($conn,"teams",array("team_id"),array($tid));
		Update($conn,"users",array("team_id"),array("None"),array("team_id"),array($tid));
		foreach ($tours as $tourney) {
			$tour_data = Fetch($conn,'tournaments',array('tournament_id'),array($tourney['tournament_id']))[0];
			if($tour_data['type']=='Team'){
				Delete($conn,'participants',array('tournament_id','participant_id'),array($tourney['tournament_id'],$tid));
			}
		}
		header("Location: join.php",TRUE,301);
	}

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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
		<link rel="stylesheet" type="text/css" href="css/user_cards.css">

	<style type="text/css">
		.sticky{
			margin-bottom: 30px; 
			position: fixed; 
			top: 0; 
			left: 0; 
			width: 100%;z-index: 1;
		}
		.options{
			color: #fff;
			background-color: #09143c;
			border-radius: 4px;
			height: 40px;
			margin: 3px;
		}
		.options:hover{
			background-color: #fff;
			color: #a86008;
			border-color: #ffba56;
		}

		.card:hover{
			background: linear-gradient(to right, #a86008, #ffba56) !important;
    	color: #fff;
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
				color: black !important;
			}
			::placeholder{
				color: gray !important;
			}
	</style>

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
		
		$data = Fetch($conn,"teams");
		$ptid = Fetch($conn,"users",array("user_id"),array($_SESSION['user_id']))[0]['team_id'];

		for ($i=0 ; $i<count($data) ; $i+=2) {
			$row = $data[$i];
			$lead_id = $row['leader_id'];

			echo '
		    <div class="col-md-12 ">
			    <div class="row "> 
			        <div class="col-xl-6 col-lg-6">';

			        if($ptid!=$row['team_id']){
			        	$pc = player_count($conn,$row['team_id']);

			            echo '<div class="card l-bg-blue-dark">
			                <div class="card-statistic-3 p-4">
			                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
			                    <div class="mb-4">
			                        <h5 class="card-title mb-0">'.$row['team_name'].'</h5>
			                    </div>
			                    <div class="row align-items-center mb-2 d-flex">
			                        <div class="col-9">
			                            <h2 class="d-flex align-items-center mb-0">
			                                '.$pc.'
			                            </h2>
			                        </div>
			                        <div class="col-3 text-right">
			                            <span>
				                            <form action="join.php" method="POST" onsubmit="return true">
				                            	<input name="tid" value="'.$row['team_id'].'" style="visibility:hidden;"/>
				                            	<input class="options nav-link" type="submit" name="join" value="Join"/></form>
			                            </span>
			                        </div>
			                    </div>';
			        }
			        else{
			        	$pc = player_count($conn,$row['team_id']);
			        	echo '<div class="card l-bg-green-dark">
			                <div class="card-statistic-3 p-4">
			                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
			                    <div class="mb-4">
			                        <h5 class="card-title mb-0">'.$row['team_name'].'</h5>
			                    </div>
			                    <div class="row align-items-center mb-2 d-flex">
			                        <div class="col-9">
			                            <h2 class="d-flex align-items-center mb-0">
			                                '.$pc.'
			                            </h2>
			                        </div>
			                        <div class="col-3 text-right">
			                            <span>
				                            <form action="join.php" method="POST" onsubmit="return true">
				                            	<input name="tid" value="'.$row['team_id'].'" style="visibility:hidden;"/>';
				                            	if($lead_id==$_SESSION['user_id']){
				                            		echo '<input class="options nav-link" type="submit" name="delete" value="Delete"/>';
				                            	}
				                            	else{
				                            		echo '<input class="options nav-link" type="submit" name="exit" value="Exit"/>';
				                            	}

				                            echo '</form>
			                            </span>
			                        </div>
			                    </div>';
			        }

			                echo '</div>
			            </div>
			        </div>';


			        if(($i+1)<count($data)){
			        	$row = $data[$i+1];
								$lead_id = $row['leader_id'];

			        echo '<div class="col-xl-6 col-lg-6">';

			        if($ptid!=$row['team_id']){
			        	$pc = player_count($conn,$row['team_id']);

			            echo '<div class="card l-bg-blue-dark">
			                <div class="card-statistic-3 p-4">
			                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
			                    <div class="mb-4">
			                        <h5 class="card-title mb-0">'.$row['team_name'].'</h5>
			                    </div>
			                    <div class="row align-items-center mb-2 d-flex">
			                        <div class="col-9">
			                            <h2 class="d-flex align-items-center mb-0">
			                                '.$pc.'
			                            </h2>
			                        </div>
			                        <div class="col-3 text-right">
			                            <span>
				                            <form action="join.php" method="POST" onsubmit="return true">
				                            	<input name="tid" value="'.$row['team_id'].'" style="visibility:hidden;"/>
				                            	<input class="options nav-link" type="submit" name="join" value="Join"/></form>
			                            </span>
			                        </div>
			                    </div>';
			        }
			        else{
			        	$pc = player_count($conn,$row['team_id']);
			        	echo '<div class="card l-bg-green-dark">
			                <div class="card-statistic-3 p-4">
			                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
			                    <div class="mb-4">
			                        <h5 class="card-title mb-0">'.$row['team_name'].'</h5>
			                    </div>
			                    <div class="row align-items-center mb-2 d-flex">
			                        <div class="col-9">
			                            <h2 class="d-flex align-items-center mb-0">
			                                '.$pc.'
			                            </h2>
			                        </div>
			                        <div class="col-3 text-right">
			                            <span>
				                            <form action="join.php" method="POST" onsubmit="return true">
				                            	<input name="tid" value="'.$row['team_id'].'" style="visibility:hidden;"/>';
				                            	if($lead_id==$_SESSION['user_id']){
				                            		echo '<input class="options nav-link" type="submit" name="delete" value="Delete"/>';
				                            	}
				                            	else{
				                            		echo '<input class="options nav-link" type="submit" name="exit" value="Exit"/>';
				                            	}

				                            echo '</form>
			                            </span>
			                        </div>
			                    </div>';
			        }

			                echo '</div>
			            </div>
			        </div>';
			      }

			    echo '</div> 

			</div>';
		}
	
	?>
    
</div>
</div>
<!-------------------------------------------------------------------------------------------->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" style="width:30%" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
				      	<h3 class="mb-4">Create Team</h3>
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
						      	<form action="join.php" method="POST" onsubmit="return true" class="signin-form">
						      		<div class="form-group">
						      			<input type="text" name="tname" class="form-control" placeholder="Team Name">
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