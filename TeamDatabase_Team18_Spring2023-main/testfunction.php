<?php
function pre($d=array()){
  ob_start();
  echo "<pre>";
  print_r($d);
  echo "</pre>";
  $c = ob_get_contents();
  ob_end_clean();
  return $d;
}
?>