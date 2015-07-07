<?php
    header('Access-Control-Allow-Headers: *');
    
    class Energy {
        public $power;
        public $hour;
        
        function Energy($a1,$a2) {
	     $this->power = $a1;
	     $this->hour = $a2;
	}
    }
    
    $dbname = 'energy13';
    $con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', 'solidrun');
    if (!$con) {
	die('Could not connect: ' . mysql_error());
	echo mysql_error();
    }
    mysql_select_db($dbname);
    $sql = mysql_query("SELECT sum(kwh),hourvalue FROM history_log WHERE id>=((SELECT max(id) FROM history_log)-255) GROUP BY hourvalue ORDER BY hourvalue DESC LIMIT 25;");
    $arr = array();
    while($row = mysql_fetch_array($sql)) {
        $watti = $row['sum(kwh)'];
        $times = $row['hourvalue'];
        $powerOBJ = new Energy($watti,$times);
        array_push($arr, $powerOBJ);
    }

    echo json_encode($arr);
?>