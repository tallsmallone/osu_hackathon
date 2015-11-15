<?php
	require_once("frames/head.php"); 
?>
	<div class"maindiv" >
		<img src="img/eduDine.png" class="logo">
			<div id="search_div">
		   		<input id="search" type="text" class="form-control" placeholder="Where to Eat" data-toggle="dropdown">
			   	<table border='0' align='center' id="suggestions">
			   		<tr>
			   			<td align='left'>
						   	<h4>Suggestions:</h4>
						   	<ul id="results">
						   	</ul>
						</td>
					</tr>
				<table>
		   </div>
	</div>
	<div id='debug'>
	</div>
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