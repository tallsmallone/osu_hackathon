<?php
	require_once("frames/head.php"); 
?>
	<div class"maindiv" >
		<img src="img/eduDine.png" class="logo">
		<div class="dropdown">
			<div class="input-group input-group-lg" id="search_div">
		   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1" id="search"data-toggle="dropdown" aria-expanded="false">
		   	</div>
		   	<ul class="dropdown-menu results" aria-labelledby="dropdownMenu1">
		   		<li></li>
		   	</ul>
		   </div>
	</div>
	<div id='debug'>
	</div>
	<script type="text/javascript" src='js/autocomplete.js'></script>
<?php
  	require_once("frames/footer.php");
?>