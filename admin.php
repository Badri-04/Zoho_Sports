<?php
	session_start();
	require "Access_Functions/user_session.php";
	require "Access_Functions/db_access.php";
	require "Access_Functions/menu.php";
	require "Access_Functions/dates.php";
	
	redirect_from_admin();

		
	require 'Access_Functions/connect.php';

	if(isset($_POST['add'])){

		$cols = array('name','sport','max_participation','type','start_date','prize','duration');
		$vals = array($_POST["tname"],$_POST["sport"],$_POST["max"],$_POST["type"],$_POST["date"],$_POST["prize"],$_POST["duration"]);

		if(Insert($conn,"tournaments",$cols,$vals)){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
		}
	}

	if(isset($_POST['delete'])){
		header('Location: deleteTourney.php?id='.$_POST['id'],TRUE,301);
	}
	
	if(isset($_POST['update'])){

		$scols = array('name','sport','max_participation','type','start_date','prize','duration');
		$svals = array($_POST["tname"],$_POST["sport"],$_POST["max"],$_POST["type"],$_POST["date"],$_POST["prize"],$_POST["duration"]);
		$ccols = array('tournament_id');
		$cvals = array($_POST['id']);


		if(Update($conn,"tournaments",$scols,$svals,$ccols,$cvals)){
			header("Location: http://localhost/Zoho_sports/admin.php",TRUE,301);
		}
	}

	$tourDates = tourDates($conn);
	$tourDur = tourDurations($conn);

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
	<link rel="stylesheet" type="text/css" href="css/confirm.css">
	
	


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
			border-radius: 50%;
			margin: 3px;
			border: 1px solid #8888FF;
			display: inline-block;
		}
		.options:hover{
			background-color: #fff;
			color: #60a;
			border: 1px solid #60a;
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
				background: radial-gradient(circle, rgba(228,232,253,0.9) 0%, rgba(194,255,230,0.9) 100%);
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
			::placeholder{
				color: gray !important;
			}
			.form-group>input{
				color: black !important;
			}
			.form-control{
				border-bottom: 1px solid #080 !important;
			}
	</style>

	<script type="text/javascript">
		function analytics(link){
			window.location.href = link;
		}

		function addDays(date,days){
			var d = new Date(date);
			d.setDate(d.getDate() + parseInt(days));
			return d;
		}

		function isAcceptable(date1,days1,date2,days2){
			var end1 = addDays(date1,days1);
			var end2 = addDays(date2,days2);
      var d1 = addDays(date1,0);
      var d2 = addDays(date2,0);
			if((d2<d1 && end2<d1) || (d2>end1 && end2>end1)){
				return true;
			}
			else{
				return false;
			}
		}

		function dateValidation(){
			document.getElementById("warn").innerHTML="";

			var date = document.getElementById("sdate").value;
			var duration = document.getElementById("sdur").value;

			var d1 = addDays(date,0);
			var d2 = new Date();

			if(d1<d2){
				document.getElementById("warn").innerHTML="!!Invalid Date";
				return false;
			}

			var dates = <?php echo json_encode($tourDates);?>;
			var durs = <?php echo json_encode($tourDur);?>;

			for(let i=0; i<dates.length; i++){
				if(isAcceptable(dates[i],durs[i],date,duration)){
					continue;
				}
				else{
					document.getElementById("warn").innerHTML="!!Selected dates are already scheduled";
					return false;
				}
			}

			return true;
		}
	</script>

</head>
<body>
<!--
 style="background-image: radial-gradient(circle, rgba(228,232,253,1) 0%, rgba(194,255,230,1) 100%), url('images/bg.jpg');"
-->
	<nav class="navbar navbar-expand-lg navbar-dark mx-background-top-linear sticky" style="margin-bottom: 30px; position: fixed; top: 0; left: 0; width: 100%;">
    <div style="padding:0px 10px 0px 60px; width: 100%;">
      <span class="navbar-brand" href="admin.php" style="font-size: 30px;"> ZOHO Sports Admin Portal</span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">

          <li class="nav-item active">
            <a class="nav-link" data-toggle="modal" data-target="#exampleModalCenter" href="#">Add Tournament
              <span class="sr-only">(current)</span>
            </a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>

        </ul>
      </div> -->
      <?php 
	      	$list = array("Add Tournament"=>"#" , "Logout"=>"logout.php");
	      	menu($list);

	    ?>
    </div>
  	</nav>

<!-------------------------------------------------------------------------------------------->
<div  style="padding: 120px 70px 30px 70px;">
<div class="row">

	<?php
		$data = Fetch($conn,"tournaments");

		foreach ($data as $row) {
			$date = explode("-", $row['start_date']);
			$monthNum  = intval($date[1]);
			$dateObj   = DateTime::createFromFormat('!m', $monthNum);
			$month = substr($dateObj->format('F'),0,3);
			echo '
		    <div class="col-lg-3">
		        <div class="pointer card card-margin">
		            
		            <div class="card-body pt-0" style="margin-top:20px;">
		                <div class="widget-49">
		                    <div class="widget-49-title-wrapper"  onclick="analytics(\'analytics.php?id='.$row['tournament_id'].'\')">
		                        <div class="widget-49-date-primary">
		                            <span class="widget-49-date-day">'.$date[2].'</span>
		                            <span class="widget-49-date-month">'.$month.'</span>
		                        </div>
		                        <div class="widget-49-meeting-info">
		                        		<span class="widget-49-pro-title"><b>'.$row['name'].'</b></span>
		                            <span class="widget-49-pro-title">'.$row['sport'].'</span>
		                        </div>

		                        
		                    </div>
		                    
		                    <div class="widget-49-meeting-action">
		                    		<a class="btn btn-sm btn-flash-border-primary" data-toggle="modal" data-target="#exampleModalCenter'.$row['tournament_id'].'" href="#"><img src="images/edit.png" height="20px"></a>
		                        <a class="btn btn-sm btn-flash-border-primary" data-toggle="modal" data-target="#delete'.$row['tournament_id'].'" href="#"><img src="images/delete.png" height="20px"></a>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>';

		    //confirmBox('delete'.$row['tournament_id'],'admin.php',"Are you sure you want to delete ".$row['name']."?","delete","Delete",$row['tournament_id']);

		    //--------------------------------------------------------------------------------------------------------------

		    echo '
					<div class="modal fade" id="delete'.$row['tournament_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
							    <div class="modal-content confirm" style="padding:0px !important;">
							      <div class="row">
									      <div class="modal-body" style="border-radius:5px; padding:25px 40px 0px 30px;">
									      	<form action="admin.php" method="POST" onsubmit="return true" class="signin-form">
									      		<h6>
									      			Are you sure you want to Cancel '.$row['name'].'?
									      		</h6>       
									      		<input name="id" value="'.$row['tournament_id'].'" style="visibility:hidden;"/>
									      		<div class="form-group">
							            		<button type="submit" name="delete" class="form-control btn btn-primary rounded submit px-1" style="width:30%; height:35px; margin-left: 70%;">Delete</button>
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

		    //----------------------------------------------------------------------------------------------------------------------
		    	echo '
					<div class="modal fade" id="exampleModalCenter'.$row['tournament_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
									      	<h3 style="color:black;">Update Tournament</h3>
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

									      		<div class="form-group">
									      			<input type="number" name="duration" class="form-control" value="'.$row['duration'].'" placeholder="Duration(in Days)">
									      		</div>

									      		<input name="id" value="'.$row['tournament_id'].'" style="visibility:hidden;"/>
									      		
									      </div>
									    </div>
									  </div>
									  <div class="form-group">
			            	<button type="submit" name="update" class="form-control btn btn-primary rounded submit px-1" style="width:40%; margin-left: 30%; margin-top: 30px;">Update</button>
			            	</div>
			            	</form>
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
				      	<h3 style="color:black;">Add Tournament</h3>
		        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="ion-ios-close"></span>
		        </button>
		      </div>
		      <div class="row">
			      <div class="col-md mb-md-0 mb-5">
				      <div class="modal-body p-0">
				      	<form onsubmit="return dateValidation()" action="admin.php" method="POST" class="signin-form">
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
				      			<input type="date" name="date" id="sdate" class="form-control" placeholder="Starting Date">
				      			<span id="warn" style="color:#A00; font-size:13px;"></span>
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
				      			<input type="number" name="duration" id="sdur" class="form-control" placeholder="Duration(in Days)">
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