<?php
if(!isset($_SERVER['HTTP_REFERER'])) {
  return;
}
if(!preg_match("/.*\/index\.php$/", parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH))) {
  return;
}
parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $query );
$login=$query['login'];
$task=$query['task'];
$folder=$query['folder'];
$iniFile = "ini/".$task.".ini";

// test url
if(!isset($login) || !isset($task) || !isset($folder) || !file_exists($iniFile)) {
  echo "err: Invalid link.";
  return;
}

// test login
$txt_file = file_get_contents('logins.txt');
if($txt_file == null) {
  echo "err: Server error. Try it later.";
  return;
}

if(!preg_match("/".$login.",(.)/",$txt_file,$m)) {
  echo "err: Invalid login.";
  return;
}
$variant = $m[1];

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

$log = "log/".$login."_".$task."_".$folder.".txt";
//echo file_put_contents($log, $result);
$fh = fopen($log, 'w');
fwrite($fh, date('m/d/Y h:i:s a', time())."\n".$result);
fclose($fh);

echo $result;
?>
