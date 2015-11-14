var MIN_LENGTH = 1;
$(document).ready(function() {
	$('#search').keyup(function() {
		var keyword = $('#search').val();
		if(keyword.length >= MIN_LENGTH)
		{
			$.get("php/autocomplete.php", {keyword: keyword})
				.done(function(data)
				{
					console.log(date);
				});
		}
	});
});