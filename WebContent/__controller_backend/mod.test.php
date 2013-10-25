<?php
include_once( '../_lib/start.php');
include_once( __MODEL__. 'test.php');

$ictl  = new Controller();

$f_idx = $_POST['f_idx'];
$f_key = $_POST['f_key'];
$f_val = $_POST['f_val'];

$iTest = new test();

echo $iTest->mod( rawurldecode( $f_idx), rawurldecode( $f_key), rawurldecode( $f_val));
?>