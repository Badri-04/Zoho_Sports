<?php
	function Insert($conn,$table,$cols,$vals){
		$col = join(",",$cols);
		$col =  "(".$col.")";
		$val = join("','",$vals);
		$val =  "('".$val."')";
		$query = "INSERT INTO ".$table." ".$col." VALUES ".$val;
		try{
			if(mysqli_query($conn,$query)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		catch(Exception $e){
			return FALSE;
		}
	}

	function Fetch($conn,$table,$cols=array(),$vals=array()){
		$conditions = array();

		$d = array_combine($cols,$vals);
		foreach($d as $r=>$c){
		  $t = $r."='".$c."'";
		  array_push($conditions,$t);
		}
		$q = join(" AND ",$conditions);

		$query = "SELECT * FROM ".$table;
		if(count($conditions)>0){
			$query = $query." WHERE ".$q;
		}

		$fetch = mysqli_query($conn,$query);
		$data = mysqli_fetch_all($fetch,MYSQLI_ASSOC);

		return $data;
	}

	function Delete($conn,$table,$cols=array(),$vals=array()){
		$conditions = array();

		$d = array_combine($cols,$vals);
		foreach($d as $r=>$c){
		  $t = $r."='".$c."'";
		  array_push($conditions,$t);
		}
		$q = join(" AND ",$conditions);

		$query = "DELETE FROM ".$table;
		if(count($conditions)>0){
			$query = $query." WHERE ".$q;
		}
		if(mysqli_query($conn,$query)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	function Update($conn,$table,$scols,$svals,$ccols=array(),$cvals=array()){
		$sets = array();
		$d = array_combine($scols,$svals);
		foreach($d as $r=>$c){
		  $t = $r."='".$c."'";
		  array_push($sets,$t);
		}
		$qs = join(", ",$sets);

		$conditions = array();

		$d = array_combine($ccols,$cvals);
		foreach($d as $r=>$c){
		  $t = $r."='".$c."'";
		  array_push($conditions,$t);
		}
		$q = join(" AND ",$conditions);

		$query = "UPDATE ".$table." SET ".$qs;
		if(count($conditions)>0){
			$query = $query." WHERE ".$q;
		}


		if(mysqli_query($conn,$query)){
			return TRUE;
		}
		else{
			return FALSE;
		}

	}

	function CreateTable($conn,$query){
		try{
			if(mysqli_query($conn,$query)){
				return TRUE;
			}
			return FALSE;
		}
		catch(Exception $e){}
	}

	function teamId($conn,$userid){
		$data = Fetch($conn,"users",array("user_id"),array($userid))[0];
		return $data["team_id"];
	}

	function team_count($conn,$tid){
		$temp = Fetch($conn,"participants",array("tournament_id"),array($tid));
		return count($temp);
	}

	function player_count($conn,$tid){
		$temp = Fetch($conn,"users",array("team_id"),array($tid));
		return count($temp);
	}

	function tourDates($conn){
		$query = "SELECT start_date FROM tournaments";

		$fetch = mysqli_query($conn,$query);
		$data = mysqli_fetch_all($fetch,MYSQLI_ASSOC);

		$res = array();
		foreach($data as $k=>$v){
			array_push($res,$v['start_date']);
		}

		return $res;
	}

	function tourDurations($conn){
		$query = "SELECT duration FROM tournaments";

		$fetch = mysqli_query($conn,$query);
		$data = mysqli_fetch_all($fetch,MYSQLI_ASSOC);

		$res = array();
		foreach($data as $k=>$v){
			array_push($res,$v['duration']);
		}

		return $res;
	}
?>