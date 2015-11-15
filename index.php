<?php
	require_once("frames/head.php"); 
?>
	<div class"maindiv" >
		<img src="img/eduDine.png" class="logo">
			<div class="dropdown" id="search_div">
		   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1" id="search"data-toggle="dropdown" aria-expanded="false">
		   	
		   	<ul class="dropdown-menu results" aria-labelledby="dropdownMenu1">
		   		<li></li>
		   	</ul>
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
		        window.location.replace("http://osudining.warlockgaming.com/place.php?name=" + search.toLowerCase());
			}
		});
	</script>
<?php
  	require_once("frames/footer.php");
?>