// For the auto complete
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
					if(!(data === ""))
					{
						var results = jQuery.parseJSON(data);
						var count = 0;
						$(results).each(function (key, value)
						{
							$('#results').append('<li class="item"><a href="places?name=' + value.replace(' ', '_').toLowerCase() + '">' + value + '</a></li>');
							count = count + 1;
						});
						if(count > 0)
						{
							if ($("#search").val() == "")
							{
								$("#suggestions").hide();
							}
							else
							{
								$("#suggestions").show();
							}
						}
						else
						{
							$("#suggestions").hide();
						}
					}

					/*$('.item').click(function() {
						var text = $(this).html();
						$('#search').val(text);
					});*/

				});
		}
		else
		{
			$('#results').html('');
		}
	});
});