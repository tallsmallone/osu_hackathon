<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
?>

	<input id="search" type="text" list="suggestions" class="form-control search_small" placeholder="Where would you like to eat?" data-toggle="dropdown">
	<datalist id="suggestions"></datalist>
<?php
	if (isset($_GET['id'])) {
		getPlaceInfo(is_numeric($_GET['id']) ? $_GET['id'] : 0, "id");
	}
	elseif (isset($_GET['s'])) {
		getPlaceInfo($_GET['s'], "search");
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
?>
	<script type="text/javascript" src='js/autocomplete.js'></script>
	<script typu="text/javascript" src='js/search.js'></script>
	
<?php
  	require_once("frames/footer.php");
?>