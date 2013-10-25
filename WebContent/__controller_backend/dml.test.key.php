<?php
include_once( '../_lib/start.php');
include_once( __MODEL__. 'test.php');

$ictl  = new Controller();

$f_key = $_POST['f_key'];

$iTest  = new test();

$is_key = $iTest->isKey( rawurldecode( $f_key));

if( $is_key)
{
	echo 'ok';
}
else
{
	echo 'no';
}
?>