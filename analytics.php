<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/dates.php";
	require "Access_Functions/menu.php";
	redirect_from_admin();

		
	require 'Access_Functions/connect.php';

	if(isset($_POST['close'])){
		Update($conn,"tournaments",array("reg_status"),array("Close"),array("tournament_id"),array($_POST['tid']));
		header("Location: analytics.php?id=".$_POST['tid'],TRUE,301);
	}

	if(isset($_POST['open'])){
		Update($conn,"tournaments",array("reg_status"),array("Open"),array("tournament_id"),array($_POST['tid']));
		header("Location: analytics.php?id=".$_POST['tid'],TRUE,301);
	}

	if(isset($_POST['disqualify'])){
		Update($conn,"participants",array("status"),array("Disqualified"),array("tournament_id","participant_id"),array($_GET['id'],$_POST['pid']));
		$mdata1 = Fetch($conn,"matches",array("participant_id1","winner_id"),array($_POST['pid'],0));
		$mdata2 = Fetch($conn,"matches",array("participant_id2","winner_id"),array($_POST['pid'],0));

		$mdata = array_merge($mdata1,$mdata2);

		foreach($mdata as $match){
			if($match['participant_id1']==$_POST['pid']){
				$sw = $match['participant_id2'];
			}
			else{
				$sw = $match['participant_id1'];
			}
			$mid = $match['match_id'];
			Update($conn,"matches",array("winner_id"),array($sw),array('match_id'),array($mid));
		}
		header("Location: analytics.php?id=".$_POST['tid'],TRUE,301);

	}

	//----------------------------------------------------------------------------------------------------------------------
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

	$date = explode("-", $tdata['start_date']);
	$monthNum  = intval($date[1]);
	$dateObj   = DateTime::createFromFormat('!m', $monthNum);
	$month = substr($dateObj->format('F'),0,3);

	$edate = add_days($tdata['start_date'],$tdata["duration"]);
	$edate = explode("-", $edate);
	$emonthNum  = intval($edate[1]);
	$edateObj   = DateTime::createFromFormat('!m', $emonthNum);
	$emonth = substr($edateObj->format('F'),0,3);
	//----------------------------------------------------------------------------------------------------------------------
	

	if(isset($_POST['schedule'])){
		$partic_data = Fetch($conn,"participants",array("tournament_id","status"),array($_GET['id'],"In"));
		shuffle($partic_data);
		$c = floor(count($partic_data)/2);
		for($i=0;$i<$c;$i++){
			$id1 = $partic_data[2*$i]["participant_id"];
			$id2 = $partic_data[2*$i+1]["participant_id"];

			$cols = array("tournament_id","participant_id1","participant_id2");
			$vals = array($_GET['id'],$id1,$id2);
			Insert($conn,"matches",$cols,$vals);
			header("Location: analytics.php?id=".$_GET['id'],TRUE,301);
		}
	}

	if(isset($_POST['first'])){
		$scol = array("winner_id");
		$sval = array($_POST['id1']);
		$ccol = array("match_id");
		$cval = array($_POST['mid']);

		Update($conn,"matches",$scol,$sval,$ccol,$cval);
		Update($conn,"participants",array("status"),array("Out"),array("tournament_id","participant_id"),array($_GET['id'],$_POST['id2']));
		header("Location: analytics.php?id=".$_GET['id'],TRUE,301);
	}

	if(isset($_POST['second'])){
		$scol = array("winner_id");
		$sval = array($_POST['id2']);
		$ccol = array("match_id");
		$cval = array($_POST['mid']);

		Update($conn,"matches",$scol,$sval,$ccol,$cval);
		Update($conn,"participants",array("status"),array("Out"),array("tournament_id","participant_id"),array($_GET['id'],$_POST['id1']));
		header("Location: analytics.php?id=".$_GET['id'],TRUE,301);
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
		.team{
		  width: 100%;
		  text-align: center;
		  border-radius: 3px;
		  background-color: #e6e6e6;
		}
		.team:hover{
		 	background-color: #0d0;
		 }
		 .nav-link:hover{
		 		background-color: #0b0;
		 }
		 .dis{
		 	background-color: transparent;
		 	border: none;
		 }
		 .animated-progress {
			  width: 350px;
			  height: 25px;
			  border-radius: 5px;
			  margin: 5px;
			  border: 1px solid rgb(189, 113, 113);
			  overflow: hidden;
			  position: relative;
			  
			}

			.animated-progress span {
			  height: 100%;
			  display: block;
			  width: 0;
			  color: rgb(255, 251, 251);
			  line-height: 25px;
			  position: absolute;
			  text-align: end;
			  padding-right: 5px;
			  background-color: blue;
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
			.confirm{
				background: #fff;
				width: 65%;
				height: 100%;
				margin-left: 20%;
				padding: 10px;
			}
			.card-block>p{
				margin-right: 45px;
				margin-left: 5px;
				display: inline-block;
			}
	</style>

	

</head>
<body style="background-color: #EEEEFF;">

	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px; position: fixed; top: 0; left: 0; width: 100%;">
    <div  style="padding:0px 10px 0px 60px; width: 100%;">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports Admin Portal</span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

          <li class="nav-item active">
            <a class="nav-link navbar" href="admin.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>

          <li class="nav-item active">
            <a class="nav-link navbar" href="logout.php">Logout</a>
          </li>

        </ul>
      </div> -->
      <?php 
      	$list = array("Home"=>"admin.php" , "Logout"=>"logout.php");
      	menu($list);

    ?>
    </div>
  	</nav>

<!------------------------------------------------------------------------------------------------------------------------------>



<!------------------------------------------------------------------------------------------------------------------------------>

<div class="py-3" style="margin-top: 100px; padding: 120px 70px 30px 70px;">
  
  <!-- Card Start -->
  <div class="card" style="padding:20px;">
    <div class="row ">

      <div class="col-md-10 px-3">
        <div class="card-block px-6">
          <h2 class="card-title" style="color:blue;display:inline-block;"><?php echo $tdata["name"]; ?></h2>
            <?php echo "<span style='width:30%; display:inline-block;'> (".$tdata["sport"].")</span>"; ?>
            <br>

	          <p>
	            <?php echo "• ".$date[2]."-".$month."-".$date[0]." to ".$edate[2]."-".$emonth."-".$edate[0]; ?>
	          </p>
	          <p>
	          	<?php echo "• Prize Money : ".$tdata["prize"]; ?>
	          	
	          	<?php 
	          	$cc = team_count($conn,$_GET['id']);?>
	          </p>
	          <p>
	          	<?php
	          		if($tdata["type"]=="Single"){
	          			$pres = "• Individual";
	          		}
	          		else{
	          			$pres = "• Team";
	          		}
	          	 echo $pres." Participation"; ?>
	          </p>
          	
        	<p>
        		<div style="margin-left: 5px;">• Participants Count : </div>
        		<div style="display: inline-block; vertical-align: baseline;">
	          	<?php
	        		$percent = intval($cc*100/$tdata['max_participation']);
	        		echo '
					        	<div class="animated-progress progress-blue">
							        <span style="width:'.$percent.'%;">'.$cc.'</span>
							      </div>';
	        		?>
        		</div>
        		<div style="display: inline-block; vertical-align: baseline;">
        			<div class="animated-progress" style="border:0px; width:30px; margin-left: 0px;">
				        <b><?php echo $tdata['max_participation'];?></b>
				      </div>
        		</div>
          </p>
        	
        </div>
      </div>
      <!-- Carousel start -->
      <div class="col-md-2" style="padding-top:0px;">
      	<div class="card-block px-6" style="float:right; position: relative; right:0px; top:0px; margin-top:0px;">
          <h6 class="card-title">
          	<form onsubmit="return true" action="analytics.php" method="POST">
          		<input type="number" name="tid" style="visibility: hidden;"  style="float:right;" value=<?php echo '"'.$tdata["tournament_id"].'"'?>>
          		
	            <?php 
	            	if($tdata["reg_status"]=="Open"){
	            		echo '<input type="submit" name="close" id="close" value="Close Registrations">'; 
	            	}
	            	else{
	            		echo '<input type="submit" name="open" id="open" value="Re-Open Registrations">';
	            	}
	            ?>
          	</form>
          </h6>
        </div>
      </div>
      <!-- End of carousel -->
    </div>
  </div>
  <!-- End of card -->

</div>

<div style="padding: 4px 70px 30px 70px;">
  <div class="card float-left" style="padding:20px; 
  <?php
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
	            		<td></td>
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
		            								$pc = player_count($conn,$pinfo["team_id"]);
		            								echo '<td>'.$pc.'</td>';
		            							}
		            							echo '<td>'.$par['status'].'</td>
		            							<td>
		            								<a class="btn btn-sm btn-flash-border-primary" data-toggle="modal" data-target="#disqualify'.$c.'" href="#"><img src="images/cancel.png" width="20px"></a>
		            								
		            							</td>
		            						<tr>
		            					</p>';

		            		//--------------------------------------------------------------------------------
										echo '
												<div class="modal fade" id="disqualify'.$c.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
														    <div class="modal-content confirm" style="padding:0px !important;">
														      <div class="row">
																      <div class="modal-body" style="border-radius:5px; padding:25px 40px 0px 30px;">
																      	<form action="analytics.php?id='.$_GET['id'].'" method="POST" onsubmit="return true" class="signin-form">
																      		<h6>
																      			Are you sure you want to Diaqualify '.$pinfo[$name].'?
																      		</h6>       
																      			<input type="hidden" name="pid" value="'.$par["participant_id"].'">
		            														<input type="hidden" name="tid" value="'.$_GET['id'].'">
																      		<div class="form-group">
														            		<button type="submit" name="disqualify" class="form-control btn btn-primary rounded submit px-1" style="width:30%; height:35px; margin-left: 70%;">Disqualify</button>

														            		<button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close" style="height:27px;">
																		          <span aria-hidden="true" class="ion-ios-close" style="position:relative; right:7px;"></span>
																		        </button>
														            	</div>
														            </form>  
																      </div>
																    
																  </div>
														    </div>
														  </div>
												</div>';
		            		//--------------------------------------------------------------------------------
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
            <h4 class="card-title" style="color:blue; width: 70%; display: inline-block;">Schedule And Results</h4>
            <?php
            if($tdata["reg_status"]!="Open"){
            	$rem = Fetch($conn,"matches",array("tournament_id","winner_id"),array($_GET["id"],0));
            	if(count($rem)==0){
					      $sch = Fetch($conn,"matches",array("tournament_id"),array($_GET["id"]));
					      $winner = Fetch($conn,"participants",array("tournament_id","status"),array($_GET['id'],"In"));
					      if(count($winner)==1){
					      	echo "<h3 style='color:red;'>".Fetch($conn,$tablename,array($id_type),array($winner[0]["participant_id"]))[0][$name]." Won</h3>";
					      }
					      else{
	            		echo '<form onsubmit="return true" action="analytics.php?id='.$_GET['id'].'" class="btn btn-primary btn-sm float-right" method="POST" style="width:20%;">
						            	<input type="submit" class="btn" name="schedule" value="Generate">
						          	</form>';
					      }
					      if(count($sch)>0){
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
	            			
	            				echo '<tr>
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
            	}
            	else{
            		echo '<div><table class="table table-borderless" style="text-align:center;">';
            		echo '<tr class="thead-dark">
            						<th></th>
            						<th>Participant1</th>
            						<th>Participant2</th>
            						<th>Winner</th>
            					</tr>';
            		$sch = Fetch($conn,"matches",array("tournament_id"),array($_GET['id']));
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
            			if($namew!="-"){
            				echo '<tr>
	            							<td>'.$c.'</td>
	            							<td>'.$name1.'</td>
	            							<td>'.$name2.'</td>
	            							<td>'.$namew.'</td>
	            						</tr>';	
            			}
            			else{
            				echo '<tr>
		            							<td>'.$c.'</td>
		            							<td>
		            								<a class="btn btn-sm btn-flash-border-primary team" data-toggle="modal" data-target="#first'.$mid.'" href="#">'.$name1.'</a>
		            							</td>
		            							<td>
		            								<a class="btn btn-sm btn-flash-border-primary team" data-toggle="modal" data-target="#second'.$mid.'" href="#">'.$name2.'</a>
		            							</td>
		            							<td>'.$namew.'</td>
	            						</tr>';

	            			//--------------------------------------------------------------------------------------------------------------
	            			echo '
											<div class="modal fade" id="first'.$mid.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-centered" role="document">
													    <div class="modal-content confirm" style="padding:0px !important;">
													      <div class="row">
															      <div class="modal-body" style="border-radius:5px; padding:25px 40px 0px 30px;">
															      	<form action="analytics.php?id='.$_GET['id'].'" method="POST" onsubmit="return true" class="signin-form">
															      		<h6>
															      			Confirm Winner - '.$name1.'?
															      		</h6>       
															      		<input type="hidden" name="id1" value="'.$id1.'">            									
					            									<input type="hidden" name="id2" value="'.$id2.'">
					            									<input type="hidden" name="mid" value="'.$mid.'">
															      		<div class="form-group">
													            		<button type="submit" name="first" class="form-control btn btn-primary rounded submit px-1" style="width:30%; height:35px; margin-left: 70%;">Confirm</button>
													            		<button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close" style="height:27px;">
																	          <span aria-hidden="true" class="ion-ios-close" style="position:relative; right:7px;"></span>
																	        </button>
													            	</div>
													            </form>  
															      </div>
															    
															  </div>
													    </div>
													  </div>
											</div>';
											//-------------------------------------------------------------------------------------------------------------
											echo '
											<div class="modal fade" id="second'.$mid.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-centered" role="document">
													    <div class="modal-content confirm" style="padding:0px !important;">
													      <div class="row">
															      <div class="modal-body" style="border-radius:5px; padding:25px 40px 0px 30px;">
															      	<form action="analytics.php?id='.$_GET['id'].'" method="POST" onsubmit="return true" class="signin-form">
															      		<h6>
															      			Confirm Winner - '.$name2.'?
															      		</h6>       
															      		<input type="hidden" name="id1" value="'.$id1.'">            									
					            									<input type="hidden" name="id2" value="'.$id2.'">
					            									<input type="hidden" name="mid" value="'.$mid.'">
															      		<div class="form-group">
													            		<button type="submit" name="second" class="form-control btn btn-primary rounded submit px-1" style="width:30%; height:35px; margin-left: 70%;">Confirm</button>
													            		<button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close" style="height:27px;">
																	          <span aria-hidden="true" class="ion-ios-close" style="position:relative; right:7px;"></span>
																	        </button>
													            	</div>
													            </form>  
															      </div>
															    
															  </div>
													    </div>
													  </div>
											</div>';
											//-------------------------------------------------------------------------------------------------------------
            			}


            			$c+=1;
            		}
            		echo '</table></div>';
            	}
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