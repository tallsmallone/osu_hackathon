<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
?>

	<input id="search_small" type="text" list="suggestions" class="form-control" placeholder="Where would you like to eat?" data-toggle="dropdown">
	<datalist id="suggestions"></datalist>
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
?>
	<script type="text/javascript" src='js/autocomplete.js'></script>
	<script typu="text/javascript">// for the page redirect
		$(document).keypress(function(e) {
		    if(e.which == 13) {
		    	var search = $('#search').val();
		    	search = search.replace(' ', '_');
		        window.location.replace("place?name=" + search.toLowerCase());
			}
		});

		$("#results").click(function() {
			$('.dropdown-toggle').dropdown();
		});
	</script>
<?php
  	require_once("frames/footer.php");
?>