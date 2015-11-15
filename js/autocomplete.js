var MIN_LENGTH = 1;
$(document).ready(function() {
	$('#search').keyup(function() {
		var keyword = $('#search').val();
		//$('#debug').html(keyword);
		if(keyword.length >= MIN_LENGTH)
		{
			//$('#debug').html('WORKS MORE');
			$.get("../php/autocomplete.php", {keyword: keyword})
				.done(function(data)
				{
					//$('#debug').html('more');
					$('#results').html('');
					if(!(data === null))
					{
						var results = jQuery.parseJSON(data);
						$(results).each(function (key, value)
						{
							$('#results').append('<li>' + value + '</li>');
							//$('#debug').html(key + ", " + value);
						});
					}

					$('.item').click(function() {
						var text = $(this).html();
						$('#search').val(text);
					});

				});
		}
		else
		{
			$('#results').html('');
		}
	});
});