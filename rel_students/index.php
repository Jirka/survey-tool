<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="js/sliding_form.js"></script>
</head>
<body>

<br>

<?php

$variant = "";
$config = "";
$task = "";
$folder = "";
$filesPath = "";
if(isset($_GET['login']) && isset($_GET['task']) && isset($_GET['folder']))
{
  $task = $_GET['task'];
  $folder = $_GET['folder'];
  $txt_file = file_get_contents('logins.txt');
  if($txt_file != null) {
    if(preg_match("/".$_GET['login'].",(.)/",$txt_file,$m)) {
      $variant = $m[1];
      $tmpPath = "variants/".$variant."/".$folder;
      if(file_exists($tmpPath)) {
        $filesPath = $tmpPath;
        $iniFile = "ini/".$task.".ini";
        if(file_exists($iniFile)) {
          $config = parse_ini_file("ini/".$task.".ini", true);
        }
      }
    }
  }
}

if($variant != "" && $config != "" && $filesPath != "")
{
?>
<div id="loader">
  <p>Stahování dashboardů...</p>
</div>
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
<?php
  $i=2;
  $path=$filesPath."/*.*";
  $files=glob($path);
  foreach($files as $file) {
?>
  <span class="dot" onclick="currentSlide(<?php echo $i;?>)"></span>
<?php
    $i++;
  }
?>
  <span class="dot" onclick="currentSlide(<?php echo $i;?>)"></span> 
</div>

<br>

<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

<div class="slideshow-container">
<form id="formElem" name="formElem" action="" method="post">
<div class="mySlides fade">
<?php
  include('html/'.$_GET['task'].'.html');
?>  
  <br>
  <button class="start_button" type="button" onclick="plusSlides(1)">Rozumím</button> 
</div>
<?php
  foreach($files as $file) {
    $path_parts = pathinfo($file);
    $fn=$path_parts['filename'];
    $dn=$path_parts['dirname'];
    $bn=$path_parts['basename'];
?>
<div class="mySlides fade">
  <div class="dashboard"><img src="<?php echo $file;?>" style="width:600px" border="1"></div>
  <ul class="btn-group">
  <?php
  $xs = $config['x'];
  $j = 1;
  foreach($xs as $x) {
  ?>
    <li class="radio_item fade">
      <input type="radio" id="radio_<?php echo $fn;?>_x_<?php echo $j;?>" name="input_radio_<?php echo $fn;?>_x" value="<?php echo $x['value'];?>" />
      <label for="radio_<?php echo $fn;?>_x_<?php echo $j;?>"><?php echo $x['label'];?></label>
    </li>
  <?php
    $j++;
  }
  ?>
  </ul>

<?php
  if(isset($config['y'])) {
?>
  <ul class="btn-group2">
  <?php
  $xs = $config['y'];
  $j = 1;
  foreach($xs as $x) {
  ?>
    <li class="radio_item fade">
      <input type="radio" id="radio_<?php echo $fn;?>_y_<?php echo $j;?>" name="input_radio_<?php echo $fn;?>_y" value="<?php echo $x['value'];?>" />
      <label for="radio_<?php echo $fn;?>_y_<?php echo $j;?>"><?php echo $x['label'];?></label>
    </li>
  <?php
    $j++;
  }
  ?>
  </ul>
<?php
  } // if y is set
?>
  <!--<a class="hide_btn" onclick="hideButtons()">x</a>-->
  <a class="hide_btn" target="_blank" href="<?php echo $dn.'/full/'.$bn;?>"><img src="full.png" style="width:20px"></a>
</div>
<?php
    $i++;
  }
?>
<div class="mySlides fade">
  <p class="form_done">
  Děkujeme za účast v průzkumu.<br>
  Pro dokončení vygenerujte soubor, který odevzdejte do WISu.
  <br>
  </p>
  <input class="submit_button" type="submit" id="submit" value="Dokončit" action="" />
  <p class="form_uncomplete">
  Některé dashboardy nebyly ohodnoceny.<br>
  Prosíme, vyplňte chybějící položky.
  </p>
  <div class="form_finished" style="display:none;">
  <a class="final_link" href="#" onclick="download_file(); return false;"><?php echo $task."_".$folder;?>.txt</a>
  <br>
  <p style="font-size:50%">
  V případě, že vám soubor nejde stáhnout, překopírujte následující výstup do prázdného souboru <?php echo $task."_"."$folder";?>.txt a odevzdejte.
  </p>
  <p class="result_code">
  </p>
  </div>
</div>
</form>
</div>

<?php
}
else
{
?>
<p style="text-align: center;">
Nesprávný odkaz. Zkontrolujte, že jste postupovali podle zadání.
</p>
<?php
}
?>
</body>
</html> 
