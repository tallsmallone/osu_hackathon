<?php
	require_once("frames/head.php"); 
	require_once("php/functions.php");
	$id = isset($_GET['id']) && is_numeric($_GET['id']) ? htmlspecialchars($_GET['id']) : 0;
	if ($id == 0) {
		include_once("places.php");
		exit;
	} else {
		$db = db_connect();
		if ($stmt = $db->prepare("SELECT places.id,name,mon,tue,wed,thu,fri,sat,sun,notes,website,menu,phone,location,type,tags FROM places JOIN hours ON places.id = hours.id JOIN info ON places.id = info.id WHERE places.id=?")) { // get seasonid, put into $sid)
			$stmt->bind_param("i",$id);
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
?>
		<div class="input-group input-group-lg" style="width:50%">
	   		<input type="text" class="form-control" placeholder="Where to Eat" aria-describedby="sizing-addon1">
	   	</div>
<?php
				$day = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")), 0);
				$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');			
				for ($i = 0; $i < count($result); $i++) {
?>
	<div>
<?php echo 
"		<h3><b>".$result[$i]["name"]."</b></h3>
		<b>Type:</b> ".$result[$i]["type"]."<br>
		<b>Address:</b> ".$result[$i]["location"]."<br>
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
"		<br><b>Tags:</b> ".$result[$i]["tags"]."<br>
		<br>
";?>
	</div>
<?php
				}
			}
		}
	}
	//print_r($result); 
  	require_once("frames/footer.php");
?>