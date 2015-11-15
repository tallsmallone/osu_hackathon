<?php
	require_once("frames/head.php"); 
?>
	<div class"dropdown maindiv" >
		<img src="img/eduDine.png" class="logo">
		<div class="input-group input-group-lg" id="search_div">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1" id="search">
	   	</div>
	   	<ul class="dropdown-menu results" aria-labelledby="dropdownMenu1">
	   	</ul>
	</div>
	<div id='debug'>
	</div>
	<script type="text/javascript" src='js/autocomplete.js'></script>
<?php
  	require_once("frames/footer.php");
?>