<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
?>
	<div>
		<div class="input-group input-group-lg" style="width:50%">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1">
	   	</div>
<?php
	getPlaceInfo();
?>
	</div>
<?php
  	require_once("frames/footer.php");
?>