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
		$stmt = $db->prepare("SELECT name FROM places WHERE name LIKE ? LIMIT 15");

		$keyword = '%'.$db->real_escape_string($keyword).'%';
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
			if (count($result) > 0) {
				$taglist = array_filter(preg_split('/[,\s]+/', $taglist));
				$a = 0;
				foreach ($taglist as $tag) {
					if ($a != 0) $tags = $tags . ', ';
					$tags = $tags . '<a class="tag" href="places?'.$href."=".$result[$tag-1]['short'].'">'.$result[$tag-1]['name'].'</a>';
					$a++;
				}
				return $tags; 
			}
		}
		else return "";
	}
	
	function getTag($tag, $from="tags") {
		$db = db_connect();
		if (strlen($tag) > 20 || ($from != "types" && $from != "tags")) return ""; // weak level of security
		if ($stmt = $db->prepare("SELECT id FROM ".$from." WHERE short = ? LIMIT 1")) {
			$stmt->bind_param("s", $tag);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
			return $id;
		}
	}
	
	
	function getPlaceInfo($get=0, $from="") { // $get=0 or no $from means ALL places, otherwise check 	$get from $from
		$db = db_connect();
		if ($from == "search" && isset($_GET['s'])) $sql = 'WHERE name LIKE ? LIMIT 30';
		else if ($from == "name") $sql = "WHERE name=?"; // show by name
		else if ($from == "tags") $sql = "WHERE find_in_set(?, cast(tags as char)) > 0"; // show by tags
		else if ($from == "types") $sql = "WHERE find_in_set(?, cast(types as char)) > 0"; // show by types, TODO WORK WITH MULTIPLE tags & types
		else if ($from == "id") $sql = "WHERE places.id = ?";  // show by id
		else { $from == ""; $sql = "ORDER BY name ASC"; } // show all
		if ($stmt = $db->prepare("SELECT places.id,name,mon,tue,wed,thu,fri,sat,sun,notes,website,menu,phone,location,types,tags FROM places JOIN hours ON places.id = hours.id JOIN info ON places.id = info.id $sql")) { // get seasonid, put into $sid)
			$get = $db->real_escape_string($get);
			if ($from == "id"){ 
				$stmt->bind_param("i",$get);
			} else if ($from == "name") {
				$get = str_replace(['_',"\'"],[' ',"'"],$get);
				$stmt->bind_param("s",$get);
			} else if ($from == "search") {
				$get = str_replace('_',' ','%'.$get.'%');
				$stmt->bind_param("s",$get);
			} else if ($from == "types" || $from == "tags") {
				$get = getTag($get,$from);
				$stmt->bind_param("i",$get);
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
			if (count($result) == 1) $from = "name"; // Show everything if there's only one result;
			if (count($result) > 0) {
				echo "		<div";
				echo ($from == "name" || $from == "id") ? ' class="data col-xs-6">' : '>';
				$day = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")), 0);
				$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
				for ($i = 0; $i < count($result); $i++) {
					$url_part = urlencode($result[$i]["name"]); // php likely has a better fix later TODO
					$title = $from == "name" ? $result[$i]["name"] : "<a class='title' href=\"place?name=$url_part\">".$result[$i]["name"]."</a>";					
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
						if (strlen($result[$i]['phone']) > 0) {
							echo 
"		<br><b>Phone:</b> ".$result[$i]['phone']."
";
						}
						if (strlen($result[$i]['website']) > 0) {
							echo 
'		<br><a href="'.$result[$i]['website'].'"><b>Website</b></a>
';
						}						
						if (strlen($result[$i]['menu']) > 0) {
							echo 
"		<br><b>Menu:</b> ".$result[$i]['menu']."
";
						}
						if (strlen($result[$i]['location']) > 5) {
							$map =  
	'		<div id="map col-xs-6"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="500" height="400" src="https://maps.google.com/maps?hl=en&q='.str_replace(" ","+",$location).'&ie=UTF8&t=roadmap&z=15&iwloc=B&output=embed"></iframe></div>
	';
						}
					}
				}
					echo 
"		</div>
";				
				if (isset($map)) echo $map;
			} else {
				echo "<b>Sorry! No results returned.</b><br>";
				getPlaceInfo(0);
			} 
		}		
	}

	// Future of maps: https://developers.google.com/maps/articles/phpsqlsearch_v3
	function getMapInfo($get=0, $from="") { // $get=0 or no $from means ALL places, otherwise check 	$get from $from
		$db = db_connect();
		$get_orig = $get;
		if ($from == "search" && isset($_GET['s'])) $sql = 'WHERE name LIKE ? LIMIT 30';
		else if ($from == "name") $sql = "WHERE name=?"; // show by name
		else if ($from == "tags") $sql = "WHERE find_in_set(?, cast(tags as char)) > 0"; // show by tags
		else if ($from == "types") $sql = "WHERE find_in_set(?, cast(types as char)) > 0"; // show by types, TODO WORK WITH MULTIPLE tags & types
		else if ($from == "id") $sql = "WHERE places.id = ?";  // show by id
		else { $from == ""; $sql = "ORDER BY name ASC"; } // show all
		if ($stmt = $db->prepare("SELECT places.id,name,location,types,tags FROM places JOIN hours ON places.id = hours.id JOIN info ON places.id = info.id $sql")) { // get seasonid, put into $sid)
			$get = $db->real_escape_string($get);
			if ($from == "id"){ 
				$stmt->bind_param("i",$get);
			} else if ($from == "name") {
				$get = str_replace(['_',"\'"],[' ',"'"],$get);
				$stmt->bind_param("s",$get);
			} else if ($from == "search") {
				$get = str_replace('_',' ','%'.$get.'%');
				$stmt->bind_param("s",$get);
			} else if ($from == "types" || $from == "tags") {
				$get = getTag($get,$from);
				$stmt->bind_param("i",$get);
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
			if (count($result) == 1) $from = "name"; // Show everything if there's only one result;
			if (count($result) >= 0) {
				$get_info = strlen($get_orig>=1) ? '<p>Looking up: <b>".$get_orig."</b></p><br>' : '';
				echo "		<br>$get_info<div id=\"map_canvas\" style=\"width:500px;height:500px;\"></div>";
				echo "
<script type=\"text/javascript\">
  var delay = 100;
  var infowindow = new google.maps.InfoWindow();
  var latlng = new google.maps.LatLng(40.0000, -83.0145);
  var mapOptions = {
    zoom: 15,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var geocoder = new google.maps.Geocoder(); 
  var map = new google.maps.Map(document.getElementById(\"map_canvas\"), mapOptions);
  var bounds = new google.maps.LatLngBounds();
  function geocodeAddress(address, next) {
    geocoder.geocode({address:address}, function (results,status)
      { 
         if (status == google.maps.GeocoderStatus.OK) {
          var p = results[0].geometry.location;
          var lat=p.lat();
          var lng=p.lng();
          createMarker(address,lat,lng);
        }
        else {
           if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            nextAddress--;
            delay++;
          } else {
                        }   
        }
        next();
      }
    );
  }
 function createMarker(add,lat,lng) {
   var contentString = add;
   var marker = new google.maps.Marker({
     position: new google.maps.LatLng(lat,lng),
     map: map,
           });

  google.maps.event.addListener(marker, 'click', function() {
     infowindow.setContent(contentString); 
     infowindow.open(map,marker);
   });

   bounds.extend(marker.position);

 }
  var locations = [
";				for ($a = 0; $a < count($result); $a++) { 
					$loc = $result[$a]["location"]; 
					if (strlen($loc >5)) { 
						if ($a != 0) echo ", "; 
						echo "'".$result[$a]["location"]."'"; 
					} 
				} 
				echo "
  ];
  var nextAddress = 0;
  function theNext() {
	if (locations.length > 0) {
      if (nextAddress < locations.length) {
        setTimeout('geocodeAddress(\"'+locations[nextAddress]+'\",theNext)', delay);
        nextAddress++;
      } else {
        map.fitBounds(bounds);
      }
	}
  }
  theNext();
</script>";
			} else {
				echo "<b>Sorry! No results returned.</b><br>";
				getMapInfo(0);
			} 
		}		
	}	
?>