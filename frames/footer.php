    </div>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	</div>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(function() {  
			var page = location.pathname.substring(1);
			if (page.includes("place")) {
				$('ul.nav a[href="places"]').parent().addClass('active');
			} else if (page.includes("map")) {
				$('ul.nav a[href="map"]').parent().addClass('active');
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