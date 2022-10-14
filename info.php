<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/dates.php";
	require "Access_Functions/menu.php";
	
	redirect_from_user();

		
	require 'Access_Functions/connect.php';

	if(isset($_POST['close'])){
		Update($conn,"tournaments",array("reg_status"),array("Close"),array("tournament_id"),array($_POST['tid']));
		header("Location: analytics.php?id=".$_POST['tid'],TRUE,301);
	}

	if(isset($_POST['open'])){
		Update($conn,"tournaments",array("reg_status"),array("Open"),array("tournament_id"),array($_POST['tid']));
		header("Location: analytics.php?id=".$_POST['tid'],TRUE,301);
	}

	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Portal</title>

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
		#close{
			background-color: #DD0000;
			color: white;
			border: 0px;
			padding: 7px;
			border-radius: 3px;
		}
		#close:hover{
			background-color: #FF0000;
		}
		#open{
			background-color: #0D0;
			color: white;
			border: 0px;
			padding: 7px;
			border-radius: 3px;
		}
		#open:hover{
			background-color: #0E0;
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

</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px; position: fixed; top: 0; left: 0; width: 100%;">
    <div  style="padding:0px 10px 0px 60px; width: 100%;">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports Info Portal</span>
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
	      	$list = array("Home"=>"homepage.php" , "Join Team"=>"join.php" , "Profile"=>"profile.php","Logout"=>"logout.php");
	      	menu($list);

	    ?>
    </div>
  	</nav>

<!------------------------------------------------------------------------------------------------------------------------------>

<?php
	$tdata = Fetch($conn,"tournaments",array("tournament_id"),array($_GET['id']))[0];
	$pdata = Fetch($conn,"participants",array("tournament_id"),array($_GET['id']));
	if($tdata['type']=='Single'){
		$id_type = "user_id";	
		$tablename = "users";
		$name = "name";
	}
	else{
		$id_type = "team_id";
		$tablename = "teams";
		$name = "team_name";
	}


	$upid = Fetch($conn,"users",array("user_id"),array($_SESSION['user_id']))[0][$id_type];

	$date = explode("-", $tdata['start_date']);
	$monthNum  = intval($date[1]);
	$dateObj   = DateTime::createFromFormat('!m', $monthNum);
	$month = substr($dateObj->format('F'),0,3);

	$edate = add_days($tdata['start_date'],$tdata["duration"]);
	$edate = explode("-", $edate);
	$emonthNum  = intval($edate[1]);
	$edateObj   = DateTime::createFromFormat('!m', $emonthNum);
	$emonth = substr($edateObj->format('F'),0,3);
?>

<!------------------------------------------------------------------------------------------------------------------------------>

<div class="py-3" style="margin-top: 100px; padding: 120px 70px 30px 70px;">
  
  <!-- Card Start -->
  <div class="card" style="padding:20px;">
    <div class="row ">

      <div class="col-md-7 px-3">
        <div class="card-block px-6">
          <h4 class="card-title" style="color:blue;"><?php echo $tdata["name"]; ?></h4>
          <p class="card-text">
            <?php echo "<span style='width:50%; display:inline-block;'>Sport : ".$tdata["sport"]."</span>"; ?>
            <?php echo "Maximum Participations : ".$tdata["max_participation"]; ?>
          </p>
          <p class="card-text">
          	<?php echo "<span style='width:50%; display:inline-block;'>Prize Money : ".$tdata["prize"]."</span>"; ?>
          	<?php 
          	$cc = team_count($conn,$_GET['id']);
          	echo "Current Registrations : ".$cc; ?>
          </p>
          <p class="card-text">
          	<?php echo "Team/Single : ".$tdata["type"]; ?>
          </p>
        </div>
      </div>
      <!-- Carousel start -->
      <div class="col-md-5">
        	<p class="card-text" style="margin-top: 46px;">
            <?php echo "Starting Date : ".$date[2]."-".$month."-".$date[0]; ?>
          </p>
          <p class="card-text">
            <?php echo "Ending Date : ".$edate[2]."-".$emonth."-".$edate[0]; ?>
          </p>
          <p class="card-text" style="margin-top: 46px;">
          	<!-- <form onsubmit="return true" action="analytics.php" method="POST">
          		<input type="number" name="tid" style="visibility: hidden;" value=<?php echo '"'.$tdata["tournament_id"].'"'?>>
          		
	            <?php 
	            	// if($tdata["reg_status"]=="Open"){
	            	// 	echo '<input type="submit" name="close" id="close" value="Close Registrations">'; 
	            	// }
	            	// else{
	            	// 	echo '<input type="submit" name="open" id="open" value="Re-Open Registrations">';
	            	// }
	            ?>
            </form> -->
          </p>
      </div>
      <!-- End of carousel -->
    </div>
  </div>
  <!-- End of card -->

</div>

<div style="padding: 4px 70px 30px 70px;">
  <div class="card float-left" style="padding:20px; <?php
  	if($tdata["reg_status"]=="Open"){
  		echo 'width:100%;';
  	}
  	else{
  		echo 'width:40%;';
  	}
	?> ">
    <div class="row ">

      <div class="col-sm-12">
        <div class="card-block">
            <h4 class="card-title" style="color:blue;">Participants</h4>
	            <table class="table table-striped table-hover" style="text-align:center;">
	            	<th>
	            		<td>Name</td>
	            		<?php
	            			if($tdata['type']=="Team"){
	            				echo '<td>Member Count</td>';
	            			}
	            		?>
	            		<td>Status</td>
	            	</th>
		            <?php
		            	$c=1;
		            //--------------------------------------------------------------------------------------------------------------------
		            	foreach ($pdata as $par) {
		            		//----------------------------------------------------------------------------------------------------------------
		            		$pinfo = Fetch($conn,$tablename,array($id_type),array($par["participant_id"]))[0];
		            		//----------------------------------------------------------------------------------------------------------------
		            		echo '<p class="card-text">
		            						<tr>
		            							<td>'.$c.'</td>
		            							<td>'.$pinfo[$name].'</td>';
		            							if($tdata['type']=="Team"){
		            								$pc = player_count($conn,$pinfo[$id_type]);	
		            								echo '<td>'.$pc.'</td>';
		            							}
		            							echo '<td>'.$par['status'].'</td>
		            						<tr>
		            					</p>';
		            		$c+=1;
								  }
		            //--------------------------------------------------------------------------------------------------------------------
		            ?>
	            </table> 
        </div>
      </div>
    </div>
  </div>

 
    <div class="card float-right" style="padding:20px; <?php
  	if($tdata["reg_status"]=="Open"){
  		echo 'width:0%; display: none;';
  	}
  	else{
  		echo 'width:59%;';
  	}
	?> ">
      <div class="row">
        <div class="col-sm-12">
          <div class="card-block">
            <h4 class="card-title" style="color:blue; width: 70%; display: inline-block;"> Schedule</h4>
            
          		<?php
            if($tdata["reg_status"]){
            	$rem = Fetch($conn,"matches",array("tournament_id","winner_id"),array($_GET["id"],0));
					      $sch = Fetch($conn,"matches",array("tournament_id"),array($_GET["id"]));
					      $winner = Fetch($conn,"participants",array("tournament_id","status"),array($_GET['id'],"In"));
					      if(count($winner)==1){
					      	echo "<h3 style='color:red;'>".Fetch($conn,$tablename,array($id_type),array($winner[0]["participant_id"]))[0][$name]." Won</h3>";
					      }
					      					      	//-----------------------------------------------------------------------------------------------------
					      	echo '<div><table class="table table-borderless" style="text-align:center;">';
	            		echo '<tr class="thead-dark">
	            						<th></th>
	            						<th>Participant1</th>
	            						<th>Participant2</th>
	            						<th>Winner</th>
	            					</tr>';
	            		$c = 1;
	            		foreach($sch as $mat){
	            			$id1 = $mat['participant_id1'];
	            			$id2 = $mat['participant_id2'];
	            			$wid = $mat['winner_id'];
	            			$mid = $mat['match_id'];

	            			if($wid!=0){
	            				$namew = Fetch($conn,$tablename,array($id_type),array($wid))[0][$name];
	            			}
	            			else{
	            				$namew = "-";
	            			}

	            			$name1 = Fetch($conn,$tablename,array($id_type),array($id1))[0][$name];
	            			$name2 = Fetch($conn,$tablename,array($id_type),array($id2))[0][$name];
	            			
	            				echo '<tr '; 
	            				if($upid==$id1 OR $upid==$id2){
	            					echo 'style="background-color:#eee;"';
	            				}
	            				echo'>
		            							<td>'.$c.'</td>
		            							<td>'.$name1.'</td>
		            							<td>'.$name2.'</td>
		            							<td>'.$namew.'</td>
		            						</tr>';	
	            			
	            			$c+=1;
	            		}
	            		echo '</table></div>';
					      	//-----------------------------------------------------------------------------------------------------
					      
            	
            	
            }
            ?>

	          	
          </div>
        </div>
 
      </div>
    </div>
  </div>
 
 <br>
<br>
 

<!------------------------------------------------------------------------------------------------------------------------------>


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