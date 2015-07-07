<?php
    header('Access-Control-Allow-Headers: *');
    
    class Energy {
        public $pid;
        public $power;
        public $deviceName;
        public $time;
        public $lastHour;
        
        function Energy($a1,$a2,$a3,$a4,$a5) {
	     $this->pid = $a1;
	     $this->power = $a2;
	     $this->deviceName = $a3;
	     $this->time = $a4;
	     $this->lastHour = $a5;
	}
    }
    
    $dbname = 'energy13';
    $con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', 'solidrun');
    if (!$con) {
	die('Could not connect: ' . mysql_error());
	echo mysql_error();
    }
    mysql_select_db($dbname);
    $sql = mysql_query("SELECT id,moduleName FROM module");
    $wattoverall = 0;
    $arr = array();
    while($row = mysql_fetch_array($sql)) {
        $id = $row['id'];
        $name = $row['moduleName'];
        $watt = 0.0;
        $lastwatt = 0.0;
        $timer = "";
        $sqlP = mysql_query("SELECT watt, currenttime FROM live_log WHERE module_id = '$id' ORDER BY ID DESC LIMIT 1");
        while($rowss = mysql_fetch_array($sqlP)) {
            $watt = $rowss["watt"];
            $timer = $rowss["currenttime"];
            $wattoverall = $wattoverall + $watt;
            $watt = number_format($watt,2,",","");
	    $wattoverall = number_format($wattoverall,2,",","");
	}
        $sqlPs = mysql_query("SELECT kwh FROM history_log WHERE module_id = '$id' ORDER BY id DESC LIMIT 0, 1;");
        while($rowsss = mysql_fetch_array($sqlPs)) {
            $lastwatt = $rowsss["kwh"];
            $lastwatt = number_format($lastwatt,2,",","");
	}    
	    $powerOBJ = new Energy($id,$watt,$name,$timer,$lastwatt);
            array_push($arr, $powerOBJ);
    }

    echo json_encode($arr);
?>