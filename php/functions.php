<?php
	if ('functions.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
		header("Location: ../");
		die('Error.');
	}

	function db_connect() {
		static $connection;
		if(!isset($connection)) {
			$config = parse_ini_file('config.ini',true); 
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
	$stmt = $db->prepare("SELECE name FROM 'places' WHERE name LIKE ?");

	$keyword = $keyword . '%';
	$stmt->bindParam(1, $keyword, PDO::PARAM_STR, 100);

	$queryOK = $stmt->execute();

	$results = array();

	if($queryOK)
	{
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	else
	{
		trigger_error('Error executing statement.', E_USER_ERROR);
	}

	$db=null;
	return $results;
}
?>