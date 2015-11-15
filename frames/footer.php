	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(function() { // Hack to get places highlighed on right page 
			var page = location.pathname.substring(1);
			if (page.includes("place")) {
				$('ul.nav a[href="places"]').parent().addClass('active');
			} else {
				$('ul.nav a[href="./"]').parent().addClass('active');
			}
		});
	</script>
    <?php
      include_once("frames/google.php");
    ?>
<body>
</html>