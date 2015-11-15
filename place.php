<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
?>
		<div class="input-group input-group-lg" style="width:50%">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1">
	   	</div>
<?php
	if (isset($_GET['id'])) {
		getPlaceInfo(is_numeric($_GET['id']) ? htmlspecialchars($_GET['id']) : 0);
	}
	elseif (isset($_GET['name'])) {
		getPlaceInfo(htmlspecialchars($_GET['name']), 1);
	}
	else getPlaceInfo(0);
	//print_r($result); 
  	require_once("frames/footer.php");
?>