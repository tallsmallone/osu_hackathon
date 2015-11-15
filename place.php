<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
?>

		<div class="input-group input-group-lg" style="width:300px%">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1">
	   	</div>
<?php
	if (isset($_GET['id'])) {
		getPlaceInfo(is_numeric($_GET['id']) ? $_GET['id'] : 0, "id");
	}
	elseif (isset($_GET['name'])) {
		getPlaceInfo($_GET['name'], "name");
	}
	elseif (isset($_GET['tags'])) {
		getPlaceInfo($_GET['tags'], "tags");
	}
	elseif (isset($_GET['types'])) {
		getPlaceInfo($_GET['types'], "types");
	}
	else getPlaceInfo(0);
	//print_r($result); 
  	require_once("frames/footer.php");
?>