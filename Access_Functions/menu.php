<?php
	function menu($list){
		echo '<div class="dropdown" style="float:right;">
				  <button><img src="images/menu1.png" width=20px; height=20px;></button>
				  <div class="dropdown-content">';

				  foreach($list as $name=>$link){
				  	if($link=="#"){
				  		echo '<a data-toggle="modal" data-target="#exampleModalCenter" href="#">&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</a>';
				  	}
				  	else{
				  		echo '<a href="'.$link.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</a>';
				  	}
				  }

				  echo '</div>
			  </div>';
	}

	
?>