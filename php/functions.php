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
?>