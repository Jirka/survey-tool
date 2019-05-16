<?php
if(!isset($_SERVER['HTTP_REFERER'])) {
  return;
}
if(!preg_match("/.*\/index\.php$/", parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH))) {
  return;
}
parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $query );
$task=$query['task'];
$folder=$query['folder'];
$iniFile = "ini/".$task.".ini";

// test url
if(!isset($task) || !isset($folder) || !file_exists($iniFile)) {
  echo "err: Invalid link.";
  return;
}

$variant = $query['variant'];

$filesPath = "variants/".$variant."/".$folder;
if(!file_exists($filesPath)) {
  echo "err: Invalid link.";
  return;
}

$result = "";
$actImgID = "";
$count = 0;
foreach($_POST as $key => $value) {
   if($value == "" || !preg_match("/input_radio_([^_]*)_?.*/",$key,$m)) {
      echo "err: invalid post data";
      return;
   }
   if($m[1] == $actImgID) {
     $result .= ":$value";
   } else {
     if($result != "") {
       $result .= "\n";
     }
     $result .= "$m[1]:$value";
   }
   $actImgID = $m[1];
   $count++;
}

// todo check number of items
$path = $filesPath."/*.*";
$filesCount=count(glob($path));
$axisCount = count(parse_ini_file($iniFile, true));
if($filesCount*$axisCount != $count) {
  echo "err: invalid post data";
  return;
}

// counter
$hash = md5(uniqid(rand(), true));

$log = "log/".$task."_".$folder."_".$hash.".txt";

//echo file_put_contents($log, $result);
$fh = fopen($log, 'w');
fwrite($fh, date('m/d/Y h:i:s a', time())."\n".$result);
fclose($fh);

echo $result;
?>
