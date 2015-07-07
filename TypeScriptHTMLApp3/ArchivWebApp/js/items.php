<?php
		
		
			
		
		
		
			echo '<div>';
			echo '<ul style="list-style-type:none;margin-left:0px;">';
			echo("<script>console.log('PHP: Start');</script>");
			$server = 'localhost';
			$user = 'root';
			$pass = 'ygrene';
			$dbname = 'energy13';
			$con = mysql_connect($server, $user, $pass) or die("Can't connect");
			mysql_select_db($dbname);
			if(!$con) {
				echo("<script>console.log('PHP: SQL Verbindung Fehlgeschlagen');</script>");
  				exit("Verbindungsfehler: ".mysql_connect_error());
			}	else {
				echo("<script>console.log('PHP: SQL Verbindung Erfolgreich');</script>");			
			}	
			$dynamicList = "";
			$sql = mysql_query("SELECT * FROM module");
			$productCount = mysql_num_rows($sql); 
			while($row = mysql_fetch_array($sql)) {
				$id = $row['id'];
				echo("<script>console.log('PHP: Module Loop (".$id.")');</script>");
   				$apids = mysql_query("SELECT id FROM views WHERE view_name = 'appliance'");
   				$apid = 0;
   				while($rows = mysql_fetch_array($apids)) {
   					echo("<script>console.log('PHP: Loop 2');</script>");
   					$apid = $rows["id"];
   					echo("<script>console.log('PHP: View ".$apid." is appliance');</script>");
				}
   				$sqls = mysql_query("SELECT * FROM tag_value WHERE module_id = '$id' AND view_id = '$apid'");
   				$num_rows = mysql_num_rows($sqls);
   				$name = "";
   				if($num_rows > 0) {
   					while($row = mysql_fetch_array($sqls)) {
   						echo("<script>console.log('PHP: Loop 3');</script>");
        				$name = $row["tag_value"];
					}
				}
				$sqlss = mysql_query("SELECT watt FROM live_log WHERE module_id = '$id'");
   				$num_rowss = mysql_num_rows($sqlss);
   				$watt = 0.0;
   				if($num_rowss > 0) {
   					while($rowss = mysql_fetch_array($sqlss)) {
   						echo("<script>console.log('PHP: Watt');</script>");
        				$watt = $rowss["watt"];
					}
				}

				date_default_timezone_set('Europe/Berlin');
				$date = date('Y-m-d H:i:s', strtotime('this week tuesday midnight'));
				echo("<script>console.log('PHP: $date');</script>");
				$sqlsss = mysql_query("SELECT kwh FROM history_log WHERE hourvalue >= '$date' module_id = '$id'");
   				$num_rowsss = mysql_num_rows($sqlsss);
   				$wattweek = 0.0;
   				if($num_rowsss > 0) {
   					while($rowsss = mysql_fetch_array($sqlsss)) {
   						echo("<script>console.log('PHP: WattWeek');</script>");
        				$wattweek .= $rowsss["kwh"];
					}
				}


				
				
				
				$dynamicList .= '<li style="margin-left: -2.5em;padding: 0.4em 0.6em 0.4em;border-bottom: 1px solid #C9C9C9;">
				
	
				<div style="width:100%">
					
					<div style="float:left;">
						<img src="img/contact.png" height="40" width="40" style="vertical-align: middle;padding-right:0.4em;"/>
					</div>

					<div style="float:left;">
						<p style="margin-bottom:0em;margin-top:0.11em;font-size:1.0em"><b>'. $name .'</b></p>
						<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em">Woche: '.$wattweek.' kWh (80%)</p>
					</div>
					
					<div style="float:right;padding:0.5em;min-height: 100%;height:100%;">
						<p style="margin:0;color: #33B5E5; font-size:1.2em" >'.$watt.' W</p>
					</div>
	
				</div>

				<div style="clear:left"></div>				
								 
				</li>';
				
			}
			echo $dynamicList;	
			mysql_close($db);
			echo '</ul>';
			echo '</div>';
			
		?>
