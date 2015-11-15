// For the auto complete
var MIN_LENGTH = 1;
$(document).ready(function() {
	$('#search').keyup(function() {
		var keyword = $('#search').val();
		if(keyword.length >= MIN_LENGTH)
		{
			$.get("php/autocomplete.php", {keyword: keyword})
				.done(function(data)
				{
					$('#results').html('');
					if(!(data === "")) {
						var results = jQuery.parseJSON(data);
						var count = 0;
						$(results).each(function (key, value)
						{
							if (!$('#suggestions').find('option[value="'+value+'"]').length)
								$('#suggestions').append('<option class="item" value="'+value+'"><a href="places?name=' + value.replace(' ', '_') + '">' + value + '</a></option>');
						});
					}
				});
		}
		else
		{
			$('#results').html('');
		}
	});
});