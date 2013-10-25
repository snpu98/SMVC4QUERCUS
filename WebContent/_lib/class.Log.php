<?php
class Log
{
	function logWrite( $_msg)
	{
		if ( __LOG_MODE__)
		{
			$now  = date( 'Y/m/d h:i:s');			
			$_msg = "[$now] $_msg";
			$f    = fopen( __LOG_PATH__, "a");
			fwrite( $f, $_msg."\n");
			fclose( $f);
		}
	}
}

function logWrite( $_msg)
{
	$log = new Log();
	$log->logWrite( $_msg);
}
?>