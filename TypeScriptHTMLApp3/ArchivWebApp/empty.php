<?php
		
		
			echo("<script>console.log('PHP Details');</script>");
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
			}	
			$dynamicList = "";
			
			$sqlTimeLastHistoryValues = mysql_query("SELECT hourvalue FROM history_log WHERE hourvalue = (SELECT MAX(hourvalue) FROM history_log)"); 
			$timeOfLastHistoryValue = "";
			while($rowLastHistory = mysql_fetch_array($sqlTimeLastHistoryValues)) {
				$timeOfLastHistoryValue = $rowLastHistory["hourvalue"];
			}		
			
			$sqlCountLastHistoryValues = mysql_query("SELECT count(*) FROM history_log WHERE hourvalue = (SELECT MAX(hourvalue) FROM history_log)");
			$countOfLastHistoryValue = "";
			while($rowLastHistoryCount = mysql_fetch_array($sqlCountLastHistoryValues)) {
				$countOfLastHistoryValue = $rowLastHistoryCount["count(*)"];
			}
		
			date_default_timezone_set('Europe/Berlin');
			$currentTimeBerlinInt = date('Y-m-d H:i:s', strtotime('first day of this month midnight'));
			$currentOld = date('Y-m-d H:i:s', strtotime('first day of last month midnight'));
			$sqlLastMonth = mysql_query("SELECT kwh FROM history_log WHERE hourvalue < '$currentTimeBerlinInt' AND hourvalue >= '$currentOld' ");
			$lastMonth = "";
			while($rowLastMonth = mysql_fetch_array($sqlLastMonth)) {
				$lastMonth =  $lastMonth + $rowLastMonth["kwh"];
			}
		
			$sqlThisMonth = mysql_query("SELECT kwh FROM history_log WHERE hourvalue >= '$currentTimeBerlinInt'");
			$thisMonth = "";
			while($rowThisMonth = mysql_fetch_array($sqlThisMonth)) {
				$thisMonth =  $thisMonth + $rowThisMonth["kwh"];
			}
		
			$dynamicList = '<div>
								<p>Last History Log: '.$timeOfLastHistoryValue.'</p>
								<p>Module: '.$countOfLastHistoryValue.'</p>
								<p>Last Month: '.$lastMonth.' kWh</p>
								<p>This Month: '.$thisMonth.' kWh</p>
							</div>';
			
			echo $dynamicList;	
			mysql_close($db);
			
		?>
