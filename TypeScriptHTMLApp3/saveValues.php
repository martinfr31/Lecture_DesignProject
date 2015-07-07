<?php
    header('Access-Control-Allow-Headers: *');   
    
    $dbname = 'energy13';
    $con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root', 'solidrun');
    if (!$con) {
	die('Could not connect: ' . mysql_error());
	echo mysql_error();
    }
    mysql_select_db($dbname);

    
    $temp     = $_GET["temp"];
    $feucht = $_GET["feucht"];
    $temp     = mysql_real_escape_string($temp);
    $feucht = mysql_real_escape_string($feucht);

    $query    = "INSERT INTO smart_home (temp, feucht, hell, time) VALUES('$temp', '$feucht', '0' , CURRENT_TIMESTAMP)";
    mysql_query($query) or trigger_error(mysql_error()." in ".$query);  
    
    
    
    
    
    
    
    echo 'Ok';
?>