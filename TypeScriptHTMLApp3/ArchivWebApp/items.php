<?php




			echo '<ul id="thelist" style="list-style-type:none;margin:0px;">';
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


			$smartMeterDa = false;

			date_default_timezone_set('Europe/Berlin');
			$currentTime = round(microtime(true) * 1000);

			$sqlSmart = mysql_query("SELECT wirkleistung,time, wirkarbeittot FROM smart_meter_values WHERE time = (SELECT max(time) from smart_meter_values)");
			$smartRowCount = mysql_num_rows($sqlSmart);
			$wattoverallsmart = 0;
			$lasttime = 0;
			$zaehlerstand = 0;
			if($smartRowCount>=1) {
				while($rowdd = mysql_fetch_array($sqlSmart)) {
					$millisecondss = 1000 * strtotime($rowdd["time"]);
        			$diffs = $currentTime-$millisecondss;
				$lasttime = $rowdd["time"];
        			$zaehlerstand = $rowdd["wirkarbeittot"];
        			if($diffs<100000) {
        					$wattoverallsmart = $rowdd["wirkleistung"];
							$smartMeterDa = true;
        			}
				}
			}

			$sqlSmart = mysql_query("SELECT time, wirkarbeittot FROM smart_meter_values WHERE time = (SELECT min(time) from smart_meter_values)");
                        $smartRowCount = mysql_num_rows($sqlSmart);
                        $firsttime = 0;
                        $zaehlerstandold = 0;
                        if($smartRowCount>=1) {
                                while($rowdd = mysql_fetch_array($sqlSmart)) {
					$firsttime = $rowdd["time"];
					$zaehlerstandold = $rowdd["wirkarbeittot"];
				}
                        }


			$now = strtotime($lasttime);
     			$your_date = strtotime($firsttime);
     			$datediff = $now - $your_date;
     			$daysdiff = $datediff/(60*60*24);
			$daysdiff = (int)$daysdiff;

			$sqlSmart = mysql_query("SELECT anticipated_comsum_year  FROM electricity_rate");
                        $smartRowCount = mysql_num_rows($sqlSmart);
                        $antizi = 0;
                        if($smartRowCount>=1) {
                                while($rowdd = mysql_fetch_array($sqlSmart)) {
                                        $antizi = $rowdd["anticipated_comsum_year"];
                                }
                        }

			$antizi = $antizi/365;
			$antizi = $zaehlerstandold + ($daysdiff*$antizi);
			if($antizi >= $zaehlerstand) {
				$antizi =  $antizi/$zaehlerstand;
			} else {
				$antizi = $zaehlerstand/$antizi;
			}

			$antizi = (1-$antizi)*100;

			$antizi = number_format($antizi,2,",","");

			$vergleich = $antizi;



			$sql = mysql_query("SELECT * FROM module");
			$productCount = mysql_num_rows($sql);
			$wattoverall = 0;
			while($row = mysql_fetch_array($sql)) {
				$id = $row['id'];
   				$apids = mysql_query("SELECT id FROM views WHERE view_name = 'appliance'");
   				$apid = 0;
   				while($rows = mysql_fetch_array($apids)) {
   					$apid = $rows["id"];
				}
   				$sqls = mysql_query("SELECT * FROM tag_value WHERE module_id = '$id' AND view_id = '$apid'");
   				$num_rows = mysql_num_rows($sqls);
   				$name = "";
   				if($num_rows > 0) {
   					while($row = mysql_fetch_array($sqls)) {
        				$name = $row["tag_value"];
					}
				}
				$sqlss = mysql_query("SELECT watt, currenttime FROM live_log WHERE module_id = '$id'");
   				$num_rowss = mysql_num_rows($sqlss);
   				$watt = 0.0;
   				$timeold = false;
   				date_default_timezone_set('Europe/Berlin');
				$currentTimeBerlinInt = round(microtime(true) * 1000);
   				if($num_rowss > 0) {
   					while($rowss = mysql_fetch_array($sqlss)) {
        				$watt = $rowss["watt"];
        				$milliseconds = 1000 * strtotime($rowss["currenttime"]);
        				$diff = $currentTimeBerlinInt-$milliseconds;
        				if($diff>600000) {
        					$timeold = true;
        				}
        				$wattoverall = $wattoverall + $watt;
					}
				}

				$apids = mysql_query("SELECT status FROM app_status WHERE modul_id = '$id'");
   				$status = 0;
   				while($rows = mysql_fetch_array($apids)) {
   					$status = $rows["status"];
				}

        $apids = mysql_query("SELECT isOn FROM module WHERE id = '$id'");
   				$isOn = 0;
   				while($rows = mysql_fetch_array($apids)) {
   					$isOn = $rows["isOn"];
				}

				$watt = number_format($watt,2,",","");
				$wattoverall = number_format($wattoverall,2,",","");

        if($isOn == '0') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#0000FF">Ausgeschaltet</span></p>';
        } elseif($status == '0') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#1E90FF">Training...</span></p>';
        }  elseif($status == '100') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#009900">In Ordnung</span></p>';
				} elseif ($status == '101') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">Standby</span></p>';
				} elseif ($status == '102') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#009900">In Ordnung</span></p>';
				} elseif ($status == '200') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#009900">In Ordnung</span></p>';
				} elseif ($status == '201') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#009900">Standby</span></p>';
				} elseif ($status == '202') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#009900">In Ordnung</span></p>';
				} elseif ($status == '887') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">Calc-Fehler</span></p>';
				} elseif ($satus == '888') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">Fehler</span></p>';
				} elseif ($status == '998') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">Calc-Fehler</span></p>';
				} elseif ($status == '999') {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">Fehler</span></p>';
				} else {
				$statusLine = '<p style="margin-bottom:0em;margin-top:0em;color:#333333;font-size:0.8em;text-decoration: none; font-weight:normal;">Status: <span style="color:#FF3333">!!!Error!!!</span></p>';
				}

				$dynamicList .= '<a style="color: #000000; font-weight:normal;  text-decoration: none;" data-id="'.$id.'" href="#popupBasic2" data-rel="popup" data>
				<li class="menu_hover" style="margin-left: -2.5em;padding: 0.4em 0.6em 0.4em;border-bottom: 1px solid #C9C9C9;">

				<div style="width:100%">

					<div style="float:left;">
						<img src="img/contact.png" height="40" width="40" style="vertical-align: middle;padding-right:0.4em;"/>
					</div>

					<div style="float:left;">
						<p style="margin-bottom:0em;margin-top:0.11em;font-size:1.0em;text-decoration: none;"><b>'. $name .'</b></p>
						'. $statusLine . '
					</div>

					<div style="float:right;padding:0.5em;min-height: 100%;height:100%;">';

				if($timeold) {
				$dynamicList .= '		<p style="margin:0;color: #DF0101; font-size:1.2em;text-decoration: none; font-weight:normal;" >'.$watt.' W</p>';
				} else {
				$dynamicList .= '		<p style="margin:0;color: #33B5E5; font-size:1.2em;text-decoration: none; font-weight:normal;" >'.$watt.' W</p>';
				}
				$dynamicList .= '	</div>

				</div>

				<div style="clear:left"></div>

				</li>
				</a>';

			}

			$listview = '';

			if($smartMeterDa) {

			$listview = '<a style="color: #000000; font-weight:normal;  text-decoration: none;" data-id="999999" href="#popupBasic2" data-rel="popup" data>
				<li class="menu_hover" style="margin-left: -2.5em;padding: 0.4em 0.6em 0.4em;border-bottom: 1px solid #C9C9C9;">

				<div style="width:100%">


					<div>
						<p align="center" style="margin-bottom:0em;margin-top:0.11em;font-size:1.0em;text-decoration: none;"><b align="center">Aktueller Gesamtverbrauch</b></p>
						<p align="center" style="margin:0;color: #33B5E5; font-size:1.2em;text-decoration: none; font-weight:normal;" >'.$wattoverallsmart.' W (Plugs: '.$wattoverall.' W)</p>
						<p align="center" style="margin:0;color: #33B5E5; font-size:1.2em;text-decoration: none; font-weight:normal;" >Zählerstand: '.$zaehlerstand.' ('.$vergleich.'%)</p>
					</div>


				</div>

				<div style="clear:left"></div>

				</li>
				</a>';
			} else {
			$listview = '<a style="color: #000000; font-weight:normal;  text-decoration: none;" data-id="999999" href="#popupBasic2" data-rel="popup" data>
				<li class="menu_hover" style="margin-left: -2.5em;padding: 0.4em 0.6em 0.4em;border-bottom: 1px solid #C9C9C9;">

				<div style="width:100%">


					<div>
						<p align="center" style="margin-bottom:0em;margin-top:0.11em;font-size:1.0em;text-decoration: none;"><b align="center">Aktueller Gesamtverbrauch</b></p>
						<p align="center" style="margin:0;color: #33B5E5; font-size:1.2em;text-decoration: none; font-weight:normal;" >'.$wattoverall.' W</p>
						<p align="center" style="margin:0;color: #33B5E5; font-size:1.0em;text-decoration: none; font-weight:normal;" >Zählerstand: '.$zaehlerstand.' ('.$vergleich.'%)</p>
					</div>


				</div>

				<div style="clear:left"></div>

				</li>
				</a>';
			}
			$listview .= $dynamicList;


      $waste = 0;
      $europerday = 0;

      $sqlSmartWaste = mysql_query("SELECT waste FROM app_waste");
      $smartRowCountWaste = mysql_num_rows($sqlSmartWaste);
      $waste = 0;
      if($smartRowCount>=1) {
        while($rowdd = mysql_fetch_array($sqlSmartWaste)) {
          $waste = $rowdd["waste"];
        }
      }

      $sqlSmartMoney = mysql_query("SELECT price_kwh FROM electricity_rate");
      $smartRowCountMoney = mysql_num_rows($sqlSmartMoney);
      $money = 0;
      if($smartRowCount>=1) {
        while($rowdd = mysql_fetch_array($sqlSmartMoney)) {
          $money = $rowdd["price_kwh"];
        }
      }

      $europerday = ($waste/1000)*(24*$money);
      $europerday = number_format($europerday,2,",","");

      $listviewend = '<a style="color: #000000; font-weight:normal;  text-decoration: none;" data-id="999999" href="#popupBasic2" data-rel="popup" data>
				<li class="menu_hover" style="margin-left: -2.5em;padding: 0.4em 0.6em 0.4em;border-bottom: 1px solid #C9C9C9;">

				<div style="width:100%">


					<div>
						<p align="center" style="margin-bottom:0em;margin-top:0.11em;font-size:1.0em;text-decoration: none;"><b align="center">Potentielle Verschwendung</b></p>
						<p align="center" style="margin:0;color: #33B5E5; font-size:1.0em;text-decoration: none; font-weight:normal;" >'.$waste.' W ('.$europerday.' Euro/Tag)</p>
					</div>


				</div>

				<div style="clear:left"></div>

				</li>
				</a>';

      $listview .= $listviewend;
			echo $listview;
			mysql_close($db);
			echo '</ul>';

		?>
