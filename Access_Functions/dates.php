<?php
	function compare_dates($date1,$date2){
		if($date1>$date2){
			return 1;
		}
		elseif ($date1<$date2) {
			return -1;
		}
		else{
			return 0;
		}
	}

	function add_days($date,$days){
		return date('Y-m-d', strtotime($date. ' + '.$days.' days'));
	}

	function check_overlap($date1,$date2,$days1,$days2){
		$date12 = add_days($date1,$days1);
		$date22 = add_days($date2,$days2);

		if((compare($date1,$date2)==1 AND compare($date1,$date22)==1) OR (compare($date12,$date2)==-1 AND compare($date12,$date22)==-1)){
			return False;
		}
		return True;
	}
	
?>