<?php
	function confirmBox($target,$page,$msg,$name,$disp){
		echo '<div class="modal fade" id="'.$target.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
						      	<h3 class="mb-4">Confirm</h3>
				        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true" class="ion-ios-close"></span>
				        </button>
				      </div>
				      <div class="row">
					      <div class="col-md mb-md-0 mb-5">
						      <div class="modal-body p-0">
						      	<form onsubmit="return true" action="'.$page.'.php" method="POST" class="signin-form">
						      		<div class="form-group">
						      			<p>'.$msg.'</p>
						      		</div>
						            
					            	<div class="form-group">
					            		<button type="submit" name="'.$name.'" class="form-control btn btn-primary rounded submit px-1" style="width:40%; margin-left: 30%; margin-top: 30px;">'$disp'</button>
					            	</div>
					            </form>
						      </div>
						    </div>
						
				    </div>
				  </div>
				</div>';
	}
?>