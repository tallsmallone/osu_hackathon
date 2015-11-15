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
					console.log(data);
					var results = jQuery.ParseJSON(data);
					$(results).each(function (key, value)
					{
						console.log(value);
						$('#results').append('<div class="item">' + value + '</div>');
					});

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