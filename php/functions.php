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
	
	function explodeTags($taglist, $href) {
		$tags = "";
		$taglist = array_filter(preg_split('/[,\s]+/', $taglist));
		$a = 0;
		foreach ($taglist as $tag) {
			if ($a != 0) $tags = $tags . ', ';
				$tags = $tags . '<a class="tag" href="'.$href.'?id='.$tag.'">'.$tag.'</a>';
				$a++;
		}
		return $tags; 		
	}
	
	function getPlaceInfo($get=0) { // $get = 0 means ALL places, otherwise get the ID of $get
		$db = db_connect();
		if ($get == 0) $sql = "ORDER BY name ASC"; 
		else $sql = "WHERE places.id=?";
		if ($stmt = $db->prepare("SELECT places.id,name,mon,tue,wed,thu,fri,sat,sun,notes,website,menu,phone,location,type,tags FROM places JOIN hours ON places.id = hours.id JOIN info ON places.id = info.id $sql")) { // get seasonid, put into $sid)
			if ($get != 0) $stmt->bind_param("i",$get);
			$stmt->execute();
			$meta = $stmt->result_metadata(); 
			while ($field = $meta->fetch_field()) { 
				$params[] = &$row[$field->name]; 
			} 
			call_user_func_array(array($stmt, 'bind_result'), $params); 

			$i = 0;
			$result = array();
			while ($stmt->fetch()) { 
				foreach($row as $key => $val) 
				{ 
					$c[$key] = $val; 
				} 
				$result[] = $c; 
				$i++;
			}
			$stmt->close();
			if (count($result) >= 1) {
				$day = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")), 0);
				$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');			
				for ($i = 0; $i < count($result); $i++) {
					$location = $result[$i]["location"];
					echo
"		<h3><b>".$result[$i]["name"]."</b></h3>
		<b>Type:</b> ".explodeTags($result[$i]["type"],"type")."<br>
		<b>Address:</b> <a href=\"https://www.google.com/maps?q=".str_replace(" ","+",$location)."\">$location</a><br>
		<b>Hours:</b>
";
		for ($j = 0; $j < 7; $j++) {
			$output = "&nbsp;&nbsp;$days[$j]: " . $result[$i][strtolower($days[$j])];
			if ($j == $day) {
				$output = "<b>$output</b>";
			}
			echo 
"		<br>$output
";
		}
		echo 
"		<br><b>Tags:</b> ".explodeTags($result[$i]["tags"],"tags")."<br><br>
";
				}
			}
		}		
	}
?>