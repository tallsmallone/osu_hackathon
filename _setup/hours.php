<?php
echo "INSERT INTO `hours` (`id`) VALUES ";
	$max = 141;
	for ($i = 1; $i < $max; $i++)  {
		echo "('$i')";
		if ($i == ($max - 1))
			echo ";";
		else
			echo ",\n";
	}
?>