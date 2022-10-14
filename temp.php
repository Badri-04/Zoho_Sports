<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	redirect_from_admin();

		
	require 'Access_Functions/connect.php';

	if(isset($_POST['add'])){

		$cols = array('name','sport','max_participation','type','current_count','start_date','prize');
		$vals = array($_POST["tname"],$_POST["sport"],$_POST["max"],$_POST["type"],0,$_POST["date"],$_POST["prize"]);

		if(Insert($conn,"tournaments",$cols,$vals)){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
		}
	}
	
	if(isset($_POST['update'])){

		$scols = array('name','sport','max_participation','type','current_count','start_date','prize');
		$svals = array($_POST["tname"],$_POST["sport"],$_POST["max"],$_POST["type"],0,$_POST["date"],$_POST["prize"]);
		$ccols = array('tournament_id');
		$cvals = array($_POST['id']);


		if(Update($conn,"tournaments",$scols,$svals,$ccols,$cvals)){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
		}
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
	</style>

</head>
<body style="background-color: #EEEEFF;">

	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px; position: fixed; top: 0; left: 0; width: 100%;">
    <div class="container">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports Admin Portal</span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

          <li class="nav-item active">
            <a class="nav-link" data-toggle="modal" data-target="#exampleModalCenter" href="#">Add Tournament
              <span class="sr-only">(current)</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>

        </ul>
      </div>
    </div>
  	</nav>

<!-------------------------------------------------------------------------------------------->
<div class="container" style="padding-top: 120px;">
<div class="row">

	<?php
		$data = Fetch($conn,"tournaments");

		foreach ($data as $row) {
			$date = explode("-", $row['start_date']);
			$monthNum  = intval($date[1]);
			$dateObj   = DateTime::createFromFormat('!m', $monthNum);
			$month = substr($dateObj->format('F'),0,3);
			echo '
		    <div class="col-lg-12">
		        <div class="card card-margin react">
		            <div class="card-header no-border">
		                <h5 class="card-title" style="width:73%;">'.$row['name'].'</h5>
		            			<div class="widget-49-meeting-info">
		                            <a class="options nav-link"  data-toggle="modal" data-target="#exampleModalCenter'.$row['tournament_id'].'" href="#"><img src="images/edit.png" height="25px" style="margin-right:10px;">Update</a>
		                        </div>

		                        <div class="widget-49-meeting-info">
		                            <a class="options nav-link" href="analytics.php?id='.$row['tournament_id'].'"><img src="images/analytics.png" height="25px" style="margin-right:10px;">Analytics</a>
		                        </div>
		            </div>
		            <div class="card-body pt-0">
		                <div class="widget-49">
		                    <div class="widget-49-title-wrapper">
		                        <div class="widget-49-date-primary">
		                            <span class="widget-49-date-day">'.$date[2].'</span>
		                            <span class="widget-49-date-month">'.$month.'</span>
		                        </div>
		                        <div class="widget-49-meeting-info">
		                            <span class="widget-49-pro-title">'.$row['sport'].'</span>
		                            <span class="widget-49-meeting-time">Prize Money : '.$row['prize'].'/-</span>
		                        </div>

		                        
		                    </div>
		                    <ol class="widget-49-meeting-points">
		                        <li class="widget-49-meeting-item"><span>Maximum Participations : '.$row['max_participation'].'</span></li>
		                        <li class="widget-49-meeting-item"><span>Current Participants Count : '.$row['current_count'].'</span></li>
		                        <li class="widget-49-meeting-item"><span>Team/Single : '.$row['type'].'</span></li>
		                    </ol>
		                    <div class="widget-49-meeting-action">
		                        <a href="deleteTourney.php?id='.$row['tournament_id'].'" class="btn btn-sm btn-flash-border-primary" style="color: blue; text-decoration: underline; font-family: cursive;">Cancel Tournament</a>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>';

		    //----------------------------------------------------------------------------------------------------------------------
		    	echo '
					<div class="modal" id="exampleModalCenter'.$row['tournament_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
									      	<h3 class="mb-4">Update Tournament</h3>
							        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true" class="ion-ios-close"></span>
							        </button>
							      </div>
							      <div class="row">
								      <div class="col-md mb-md-0 mb-5">
									      <div class="modal-body p-0">
									      	<form action="admin.php" method="POST" onsubmit="return true" class="signin-form">
									      		<div class="form-group">
									      			<input type="text" name="tname" class="form-control" placeholder="Tournament Name" value="'.$row['name'].'">
									      		</div>
									            <div class="form-group">
									              <input type="number" name="max" class="form-control" placeholder="Maximum Participations" value="'.$row['max_participation'].'">
									            </div>
									            <div class="form-group">
									              <input type="number" name="prize" class="form-control" placeholder="Prize Money" value="'.$row['prize'].'">
									            </div>
									            <div class="form-group">
									      			<label style="font-size : 16px; text-decoration: underline;">Starting Date</label>
									      			<input type="date" name="date" class="form-control" placeholder="Starting Date" value="'.$row['start_date'].'">
									      		</div>
								            
								            
								          
									      </div>
									    </div>
									    <div class="col-md-1 divider"></div>
									    <div class="col-md">
									      <div class="modal-body p-0">
									      		<div class="form-group">
								              		<input type="text" name="sport" class="form-control" placeholder="Sport" value='.$row['sport'].'>
								            	</div>
									      		
									      		<div class="form-group">
									      			<label style="font-size : 16px; text-decoration: underline;">Event Type</label><br>
									      			<input type="radio" id="single" name="type" value="Single" ';
									      				if($row['type']=="Single"){ echo "checked"; }
									      			echo'>
													<label for="single">Single</label><br>
													<input type="radio" id="team" name="type" value="Team" ';
									      				if($row['type']=="Team"){ echo "checked"; }
									      			echo'>
													<label for="team">Team</label><br>
									      		</div>
									      		<input name="id" value="'.$row['tournament_id'].'" style="visibility:hidden;"/>
									      		<div class="form-group">
								            	<button type="submit" name="update" class="form-control btn btn-primary rounded submit px-3">Update</button>
								            </div>
								            
								          </form>
									      </div>
									    </div>
									  </div>
							    </div>
							  </div>
							</div>';

		    //----------------------------------------------------------------------------------------------------------------------

		}
	
	?>
    
</div>
</div>
<!-------------------------------------------------------------------------------------------->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
				      	<h3 class="mb-4">Add Tournament</h3>
		        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="ion-ios-close"></span>
		        </button>
		      </div>
		      <div class="row">
			      <div class="col-md mb-md-0 mb-5">
				      <div class="modal-body p-0">
				      	<form action="admin.php" method="POST" onsubmit="return true" class="signin-form">
				      		<div class="form-group">
				      			<input type="text" name="tname" class="form-control" placeholder="Tournament Name">
				      		</div>
				            <div class="form-group">
				              <input type="number" name="max" class="form-control" placeholder="Maximum Participations">
				            </div>
				            <div class="form-group">
				              <input type="number" name="prize" class="form-control" placeholder="Prize Money">
				            </div>
				            <div class="form-group">
				      			<label style="font-size : 16px; text-decoration: underline;">Starting Date</label>
				      			<input type="date" name="date" class="form-control" placeholder="Starting Date">
				      		</div>
			            
			            
			          
				      </div>
				    </div>
				    <div class="col-md-1 divider"></div>
				    <div class="col-md">
				      <div class="modal-body p-0">
				      		<div class="form-group">
			              		<input type="text" name="sport" class="form-control" placeholder="Sport">
			            	</div>
				      		
				      		<div class="form-group">
				      			<label style="font-size : 16px; text-decoration: underline;">Event Type</label><br>
				      			<input type="radio" id="single" name="type" value="Single">
								<label for="single">Single</label><br>
								<input type="radio" id="team" name="type" value="Team">
								<label for="team">Team</label><br>
				      		</div>
				      		<div class="form-group">
				      			<label style="font-size : 16px; text-decoration: underline;">Ending Date</label>
				      			<input type="date" name="enddate" class="form-control" placeholder="Ending Date">
				      		</div>
				      		
				      </div>
				    </div>
				  </div>
				  <div class="form-group">
			            	<button type="submit" name="add" class="form-control btn btn-primary rounded submit px-1" style="width:40%; margin-left: 30%; margin-top: 30px;">ADD</button>
			            </div>
			            
			          </form>
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

<!--------------------------------------------------------------------------------------------------------->
