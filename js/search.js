		$(document).keypress(function(e) {
		    if(e.which == 13) {
		    	var search = $('#search').val();
		    	search = search.replace(' ', '_');
				if ($('#search').hasClass("search_map")) {
					window.location.replace("map?s=" + encodeURIComponent(search));
				} else {
					window.location.replace("place?s=" + encodeURIComponent(search));
				}
			}
		});