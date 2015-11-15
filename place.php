<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
	$id = isset($_GET['id']) && is_numeric($_GET['id']) ? htmlspecialchars($_GET['id']) : 0;
?>
		<div class="input-group input-group-lg" style="width:50%">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1">
	   	</div>
<?php
	getPlaceInfo($id);
	//print_r($result); 
  	require_once("frames/footer.php");
?>