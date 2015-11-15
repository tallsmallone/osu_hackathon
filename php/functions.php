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

	function searchForKeyword($keyword) {
		$db = db_connect();
		$stmt = $db->prepare("SELECT name FROM places WHERE name LIKE ?");

		$keyword = $keyword . '%';
		$stmt->bind_param('s', $keyword);

		$results = array();
		$stmt->execute();
		$stmt->bind_result($result);

		$out = array();
		while($stmt->fetch()) {
			array_push($out,$result);
		}
	

		$db->close();
		return $out;
	}
	
	function explodeTags($taglist, $href="tags") { // TODO: redorder based on order column
		$tags = "";
		$db = db_connect();
		if (($href == "tags" || $href == "types") && $stmt = $db->prepare("SELECT `id`,`name`,`short`,`order` FROM ".$href." ORDER BY id ASC")) { // get seasonid, put into $sid)
			$stmt->execute();
			$meta = $stmt->result_metadata(); 
			while ($field = $meta->fetch_field()) { 
				$params[] = &$row[$field->name]; 
			} 
			call_user_func_array(array($stmt, 'bind_result'), $params); 
			$result = array();
			while ($stmt->fetch()) { 				
				foreach($row as $key => $val) 
					$c[$key] = $val; 
				$result[] = $c;	
			}
			$stmt->close();
			$a = 0;
			if (count($result) > 0) {
				$taglist = array_filter(preg_split('/[,\s]+/', $taglist));
				foreach ($taglist as $tag) {
					if ($a != 0) $tags = $tags . ', ';
					$tags = $tags . '<a class="tag" href="places?'.$href."=".$result[$a]['short'].'">'.$result[$a]['name'].'</a>';
					$a++;
				}
				return $tags; 
			}
		}
		else return "";
	}
	
	function getPlaceInfo($get=0, $from="") { // $get=0 or no $from means ALL places, otherwise check $get from $from
		$db = db_connect();
		if ($from == "name") $sql = "WHERE name=?"; // show by name
		else if ($from == "tags") $sql = "WHERE find_in_set(?, cast(tags as char)) > 0"; // show by tags
		else if ($from == "types") $sql = "WHERE find_in_set(?, cast(types as char)) > 0"; // show by types, TODO WORK WITH MULTIPLE tags & types
		else if ($from == "id") $sql = "WHERE places.id = ?";  // show by id
		else { $from == ""; $sql = "ORDER BY name ASC"; } // show all
		if ($stmt = $db->prepare("SELECT places.id,name,mon,tue,wed,thu,fri,sat,sun,notes,website,menu,phone,location,types,tags FROM places JOIN hours ON places.id = hours.id JOIN info ON places.id = info.id $sql")) { // get seasonid, put into $sid)
			if ($from == "id"){ 
				$stmt->bind_param("i",$get);
			} else if ($from == "name") {
				$get = str_replace('_',' ',htmlspecialchars($get));
				$stmt->bind_param("s",$get);
			} else if (preg_match('/^\d(?:,\d)*$/',$get) && ($from == "types" || $from == "tags")) {
				$stmt->bind_param("s",htmlspecialchars($get));
			}
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
					$c[$key] = $val; 
				$result[] = $c; 
				$i++;
			}
			$stmt->close();
			if (count($result) > 0) {
				$day = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")), 0);
				$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
				for ($i = 0; $i < count($result); $i++) {
					$url_part = str_replace(' ','_',$result[$i]["name"]);
					$title = $get != 0 ? $result[$i]["name"] : "<a class='title' href='place?name=$url_part'>".$result[$i]["name"]."</a>";					
					$location = $result[$i]["location"];
					echo
"		<h3><b>$title</b></h3>
		<b>Types:</b> ".explodeTags($result[$i]["types"],"types")."<br>
		<b>Address:</b> <a href=\"https://www.google.com/maps?q=".str_replace(" ","+",$location)."\">$location</a><br>
		<b>Hours:</b>
";
					if ($from == "name" || $from == "id") {
						for ($j = 0; $j < 7; $j++) {
							$output = "&nbsp;&nbsp;$days[$j]: " . $result[$i][strtolower($days[$j])];
							if ($j == $day) {
								$output = "<b>$output</b>";
							}
							echo 
"		<br>$output
";
						}
						if (strlen($result[$i]['notes']) > 0) {
							echo 
"		<br>&nbsp;&nbsp;Note: ".$result[$i]['notes']."
";
						}
					} else {
						echo
"		<br>&nbsp;&nbsp;<b>$days[$day]:</b> ".$result[$i][strtolower($days[$day])]."
";
					}
					echo 
"		<br><b>Tags:</b> ".explodeTags($result[$i]["tags"],"tags")."
";				
					if ($from == "name" || $from == "id") {
						if (strlen($result[$i]['website']) > 0) {
							echo 
"		<br><b>Website:</b> ".$result[$i]['website']."
";
						}
						if (strlen($result[$i]['menu']) > 0) {
							echo 
"		<br><b>Menu:</b> ".$result[$i]['menu']."
";
						}
						if (strlen($result[$i]['phone']) > 0) {
							echo 
"		<br><b>Phone:</b> ".$result[$i]['phone']."
";
						}
					}
				}
			} else {
				echo "<b>Sorry! No results returned. Try again or go <a href=\"places\">here</a> for a full listing.</b>";
			}
		}		
	}
?>