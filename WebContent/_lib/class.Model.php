<?php
class Model extends Database
{
	private $tableName;
	private $colArr;
	private $PKName;

	function __construct( $_tableName = null, $_PKVal = null)
	{
		parent::__construct();

		if( $_tableName!=null || $_tableName!=''){
			$this->setTable( $_tableName, $_PKVal);
		}
	}

	function setTable( $_tableName, $_PKVal = null)
	{
		$this->tableName = null;
		$this->colArr    = null;
		$this->PKName    = null;

		$this->clear();

		$query = "SHOW COLUMNS FROM `$_tableName`";

		if( $this->read($query))
		{
			$this->tableName = $_tableName;
			$colArr          = $this->getRows();

			foreach( $colArr as $val)
			{
				$Key       = $val['Key'];
				$FieldName = $val['Field'];

				if( $Key=='PRI')
				{
					$this->PKName             = $FieldName;
					$this->colArr[$FieldName] = $_PKVal;
				}
				else
				{
					$this->colArr[$FieldName] = null;
				}
			}

			$json = json_encode
			(
				array
				(
					'PKName' => $this->PKName,
					'ColArr' => $this->colArr
				)
			);

			if( $this->PKName==null || $this->PKName=='')
			{
				logWrite( "WARN  " .__METHOD__. "( '$_tableName', '$_PKVal') : Primary key is no exist.");
			}

			logWrite( "INFO  " .__METHOD__. "( '$_tableName', '$_PKVal') : Table '$_tableName' read success. => $json");

			return true;
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_tableName', '$_PKVal') : Table '$_tableName' read fail.");

			return false;
		}
	}

	function getPKName()
	{
		if( $this->PKName==null || $this->PKName=='')
		{
			logWrite( "WARN  " .__METHOD__. "() : Primary key is not exist.");
		}

		return $this->PKName;
	}

	function getPKVal()
	{
		if( $this->colArr[$this->PKName]==null || $this->colArr[$this->PKName]=='')
		{
			logWrite( "WARN  " .__METHOD__. "() : Primary key value is empty.");
		}

		return $this->colArr[$this->PKName];
	}

	function getColArr()
	{
		if( is_array( $this->colArr))
		{
			$json = json_encode( $this->colArr);

			logWrite( "INFO  " .__METHOD__. "() : Success => $json");

			return $this->colArr;
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "() : Column array is empty.");

			return null;
		}
	}

	function getColVal( $_colName)
	{
		if( isset( $this->colArr[$_colName]))
		{
			logWrite( "INFO  " .__METHOD__. "( '$_colName') : Column '$_colName' value is '{$this->colArr[$_colName]}'.");

			return $this->colArr[$_colName];
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_colName') : Column '$_colName' is not exist.");

			return null;
		}
	}

	function setCol( $_colName, $_val)
	{
		if( isset( $this->colArr[$_colName]))
		{
			$this->colArr[$_colName] = $_val;

			logWrite( "INFO  " .__METHOD__. "( '$_colName', '$_val') : '$_colname'=>'$_val'");

			return true;
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_colName', '$_val') : Column '$_colname' is not exist.");

			return false;
		}
	}

	function setColArr( $_colArr)
	{
		if( isset( $this->colArr[$this->PKName]))
		{
			$_colArr[$this->PKName] = $this->colArr[$this->PKName];
		}

		$this->colArr = $_colArr;
		$json = json_encode( $this->colArr);

		logWrite( "INFO  " .__METHOD__. "( '$_colArr') : Success => $json");

		return;
	}

	function setPKVal( $_val)
	{
		if( $this->PKName!=null && $this->PKName!='')
		{
			$this->colArr[$this->PKName] = $_val;

			logWrite( "INFO  " .__METHOD__. "( '$_val') : '{$this->PKName}'=>'$_val'");

			return true;
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "( '$_val') : Primary key is not exist.");

			return false;
		}
	}

	function select( $_colNames = null, $_where = null)
	{
		if( $_where==null || $_where=='')
		{
			$PKName = $this->PKName;
			$PKVal  = $this->colArr[$this->PKName];

			if( $PKVal!=null && $PKVal!='')
			{
				$_where = "WHERE $PKName='$PKVal'";
			}
		}

		if( $_colNames==null || $_colNames=='')
		{
			$colNameArr = array();
			$cnt        = 0;

			foreach( $this->colArr as $key=>$val)
			{
				$colNameArr[] = $key;

				$cnt++;
			}

			$_colNames = implode( ", ", $colNameArr);
		}
		else
		{
			$cnt = 1;
		}

		$query = "SELECT $_colNames FROM {$this->tableName} $_where;";

		if( $cnt>0)
		{
			if( $this->read( $query))
			{
				logWrite( "INFO  " .__METHOD__. "( '$_colNames', '$_where') : Success => $query");

				return true;
			}
			else
			{
				logWrite( "ERROR " .__METHOD__. "( '$_colNames', '$_where') : Fail => $query");

				return false;
			}
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_colNames', '$_where') : $query");

			return false;
		}
	}

	function update( $_where = null)
	{
		if( $_where==null || $_where=='')
		{
			$PKName = $this->PKName;
			$PKVal  = $this->colArr[$this->PKName];

			if( $PKVal != null && $PKVal!='')
			{
				$_where = "WHERE $PKName='$PKVal'";
			}
		}

		$colNameArr = array();
		$cnt = 0;

		foreach( $this->colArr as $colName=>$colVal)
		{
			if( $colName!=$this->PKName && $colVal!=null && $colVal!='')
			{
				if( substr( $colVal,0,4)=='|FN|')
				{
					$colVal = str_replace( '|FN|','',$colVal);

					$colNameArr[] = "$colName=$colVal";
				}
				else
				{
					$colNameArr[] = "$colName='$colVal'";
				}

				$cnt++;
			}
		}

		if( $cnt>0 && $_where!=null && $_where!='')
		{
			$colNames = implode( ", ", $colNameArr);
			$query    = "UPDATE {$this->tableName} SET $colNames $_where;";

			if( $this->save( $query))
			{
				logWrite( "INFO  " .__METHOD__. "( '$_where') : Success => $query");

				return true;
			}
			else
			{
				logWrite( "ERROR " .__METHOD__. "( '$_where') : Fail => $query");

				return false;
			}
			return true;
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_where') : $query");

			return false;
		}

		logWrite( "ERROR " .__METHOD__. "( '$_where') : Fail => $query");

		return false;
	}

	function insert()
	{
		$colNameArr = array();
		$valArr     = array();
		$cnt        = 0;

		foreach( $this->colArr as $colName=>$colVal)
		{
			if( $colName!=$this->PKName && $colVal!=null && $colVal!='')
			{
				$colNameArr[] = "$colName";

				if( substr($colVal,0,4)=='|FN|')
				{
					$colVal = str_replace( '|FN|','',$colVal);

					$valArr[] = "$colVal";
				}
				else
				{
					$valArr[] = "'$colVal'";
				}

				$cnt++;
			}
		}

		if( $cnt>0)
		{
			$colNames = implode( ", ", $colNameArr);
			$vals     = implode( ", ", $valArr);
			$query    = "INSERT INTO {$this->tableName} ($colNames) VALUES ($vals);";

			if( $this->save( $query))
			{
				$last_id                     = $this->insert_id;
				$this->colArr[$this->PKName] = $last_id;

				logWrite( "INFO  " .__METHOD__. "() : Success => $last_id / $query");

				return true;
			}
			else
			{
				logWrite( "ERROR " .__METHOD__. "() : Fail => $query");

				return false;
			}
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "() : Fail => $query");

			return false;
		}
	}

	function delete( $_where = null)
	{
		if( $_where==null || $_where=='')
		{
			$PKName = $this->PKName;
			$PKVal  = $this->colArr[$this->PKName];

			if( $PKVal!=null && $PKVal!='')
			{
				$_where = "WHERE $PKName='$PKVal'";
			}
		}

		$query = "DELETE FROM {$this->tableName} $_where;";

		if( $_where!=null && $_where!='')
		{
			if( $this->save( $query))
			{
				logWrite( "INFO  " .__METHOD__. "( '$_where') : Success => $query");

				return true;
			}
			else
			{
				logWrite( "ERROR " .__METHOD__. "( '$_where') : Fail => $query");

				return false;
			}
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_where') : Fail => $query");

			return false;
		}
	}
}
?>