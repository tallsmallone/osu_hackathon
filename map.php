<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
	$config = parse_ini_file('config.ini',true); 
	$gm = $config["api"]["gm"];	
?>

	<input id="search" type="text" list="suggestions" class="form-control search_small search_map" value="<?php if (isset($_GET['s'])) echo $_GET['s']; ?>" placeholder="Where would you like to eat?" data-toggle="dropdown">
	<datalist id="suggestions"></datalist>
<?php
	if ($gm) {
?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gm; ?>">
    </script>	
<?php
	if (isset($_GET['id'])) {
		getMapInfo(is_numeric($_GET['id']) ? $_GET['id'] : 0, "id");
	}
	elseif (isset($_GET['s'])) {
		getMapInfo($_GET['s'], "search");
	}
	elseif (isset($_GET['name'])) {
		getMapInfo($_GET['name'], "name");
	}
	elseif (isset($_GET['tags'])) {
		getMapInfo($_GET['tags'], "tags");
	}
	elseif (isset($_GET['types'])) {
		getMapInfo($_GET['types'], "types");
	}
	else getMapInfo(0);
	} else {
		echo "The google maps api was not set up on this server!";
	}
?>
	<script type="text/javascript" src='js/autocomplete.js'></script>
	<script type="text/javascript" src='js/search.js'></script>
<?php
  	require_once("frames/footer.php");
?>