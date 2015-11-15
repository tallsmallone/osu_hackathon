<?php
	if ('functions.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
		header("Location: ../");
		die('Error.');
	}

	function db_connect() {
		static $connection;
		if(!isset($connection)) {
			$config = parse_ini_file(__DIR__.'/../config.ini',true); 
			$connection = mysqli_connect($config['mysql']['host'],$config['mysql']['user'],$config['mysql']['pass'],$config['mysql']['db']);
		}

		if ($connection === false) {
			return false; 
		}
		return $connection;
	}

	if (db_connect() == false) {
		die("Something went wrong!");
	}

	function searchForKeyword($keyword)
	{
		$db = db_connect();
		$stmt = $db->prepare("SELECT name FROM 'places' WHERE id LIKE ? ;");

		$keyword = $keyword . '%';
		$stmt->bind_param('s', $keyword);

		$results = array();
		$stmt->execute();
		$stmt->bind_result($results);
		$stmt->fetch();

		if($results->numRows > 0)
		{
			$out = array();
			while($row = $results->fetch_assoc())
			{
				array_push($out, $row['name']);

			}
		}
		else
		{
			$out ="ERROR";
		}

		$db->close();
		return $out;
	}
?>