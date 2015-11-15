		$(document).keypress(function(e) {
		    if(e.which == 13) {
		    	var search = $('#search').val();
		    	search = search.replace(' ', '_');
		        window.location.replace("place?name=" + encodeURIComponent(search));
			}
		});

		$("#results").click(function() {
			$('.dropdown-toggle').dropdown();
		});