<?php
    header('Access-Control-Allow-Headers: *');
    

    
    class Energy {
        public $pid;
        public $temp;
        public $luft;
        public $time;
        
        function Energy($a1,$a2,$a3,$a4) {
	     $this->pid = $a1;
	     $this->temp = $a2;
	     $this->luft = $a3;
	     $this->time = $a4;
	}
    }
    
    $dbname = 'energy13';
    $con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', 'solidrun');
    if (!$con) {
	die('Could not connect: ' . mysql_error());
	echo mysql_error();
    }
    mysql_select_db($dbname);
    $sql = mysql_query("SELECT id,time,temp,feucht FROM smart_home");
    $id = "";
    $tempen = 0;
    $feuchten = 0;
    $times = "";
    $arr = array();
    while($row = mysql_fetch_array($sql)) {
        $id = $row['id'];
        $tempen = $row['temp'];
        $feuchten = $row['feucht'];
        $times = $row['time'];
        $tempen = number_format($tempen,2,".","");
	$feuchten = number_format($feuchten,2,".","");
	$powerOBJ = new Energy($id,$tempen,$feuchten,$times);
        array_push($arr, $powerOBJ);
    }

    echo json_encode($arr ,JSON_NUMERIC_CHECK );
?>