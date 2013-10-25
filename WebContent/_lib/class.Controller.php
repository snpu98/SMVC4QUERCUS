<?php
class Controller{
	private $method;

	private $req;
	private $sess;
	private $cook;


	function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->req          = $_REQUEST;
		$this->req['FILES'] = $_FILES;
		$this->cook         = $_COOKIE;
		$this->sess         = $_SESSION;

		$json = json_encode
		(
			array
			(
				'method'  => $this->method,
				'request' => $this->req,
				'cookie'  => $this->cook,
				'session' => $this->sess
			)
		);

		$line  = "==================================================";
		$line .= "==================================================";
		$line .= "==================================================";

		logWrite( $line);

		foreach( $_SERVER as $key=>$val)
		{
			$txt .= "| ";
			$txt .= str_replace( "_", " ", $key);

			$txt = str_pad( $txt,  24, " ", STR_PAD_RIGHT) . ": ";

			$txt .= str_replace( "_", " ", $val);

			$txt = str_pad( $txt, 149, " ", STR_PAD_RIGHT) . "|";

			logWrite( $txt );

			$txt = '';
		}

		logWrite( $line);

		logWrite( "INFO  " .__METHOD__. "() : $json");
	}

	function __destruct()
	{

	}

	function getMethod()
	{
		logWrite( "INFO  " .__METHOD__. "() : {$this->method}");

		return $this->method;
	}

	function getReq( $_key)
	{
		logWrite( "INFO  " .__METHOD__. "( '$_key') : {$this->req[$_key]}");

		return ( isset( $this->req[$_key]))  ? $this->req[$_key]  : null;
	}

	function getCook( $_key)
	{
		logWrite( "INFO  " .__METHOD__. "( '$_key') : {$this->cook[$_key]}");

		return ( isset( $this->cook[$_key])) ? $this->cook[$_key] : null;
	}

	function getSess( $_key)
	{
		logWrite( "INFO  " .__METHOD__. "( '$_key') : {$this->sess[$_key]}");

		return ( isset( $this->sess[$_key])) ? $this->sess[$_key] : null;
	}


	function getReqArr()
	{
		if( is_array( $this->req))
		{
			$json = json_encode( $this->req);

			logWrite( "INFO  " .__METHOD__. "() : $json");
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "() : Request array is empty.");
		}

		return $this->req;
	}

	function getCookArr()
	{
		if( is_array( $this->cook))
		{
			$json = json_encode( $this->cook);

			logWrite( "INFO  " .__METHOD__. "() : $json");
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "() : Cookie array is empty.");
		}

		return $this->cook;
	}

	function getSessArr()
	{
		if( is_array( $this->sess))
		{
			$json = json_encode( $this->sess);

			logWrite( "INFO  " .__METHOD__. "() : $json");
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "() : Session array is empty.");
		}

		return $this->sess;
	}


	function setCook( $_key, $_val, $_expire = 0)
	{
		$this->cook[$_key] =  $_val;

		try
		{
			setcookie( $_key, $_val, $_expire);

			logWrite( "INFO  " .__METHOD__. "( '$_key', '$_val', '$_expire') : Success.");

			return true;
		}
		catch( Exception $ex)
		{
			logWrite( "ERROR " .__METHOD__. "( '$_key', '$_val', '$_expire') : {$ex->getMessage()}");

			return false;
		}
	}

	function setSess( $_key, $_val)
	{
		$this->sess[$_key] = $_val;
		$_SESSION[$_key]   = $_val;

		logWrite( "INFO  " .__METHOD__. "( '$_key', '$_val') : Success.");

		return true;
	}


	function upload( $_dstPath, $_dstFileName, $_name)
	{
		$_dest = $_dstPath . $_dstFileName;

		if( isset( $this->req['FILES'][$_name]['tmp_name']))
		{
			$src = $this->req['FILES'][$_name]['tmp_name'];

			try
			{
				move_uploaded_file( $src, $_dest);

				logWrite( "INFO  " .__METHOD__. "( '$_dest', '$_name') : Success.");

				return true;
			}
			catch( Exception $ex)
			{
				logWrite( "ERROR " .__METHOD__. "( '$_dest', '$_name') : {$ex->getMessage()}");
				return false;
			}

			return true;
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_dest', '$_name') : There is no 'FILES/$_name' request.");

			return false;
		}
	}

	function thumnail( $_srcPath, $_srcFileName, $_dstPath, $_dstFileName, $_maxWidth, $_maxHeight)
	{
		$img_info = getImageSize( $_srcPath);

		if( $img_info[2]==1)
		{
			$srcImg = ImageCreateFromGif ( $_srcPath);
		}
		elseif( $img_info[2]==2)
		{
			$srcImg = ImageCreateFromJPEG( $_srcPath);
		}
		elseif( $img_info[2]==3)
		{
			$srcImg = ImageCreateFromPNG ( $_srcPath);
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_srcPath', '$_srcFileName', '$_dstPath', '$_dstFileName', '$_maxWidth', '$_maxHeight') : Image format is not JPEG, GIF or PNG.");

			return false;
		}

		$imgWidth  = $img_info[0];
		$imgHeight = $img_info[1];

		if( $imgWidth>$_maxWidth || $imgHeight>$_maxHeight)
		{
			if( $imgWidth==$imgHeight)
			{
				$dstWidth  = $_maxWidth;
				$dstHeight = $_maxHeight;
			}
			elseif( $imgWidth>$imgHeight)
			{
				$dstWidth  = $_maxWidth;
				$dstHeight = ceil( ( $_maxWidth / $imgWidth) * $imgHeight);
			}
			else
			{
				$dstHeight = $_maxHeight;
				$dstWidth  = ceil( ( $_maxHeight / $imgHeight) * $imgWidth);
			}
		}
		else
		{
			$dstWidth  = $imgWidth;
			$dstHeight = $imgHeight;
		}

		if( $dstWidth  < $_maxWidth ) $srcx = ceil( ( $_maxWidth  - $dstWidth ) / 2); else $srcx = 0;
		if( $dstHeight < $_maxHeight) $srcy = ceil( ( $_maxHeight - $dstHeight) / 2); else $srcy = 0;

		if( $img_info[2]==1)
		{
			$dstImg = imagecreate( $_maxWidth, $_maxHeight);
		}
		else
		{
			$dstImg = imagecreatetruecolor( $_maxWidth, $_maxHeight);
		}

		$bgc = ImageColorAllocate( $dstImg, 255, 255, 255);

		ImageFilledRectangle( $dstImg, 0, 0, $_maxWidth, $_maxHeight, $bgc);

		ImageCopyResampled
		(
			$dstImg, $srcImg,
			$srcx, $srcy,
			0, 0,
			$dstWidth, $dstHeight,
			ImageSX( $srcImg), ImageSY( $srcImg)
		);

		if( $img_info[2]==1)
		{
			ImageInterlace( $dstImg);
			ImageGif      ( $dstImg, $_dstPath .$_dstFileName);
		}elseif( $img_info[2]==2)
		{
			ImageInterlace( $dstImg);
			ImageJPEG     ( $dstImg, $_dstPath .$_dstFileName);
		}
		elseif( $img_info[2]==3)
		{
			ImagePNG      ( $dstImg, $_dstPath .$_dstFileName);
		}

		ImageDestroy( $dstImg);
		ImageDestroy( $srcImg);

		logWrite( "INFO  " .__METHOD__. "( '$_srcPath', '$_srcFileName', '$_dstPath', '$_dstFileName', '$_maxWidth', '$_maxHeight') : Success.");

		return true;
	}

	function vars2input( $_allocArr)
	{
		$rtn = "$(function(){\n";

		if( is_array($_allocArr))
		{
			foreach( $_allocArr as $key=>$val)
			{
				$rtn .= "	if( $( '[name=$key]').get( 0).tagName=='INPUT' && $( '[name=$key]').attr( 'type')=='radio')\n";
				$rtn .= "	{\n";
				$rtn .= "		$( '[name=$key][value=$val]').prop( 'checked',true);\n";
				$rtn .= "	}\n";
				$rtn .= "	else\n";
				$rtn .= "	if( $( '[name=$key]').get( 0).tagName=='INPUT' && $( '[name=$key]').attr( 'type')=='checkbox')\n";
				$rtn .= "	{\n";
				$rtn .= "		$( '[name=$key][value=$val]').prop( 'checked', true);\n";
				$rtn .= "	}\n";
				$rtn .= "	else\n";
				$rtn .= "	if( $('#$key').get( 0).tagName=='INPUT' && $( '#$key').attr( 'type')=='text')\n";
				$rtn .= "	{\n";
				$rtn .= "		$( '#$key').val( '$val');\n";
				$rtn .= "	}\n";
				$rtn .= "	else\n";
				$rtn .= "	if( $( '#$key').get( 0).tagName=='SELECT')\n";
				$rtn .= "	{\n";
				$rtn .= "		$( '#$key').val( '$val');\n";
				$rtn .= "	}\n";
				$rtn .= "	else\n";
				$rtn .= "	{\n";
				$rtn .= "		$( '#$key').html( '$val');\n";
				$rtn .= "	}\n";
			}
		}

		$rtn .= "});\n";

		logWrite( "INFO  " .__METHOD__. "( array) : Success.");

		return $rtn;
	}

	function makeTable( $_tableName, $_PKName, $_rows)
	{
		$rtn  = "$(function(){\n";
		$rtn .= "	var tbl = $( '#$_tableName');\n";
		$rtn .= "	var tr0 = tbl.find( 'tr[name=idx]'   ).clone();\n";
		$rtn .= "	var trX = tbl.find( 'tr[name=noRows]').clone();\n";
		$rtn .= "	tbl.find( 'tr[name=idx]'   ).remove();\n";
		$rtn .= "	tbl.find( 'tr[name=noRows]').remove();\n";
		if( is_array( $_rows))
		{
			if( count( $_rows)>0)
			{
				foreach( $_rows as $row)
				{
					$idx  = $row[$_PKName];
					$rtn .= "	var tr = tr0.clone();\n";
					$rtn .= "	tr.attr( 'value', '$idx');\n";

					foreach( $row as $colName => $colValue)
					{
						$rtn .= "	tr.find( '[name=$colName]').html( '$colValue');\n";
					}

					$rtn .= "	tbl.append( tr);\n";
				}
			}
			else
			{
				$rtn .= "	$( '#$_tableName').find( 'tr[name=idx]').remove();\n";
				$rtn .= "	tbl.append( trX);\n";
			}

		}
		else
		{
			$rtn .= "	tbl.append( trX);\n";
		}

		$rtn .= "});\n";

		logWrite( "INFO  " .__METHOD__. "( '$_tableName', '$_PKName', array) : Success.");

		return $rtn;
	}


	function makeSelect( $_selectId, $_valueColName, $_textColName, $_rows, $_firstText=null)
	{
		$rtn  = "$(function(){\n";
		$rtn .= "	var sel  = $( '#$_selectId');\n";
		$rtn .= "	var opt0 = sel.children( 'option').eq( 0).clone();\n";
		$rtn .= "	sel.children( 'option').remove();\n";

		if( $_firstText!=null && $_firstText!='')
		{
			$rtn .= "	var opt  = opt0.clone();\n";
			$rtn .= "	opt.attr( 'value', '');\n";
			$rtn .= "	opt.text( '$_firstText');\n";
			$rtn .= "	sel.append( opt)\n";
		}

		if( is_array( $_rows))
		{
			if( count( $_rows)>0)
			{
				foreach( $_rows as $row)
				{
					$val = $row[$_valueColName];
					$txt = $row[$_textColName];
					$rtn .= "	var opt  = opt0.clone();\n";
					$rtn .= "	opt.attr( 'value', '$val');\n";
					$rtn .= "	opt.text( '$txt');\n";
					$rtn .= "	sel.append( opt);\n";
				}
			}
		}

		$rtn .= "});\n";

		logWrite( "INFO  " .__METHOD__. "( '$_selectId', '$_valueColName', '$_textColName', array, '$_firstText') : Success.");

		return $rtn;
	}


	function header( $_type)
	{
		switch( strtolower( $_type))
		{
			case 'javascript':
				$contentType = 'application/javascript';
				break;
			case 'json':
				$contentType = 'application/json';
				break;
			case 'xml' :
				$contentType = 'text/xml';
				break;
			case 'html':
				$contentType = 'text/html';
				break;
			default    :
				$contentType = 'text/html';
				break;
		}

		header( 'Content-type: ' . $contentType . ';charset=UTF-8');

		logWrite( "INFO  " .__METHOD__. "( '$_type') : Success => $contentType");
	}
}
?>