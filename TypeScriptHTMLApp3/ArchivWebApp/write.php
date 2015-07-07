<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
$lineto = $_GET['text'];
$myFile = "WebLogFile.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
$lineto .= " \r\n";
echo("<b>Test: ". $lineto ."</b>");
fwrite($fh, $lineto);
fclose($fh);

?>