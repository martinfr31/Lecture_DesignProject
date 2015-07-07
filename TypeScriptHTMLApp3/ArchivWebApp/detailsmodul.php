<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<style>
	section
{
	float:left;
	width:30%;
	margin:20px 20px;
}


input[type=checkbox] {
	visibility: hidden;
}

.flatRoundedCheckbox
{
    width: 260px;
    height: 30px;
    margin: 10px 10px;
    position: relative;
}
.flatRoundedCheckbox div
{
    width: 100%;
    height:100%;
    background: #d3d3d3;
    border-radius: 50px;
    position: relative;
    top:-30px;
}
.flatRoundedCheckbox label
{
    display: block;
    width: 35px;
    height: 25px;
    border-radius: 50px;

    -webkit-transition: all .5s ease;
    -moz-transition: all .5s ease;
    -o-transition: all .5s ease;
    -ms-transition: all .5s ease;
    transition: all .5s ease;
    cursor: pointer;
    position: absolute;
    top: -7.5px;
    z-index: 1;
    left: 8px;
    background: #FFF;
}

.flatRoundedCheckbox input[type=checkbox]:checked ~ div
{
    background: #33B5E5;
}

.flatRoundedCheckbox input[type=checkbox]:checked ~ label {
    left: 195px;
}


	</style>

	<script>
		function handleClick(cb, mac) {
        	$.post('http://'+location.hostname+':8080/EnergyWebServiceClient/sampleEnergyServiceProxy/Result.jsp?method=17&mac20=' + mac);

        	var currentdate = new Date();
        	var d = currentdate.getDate();
    		var m = currentdate.getMonth() + 1;
    		var y = currentdate.getFullYear();
    		var h = currentdate.getHours();
    		var mm = currentdate.getMinutes();
    		var s = currentdate.getSeconds();
    		var datetime = '' + (d <= 9 ? '0' + d : d)+'.'+(m<=9 ? '0' + m : m)+'.'+y+' '+ (h <= 9 ? '0' + h : h)+':'+(mm<=9 ? '0' + mm : mm)+':'+(s<=9 ? '0' + s : s);

        	$.post('http://'+location.host+'/write.php?text="---- '+datetime+' WebApp: Switch Appliance"');

			$.mobile.loading('show');
    		setTimeout(function(){
        		$.mobile.loading('hide');
    		}, 5000);
    	}
		</script>

    <script>
		function handleClickUL(cb, mac) {

          arrayyy = mac.split('~');
          var acctiv = arrayyy[arrayyy.length-1];

          if(acctiv == 0) {
            var retVal = confirm("Wirklich Sparmodus aktivieren?");
          } else {
            var retVal = confirm("Wirklich Sparmodus deaktivieren?");
          }

          if (retVal == true) {
            console.log("User wants to continue!");
            if(acctiv == 0) {
          for(i=0;i<arrayyy.length-1;i++){
            var flub = arrayyy[i];
            if(arrayyy[i+1]==1) {
              partA(flub, i);
            }
            i++;
          }
        } else {
          for(i=0;i<arrayyy.length-1;i++){
            var flub = arrayyy[i];
            if(arrayyy[i+1]==0) {
              partA(flub, i);
            }
            i++;
          }
        }
        $.mobile.loading('show');
    		    setTimeout(function(){
        		  $.mobile.loading('hide');
    		      }, 20000);
	          return true;
          } else {
            console.log("User does not want to continue!");
	          return false;
          }




        var currentdate = new Date();
        var d = currentdate.getDate();
    		var m = currentdate.getMonth() + 1;
    		var y = currentdate.getFullYear();
    		var h = currentdate.getHours();
    		var mm = currentdate.getMinutes();
    		var s = currentdate.getSeconds();
    		var datetime = '' + (d <= 9 ? '0' + d : d)+'.'+(m<=9 ? '0' + m : m)+'.'+y+' '+ (h <= 9 ? '0' + h : h)+':'+(mm<=9 ? '0' + mm : mm)+':'+(s<=9 ? '0' + s : s);

        	$.post('http://'+location.host+'/write.php?text="---- '+datetime+' WebApp: Switch Appliance (Urlaub)"');



}

function partA(mac, i) {
            setTimeout(function() {partB(mac);},i*2500);
}

function partB(mac) {
            $.post('http://'+location.hostname+':8080/EnergyWebServiceClient/sampleEnergyServiceProxy/Result.jsp?method=17&mac20=' + mac);
            console.log("Mac Click: " +mac);
}


		</script>

    </head>
	<body>



		<?php
			$var1 = $_GET['id'];
			if($var1 == "999999") {

			$server = 'localhost';
			$user = 'root';
			$pass = 'solidrun';
			$dbname = 'energy13';
			$con = mysql_connect($server, $user, $pass) or die("Can't connect");
			mysql_select_db($dbname);
			if(!$con) {
  				exit("Verbindungsfehler: ".mysql_connect_error());
			}	else {
			}



			$smartMeterDa = false;

			date_default_timezone_set('Europe/Berlin');
			$currentTime = round(microtime(true) * 1000);

			$sqlSmart = mysql_query("SELECT wirkleistung,time FROM smart_meter_values WHERE time = (SELECT max(time) from smart_meter_values)");
			$smartRowCount = mysql_num_rows($sqlSmart);
			$wattoverallsmart = 0;
			if($smartRowCount>=1) {
				while($rowdd = mysql_fetch_array($sqlSmart)) {
					$millisecondss = 1000 * strtotime($rowdd["time"]);
        			$diffs = $currentTime-$millisecondss;
        			if($diffs<600000) {
        					$wattoverallsmart = $rowdd["wirkleistung"];
							$smartMeterDa = true;
        			}
				}
			}













			if($smartMeterDa) {

			date_default_timezone_set('Europe/Berlin');
			$currentTimeBerlinInt = date('Y-m-d H:i:s', strtotime('first day of this month midnight'));
			$currentOld = date('Y-m-d H:i:s', strtotime('first day of last month midnight'));
			$sqlLastMonth = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time < '$currentTimeBerlinInt' AND time >= '$currentOld' ");
			$lastMonth = "";
			while($rowLastMonth = mysql_fetch_array($sqlLastMonth)) {
				$lastMonth =  $lastMonth + $rowLastMonth["verbrauch"];
			}

			$sqlThisMonth = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$currentTimeBerlinInt'");
			$thisMonth = "";
			while($rowThisMonth = mysql_fetch_array($sqlThisMonth)) {
				$thisMonth =  $thisMonth + $rowThisMonth["verbrauch"];
			}

				$thisMonth = number_format($thisMonth,2,",",".");
				$lastMonth = number_format($lastMonth,2,",",".");


			$headline = '<p style="width:100%; color: #33B5E5; font-size:14pt;" align="center">Gesamtverbrauch</p>';
			echo $headline;
			echo '<p style="width:100%; color: #33B5E5; font-size:11pt;">Informationen</p>';
			$dynamicList = '<ul style="margin: 0;">
						<li>Aktueller Monat: '. $thisMonth .'kWh</li>
						<li>Letzter Monat: '. $lastMonth .'kWh</li>
						</ul>';
			echo $dynamicList;


			date_default_timezone_set('Europe/Berlin');
				$date = date("Y-m-d H:i:s");
				$ts = strtotime($date);
				$year = date('o', $ts);
				$week = date('W', $ts);
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$date = date("Y-m-d H:i:s", $ts);
				$sqlsss = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$date'");
   				$num_rowsss = mysql_num_rows($sqlsss);
   				$wattweek = 0.0;
   				if($num_rowsss > 0) {
   					while($rowsss = mysql_fetch_array($sqlsss)) {
        				$wattweek += $rowsss["verbrauch"];
					}
				}



				$dates = date('Y-m-d H:i:s', strtotime('last week midnight'));
				$dates = date("Y-m-d H:i:s");
				$ts = strtotime($date);
				$year = date('o', $ts);
				$week = date('W', $ts-1);
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$dates = date("Y-m-d H:i:s", $ts);
				$sqlssss = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$dates' AND time < '$date'");
   				$num_rowssss = mysql_num_rows($sqlssss);
   				$wattweeks = 0.0;
   				if($num_rowssss > 0) {
   					while($rowssss = mysql_fetch_array($sqlssss)) {
        				$wattweeks += $rowssss["verbrauch"];
					}
				}
				$ts = strtotime($dates);
				$year = date('o', $ts);
				$week = date('W', $ts-2);
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesd = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$datesd' AND time < '$dates'");
   				$num_rowsssss = mysql_num_rows($sqlsssss);
   				$wattweeksss = 0.0;
   				if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweeksss += $num_rowsssss["verbrauch"];
					}
				}

        $ts = strtotime($datesd);
				$year = date('o', $ts);
				$week = date('W', $ts-3);
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesdd = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$datesdd' AND time < '$datesd'");
   				$num_rowsssss = mysql_num_rows($sqlsssss);
   				$wattweekssss = 0.0;
   				if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweekssss += $num_rowsssss["verbrauch"];
					}
				}

        $ts = strtotime($datesdd);
				$year = date('o', $ts);
				$week = date('W', $ts-4);
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesdds = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT (max(wirkarbeittot)-min(wirkarbeittot)) AS verbrauch from energy13.smart_meter_values WHERE time >= '$datesdds' AND time < '$datesdd'");
   				$num_rowsssss = mysql_num_rows($sqlsssss);
   				$wattweekssssd = 0.0;
   				if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweekssssd += $num_rowsssss["verbrauch"];
					}
				}

			  $wattweek = number_format($wattweek,3,",",".");
				$wattweeks = number_format($wattweeks,3,",",".");
				$wattweeksss  = number_format($wattweeksss,3,",",".");
				$wattweeksss  = str_replace(',', '.', $wattweeksss);
				$wattweek = str_replace(',', '.', $wattweek);
				$wattweeks = str_replace(',', '.', $wattweeks);



			$graph = '<script src="/js/Chart.min.js"></script>
    	<script>
    		var lineChartData = {
 				labels : ["","","","",""],
 				datasets : [{
 					fillColor : ["rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,249,1)"],
 					pointColor : "rgba(51,181,229,1)",
 					pointStrokeColor : "#fff",
 					data : ['.$wattweekssssd.','.$wattweekssss.','.$wattweeksss.','.$wattweeks.','.$wattweek.',0]
 				}]
 			};
 			new Chart(document.getElementById("line").getContext("2d")).Bar(lineChartData, {
    			scaleShowLabels : true,
 				caleShowGridLines : true,
 				animation : false,
 				scaleFontSize : 9,
 				barValueSpacing : 0,
 				barShowStroke : false,
 				scaleOverlay : true,
 				barDatasetSpacing : 0
			});
    	</script>';
			echo '<p style="width:100%; color: #33B5E5; font-size:11pt;">Wochenvergleich in Kilowattstunden</p>';
			echo $graph;
			mysql_close($db);




			} else {

			//date_default_timezone_set('Europe/Berlin');
			//$currentTimeBerlinInt = date('Y-m-d H:i:s', strtotime('first day of this month midnight'));
			//$currentOld = date('Y-m-d H:i:s', strtotime('first day of last month midnight'));
			//$sqlLastMonth = mysql_query("SELECT kwh FROM history_log WHERE hourvalue < '$currentTimeBerlinInt' AND hourvalue >= '$currentOld' ");
			//$lastMonth = "";
			//while($rowLastMonth = mysql_fetch_array($sqlLastMonth)) {
			//	$lastMonth =  $lastMonth + $rowLastMonth["kwh"];
			//}
			//
			//$sqlThisMonth = mysql_query("SELECT kwh FROM history_log WHERE hourvalue >= '$currentTimeBerlinInt'");
			//$thisMonth = "";
			//while($rowThisMonth = mysql_fetch_array($sqlThisMonth)) {
			//	$thisMonth =  $thisMonth + $rowThisMonth["kwh"];
			//}
			//
			//	$thisMonth = number_format($thisMonth,2,",",".");
			//	$lastMonth = number_format($lastMonth,2,",",".");
			//
			//
			//$headline = '<p style="width:100%; color: #33B5E5; font-size:14pt;" align="center">Gesamtverbrauch</p>';
			//echo $headline;
			//echo '<p style="width:100%; color: #33B5E5; font-size:11pt;">Informationen</p>';
			//$dynamicList = '<ul style="margin: 0;">
			//			<li>Aktueller Monat: '. $thisMonth .'kWh</li>
			//			<li>Letzter Monat: '. $lastMonth .'kWh</li>
			//			</ul>';
			echo $dynamicList;












      $sqlUrlaub = mysql_query("SELECT mac, isOn FROM module WHERE urlaubsmodus >= '1'");
			$aktiv = '1';
      $cart = array();
			while($rowThisMonth = mysql_fetch_array($sqlUrlaub)) {
        $cart[] = $rowThisMonth["mac"];
        $cart[] = $rowThisMonth["isOn"];
        if($rowThisMonth["isOn"] == '1') {
          $aktiv = '0';
        }
			}
      $cart[] = $aktiv;
      $b = implode("~",$cart);

      echo '<p style="width:100%; color: #33B5E5; font-size:11pt; margin: 0; padding: 5;">Sparmodus</p>';
		$checkbox = '';
		if($aktiv == '0') {
			$checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.2; padding: 0;">
            <input type="checkbox" onclick="handleClickUL(this,\''.$b.'\')";  value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:5px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
		} else {
		  $checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.2; padding: 0;">
            <input type="checkbox" onclick="handleClickUL(this,\''.$b.'\')";  checked="false" value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:5px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
		}
		echo $checkbox;











//			date_default_timezone_set('Europe/Berlin');
//				$date = date("Y-m-d H:i:s");
//				$ts = strtotime($date);
//				$year = date('o', $ts);
//				$week = date('W', $ts);
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$date = date("Y-m-d H:i:s", $ts);
//				$sqlsss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$date'");
//   				$num_rowsss = mysql_num_rows($sqlsss);
//   				$wattweek = 0.0;
//   				if($num_rowsss > 0) {
//   					while($rowsss = mysql_fetch_array($sqlsss)) {
//        				$wattweek += $rowsss["sum(kwh)"];
//					}
//				}
//
//
//
//				$dates = date('Y-m-d H:i:s', strtotime('last week midnight'));
//				$dates = date("Y-m-d H:i:s");
//				$ts = strtotime($date);
//				$year = date('o', $ts);
//				$week = date('W', $ts-1);
//				$weeksave = date('W', $ts);
//				if($weeksave == 1) {
//					$year = date('o', $ts-1);
//				}
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$dates = date("Y-m-d H:i:s", $ts);
//				$sqlssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$dates' AND hourvalue < '$date'");
//   				$num_rowssss = mysql_num_rows($sqlssss);
//   				$wattweeks = 0.0;
//   				if($num_rowssss > 0) {
//   					while($rowssss = mysql_fetch_array($sqlssss)) {
//        				$wattweeks += $rowssss["sum(kwh)"];
//					}
//				}
//				$ts = strtotime($dates);
//				$year = date('o', $ts);
//				$week = date('W', $ts-2);
//				$weeksave = date('W', $ts);
//				if($weeksave == 1 || $weeksave == 2) {
//					$year = date('o', $ts-1);
//				}
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$datesd = date("Y-m-d H:i:s", $ts);
//				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesd' AND hourvalue < '$dates'");
//   				$num_rowsssss = mysql_num_rows($sqlsssss);
//   				$wattweeksss = 0.0;
//   				if($num_rowsssss > 0) {
//   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
//        				$wattweeksss += $num_rowsssss["sum(kwh)"];
//					}
//				}
//
//        $ts = strtotime($datesd);
//				$year = date('o', $ts);
//				$week = date('W', $ts-3);
//				$weeksave = date('W', $ts);
//				if($weeksave == 1 || $weeksave == 2 || $weeksave == 3) {
//					$year = date('o', $ts-1);
//				}
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$datesdd = date("Y-m-d H:i:s", $ts);
//				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesdd' AND hourvalue < '$datesd'");
//   				$num_rowsssss = mysql_num_rows($sqlsssss);
//   				$wattweekssss = 0.0;
//   				if($num_rowsssss > 0) {
//   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
//        				$wattweekssss += $num_rowsssss["sum(kwh)"];
//					}
//				}
//
//        $ts = strtotime($datesdd);
//				$year = date('o', $ts);
//				$week = date('W', $ts-4);
//				$weeksave = date('W', $ts);
//				if($weeksave == 1 || $weeksave == 2 || $weeksave == 3|| $weeksave == 4) {
//					$year = date('o', $ts-1);
//				}
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$datesdds = date("Y-m-d H:i:s", $ts);
//				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesdds' AND hourvalue < '$datesdd'");
//   				$num_rowsssss = mysql_num_rows($sqlsssss);
//   				$wattweekssssd = 0.0;
//   				if($num_rowsssss > 0) {
//   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
//        				$wattweekssssd += $num_rowsssss["sum(kwh)"];
//					}
//				}
//


















			$wattweek = number_format($wattweek,3,",",".");
				$wattweeks = number_format($wattweeks,3,",",".");
				$wattweeksss  = number_format($wattweeksss,3,",",".");
				$wattweeksss  = str_replace(',', '.', $wattweeksss);
				$wattweekssss  = number_format($wattweekssss,3,",",".");
				$wattweekssss  = str_replace(',', '.', $wattweekssss);
				$wattweekssssd  = number_format($wattweekssssd,3,",",".");
				$wattweekssssd  = str_replace(',', '.', $wattweekssssd);
				$wattweek = str_replace(',', '.', $wattweek);
				$wattweeks = str_replace(',', '.', $wattweeks);



			$graph = '<script src="/js/Chart.min.js"></script>
    	<script>
    		var lineChartData = {
 				labels : ["","","","",""],
 				datasets : [{
 					fillColor : ["rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,249,1)"],
 					pointColor : "rgba(51,181,229,1)",
 					pointStrokeColor : "#fff",
 					data : ['.$wattweekssssd.','.$wattweekssss.','.$wattweeksss.','.$wattweeks.','.$wattweek.',0]
 				}]
 			};
 			new Chart(document.getElementById("line").getContext("2d")).Bar(lineChartData, {
    			scaleShowLabels : true,
 				caleShowGridLines : true,
 				animation : false,
 				scaleFontSize : 9,
 				barValueSpacing : 0,
 				barShowStroke : false,
 				scaleOverlay : true,
 				barDatasetSpacing : 0
			});
    	</script>';
			echo '<p style="width:100%; color: #33B5E5; font-size:11pt;">Wochenvergleich in Kilowattstunden</p>';
			echo $graph;
			mysql_close($db);

			}





			} else {








			$server = 'localhost';
			$user = 'root';
			$pass = 'solidrun';
			$dbname = 'energy13';
			$con = mysql_connect($server, $user, $pass) or die("Can't connect");
			mysql_select_db($dbname);
			if(!$con) {
  				exit("Verbindungsfehler: ".mysql_connect_error());
			}	else {
			}
			$dynamicList = "";

			$apids = mysql_query("SELECT id FROM views WHERE view_name = 'appliance'");
   			$apid = 0;
   			while($rows = mysql_fetch_array($apids)) {
   				$apid = $rows["id"];
			}
   			$sqls = mysql_query("SELECT * FROM tag_value WHERE module_id = '$var1' AND view_id = '$apid'");
   			$num_rows = mysql_num_rows($sqls);
   			$name = "";
   			if($num_rows > 0) {
   				while($row = mysql_fetch_array($sqls)) {
        			$name = $row["tag_value"];
				}
			}

			$apids = mysql_query("SELECT id FROM views WHERE view_name = 'room'");
   			$apid = 0;
   			while($rows = mysql_fetch_array($apids)) {
   				$apid = $rows["id"];
			}



			$apids = mysql_query("SELECT isOn FROM module WHERE id = '$var1'");
   			$isOn = 0;
   			while($rows = mysql_fetch_array($apids)) {
   				$isOn = $rows["isOn"];
			}

			$apids = mysql_query("SELECT switchable FROM module WHERE id = '$var1'");
   			$switchable = 0;
   			while($rows = mysql_fetch_array($apids)) {
   				$switchable = $rows["switchable"];
			}


   			$sqls = mysql_query("SELECT * FROM tag_value WHERE module_id = '$var1' AND view_id = '$apid'");
   			$num_rows = mysql_num_rows($sqls);
   			$room = "";
   			if($num_rows > 0) {
   				while($row = mysql_fetch_array($sqls)) {
        			$room = $row["tag_value"];
				}
			}
//
//			$sqls = mysql_query("SELECT sum(kwh) FROM history_log WHERE module_id = '$var1'");
//   			$num_rows = mysql_num_rows($sqls);
//   			$gesamt = 0;
//   			if($num_rows > 0) {
//   				while($row = mysql_fetch_array($sqls)) {
//        			$gesamt = $row["sum(kwh)"];
//				}
//			}
//
//			$sqls = mysql_query("SELECT min(hourvalue) FROM history_log WHERE module_id = '$var1'");
//   			$num_rows = mysql_num_rows($sqls);
//   			$mintime = "";
//   			if($num_rows > 0) {
//   				while($row = mysql_fetch_array($sqls)) {
//        			$mintime = $row["min(hourvalue)"];
//				}
//			}
//
//			date_default_timezone_set('Europe/Berlin');
//				$date = date("Y-m-d H:i:s");
//				$ts = strtotime($date);
//				$year = date('o', $ts);
//				$week = date('W', $ts);
//				$i = 1;
//				$ts = strtotime($year.'W'.$week.$i);
//				$date = date("Y-m-d H:i:s", $ts);
//				$sqlsss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$date' AND module_id = '$var1'");
//   				$num_rowsss = mysql_num_rows($sqlsss);
//   				$wattweek = 0.0;
//   				if($num_rowsss > 0) {
//   					while($rowsss = mysql_fetch_array($sqlsss)) {
//        				$wattweek += $rowsss["sum(kwh)"];
//					}
//				}
//
//














				$dates = date('Y-m-d H:i:s', strtotime('last week midnight'));
				$dates = date("Y-m-d H:i:s");
				$ts = strtotime($date);
				$year = date('o', $ts);
				$week = date('W', $ts-1);

				$weeksave = date('W', $ts);
				if($weeksave == 1) {
					$year = date('o', $ts-1);
				}
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$dates = date("Y-m-d H:i:s", $ts);
				$sqlssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$dates' AND hourvalue < '$date' AND module_id = '$var1'");
   				$num_rowssss = mysql_num_rows($sqlssss);
   				$wattweeks = 0.0;
   				if($num_rowssss > 0) {
   					while($rowssss = mysql_fetch_array($sqlssss)) {
        				$wattweeks += $rowssss["sum(kwh)"];
					}
				}

				$ts = strtotime($dates);
				$year = date('o', $ts);
				$week = date('W', $ts-2);
				$weeksave = date('W', $ts);
				if($weeksave == 1 || $weeksave == 2) {
					$year = date('o', $ts-1);
				}
				$i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesd = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesd' AND hourvalue < '$dates' AND module_id = '$var1'");
   			$num_rowsssss = mysql_num_rows($sqlsssss);
   			$wattweeksss = 0.0;
   			if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweeksss += $num_rowsssss["sum(kwh)"];
					}
				}





        $ts = strtotime($datesd);
				$year = date('o', $ts);
				$week = date('W', $ts-3);
				$weeksave = date('W', $ts);
				if($weeksave == 1 || $weeksave == 2|| $weeksave == 3) {
					$year = date('o', $ts-1);
				}
        $i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesdd = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesdd' AND hourvalue < '$datesd' AND module_id = '$var1'");
   			$num_rowsssss = mysql_num_rows($sqlsssss);
   			$wattweekssss = 0.0;
   			if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweekssss += $num_rowsssss["sum(kwh)"];
					}
				}

        $ts = strtotime($datesdd);
				$year = date('o', $ts);
				$week = date('W', $ts-4);
				$weeksave = date('W', $ts);
				if($weeksave == 1 || $weeksave == 2 || $weeksave == 3 || $weeksave == 4) {
					$year = date('o', $ts-1);
				}
        $i = 1;
				$ts = strtotime($year.'W'.$week.$i);
				$datesdds = date("Y-m-d H:i:s", $ts);
				$sqlsssss = mysql_query("SELECT sum(kwh) FROM history_log WHERE hourvalue >= '$datesdds' AND hourvalue < '$datesdd' AND module_id = '$var1'");
   			$num_rowsssss = mysql_num_rows($sqlsssss);
   			$wattweekssssd = 0.0;
   			if($num_rowsssss > 0) {
   					while($num_rowsssss = mysql_fetch_array($sqlsssss)) {
        				$wattweekssssd += $num_rowsssss["sum(kwh)"];
					}
				}


        echo "<script>console.log( 'Woche 5: " . $wattweekssssd . "' );</script>";
      echo "<script>console.log( 'Woche 4: " . $wattweekssss . "' );</script>";
      echo "<script>console.log( 'Woche 3: " . $wattweeksss . "' );</script>";
      echo "<script>console.log( 'Woche 2: " . $wattweeks . "' );</script>";
      echo "<script>console.log( 'Woche 1: " . $wattweek . "' );</script>";


				$percent = ($wattweek/$wattweeks);
				$percent = $percent*100;


				$percent = number_format($percent,0,",","");
				$wattweek = number_format($wattweek,3,",","");
				$wattweeks = number_format($wattweeks,3,",","");
				$wattweeksss  = number_format($wattweeksss,3,",","");
				$wattweeksss  = str_replace(',', '.', $wattweeksss);

				$wattweekssss  = number_format($wattweekssss,3,",","");
				$wattweekssss  = str_replace(',', '.', $wattweekssss);


				$wattweekssssd  = number_format($wattweekssssd,3,",","");
				$wattweekssssd  = str_replace(',', '.', $wattweekssssd);

				$wattweek = str_replace(',', '.', $wattweek);
				$wattweeks = str_replace(',', '.', $wattweeks);
				$gesamt = number_format($gesamt,2,",","");



			$graph = '<script src="/js/Chart.min.js"></script>
    	<script>
    		var lineChartData = {
 				labels : ["","","","",""],
 				datasets : [{
 					fillColor : ["rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,229,0.5)", "rgba(51,181,249,1)"],
 					pointColor : "rgba(51,181,229,1)",
 					pointStrokeColor : "#fff",
 					data : ['.$wattweekssssd.','.$wattweekssss.','.$wattweeksss.','.$wattweeks.','.$wattweek.',0]
 				}]
 			};
      var ctx = document.getElementById("line").getContext("2d");
 			new Chart(ctx).Bar(lineChartData, {
    			scaleShowLabels : true,
 				caleShowGridLines : true,
 				animation : false,
 				scaleFontSize : 9,
 				barValueSpacing : 0,
 				barShowStroke : false,
 				scaleOverlay : false,
 				barDatasetSpacing : 0
			});
    	</script>';

		$apids = mysql_query("SELECT mac FROM module WHERE id = '$var1'");
   			$macs = "";
   			while($rows = mysql_fetch_array($apids)) {
   				$macs = $rows["mac"];
			}
			$macs = print_r($macs,true);




		$headline = '<p style="width:100%; color: #33B5E5; font-size:14pt; margin: 0; padding: 0;" align="center">'.$name.'</p>';
		echo $headline;
		echo '<p style="width:100%; color: #33B5E5; font-size:11pt; margin: 0; padding: 0;">Informationen</p>';
		$dynamicList = '<ul style="margin: 0;">
						<li>Raum: '. $room .'</li>
						<li>Gesamt: '. $gesamt .'kWh</li>
						<li>Seit: '. $mintime .'</li>
						</ul>';
		echo $dynamicList;
		echo '<p style="width:100%; color: #33B5E5; font-size:11pt; margin: 0; padding: 5;">Geräteschaltung</p>';
		$checkbox = '';
		if($isOn == '1') {
			if($switchable == '1') {
			$checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.1; padding: 0;">
            <input type="checkbox" onclick="handleClick(this,\''.$macs.'\')"; checked="true" value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:3px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
			} else {
			$checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.1; padding: 0;">
            <input type="checkbox" disabled="disabled" onclick="handleClick(this,\''.$macs.'\')"; checked="true" value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:3px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
			}

		} else {
		if($switchable == '1') {
		$checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.1; padding: 0;">
            <input type="checkbox" onclick="handleClick(this,\''.$macs.'\')";  value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:3px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
		} else {
		$checkbox = '<div class="flatRoundedCheckbox" style="margin: 0.1; padding: 0;">
            <input type="checkbox" disabled="disabled" onclick="handleClick(this,\''.$macs.'\')";  value="2" id="flatOneRoundedCheckbox" name="O"/>
            <label style="padding-left:25px; color:#888888; padding-top:3px;" for="flatOneRoundedCheckbox">O</label>
            <div></div>
        </div>';
        }
		}
		echo $checkbox;
		echo '<p style="width:100%; color: #33B5E5; font-size:11pt; margin: 0; padding: 0;">Wochenvergleich in Kilowattstunden</p>';
		echo $graph;

		mysql_close($db);
		}
		?>
		<canvas style="padding:-10em;" height="230" width="280" id="line" ></canvas>
	</body>
</html>



