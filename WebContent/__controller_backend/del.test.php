<?php
include_once( '../_lib/start.php');
include_once( __MODEL__. 'test.php');

$ictl  = new Controller();

$f_idx = $_POST['f_idx'];

$iTest = new test();

echo $iTest->del( rawurldecode( $f_idx));
?>