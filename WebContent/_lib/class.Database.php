<?php
class Database extends mysqli
{
	private $row;
	private $rows;

	function __construct( $_host=__DB_HOST__, $_user=__DB_USER__, $_pass=__DB_PASS__, $_name=__DB_NAME__)
	{
		parent::__construct( $_host, $_user, $_pass);

		if( $this->connect_errno)
		{
			logWrite( "ERROR " .__METHOD__. "() : {$this->connect_error}");
		}
		else
		{
			if( $this->select_db( $_name))
			{
				logWrite( "INFO  " .__METHOD__. "() : Database connection success.");
			}
			else
			{
				logWrite( "ERROR " .__METHOD__. "() : {$this->error}");
			}
		}
	}

	function __destruct()
	{
		$this->close();
	}

	function clear()
	{
		$this->row  = array();
		$this->rows = array();
	}

	function read( $_query)
	{
		$this->query( "SET NAMES '".__DB_CHAR__."';");

		$this->clear();

		if( $result = $this->query( $_query))
		{
			while( $row = $result->fetch_assoc())
			{
				if( $row!=null or $row!='')
				{
					$this->rows[] = $row;
				}
			}

			if( is_array( $this->rows))
			{
				$this->row = $this->rows[0];
			}

			logWrite( "QUERY " .__METHOD__. "( '$_query') : $_query");

			return true;
		}
		else
		{
			$this->row  = null;
			$this->rows = null;

			logWrite( "ERROR " .__METHOD__. "( '$_query') : {$this->error}");

			return false;
		}
	}

	function save( $_query)
	{
		$this->query( "SET NAMES '".__DB_CHAR__."';" );

		if( $result = $this->query( $_query)){
			logWrite( "INFO  " .__METHOD__. "( '$_query') : Success.");

			return true;
		}
		else
		{
			logWrite( "ERROR " .__METHOD__. "( '$_query') : {$this->error}");

			return false;
		}
	}

	function getRow()
	{
		if( $this->row==null)
		{
			logWrite( "WARN  " .__METHOD__. "() : Row is null.");
		}

		return $this->row;
	}

	function getRows()
	{
		if( $this->rows==null)
		{
			logWrite( "WARN  " .__METHOD__. "() : Rows is null.");
		}

		return $this->rows;
	}

	function getJSON()
	{
		$rows = $this->getRows();

		if( $rows!=null)
		{
			foreach( $rows as $row)
			{
				if( is_array( $row))
				{
					foreach( $row as $colName=>$colVal)
					{
						if( !is_numeric( $colName))
						{
							$colVal   = str_replace( "'", "\\'", $colVal);
							$colVal   = str_replace( '"',  '\"', $colVal);
							$colArr[] = "$colName:'$colVal'";
						}
					}

					$rowArr[] = '{' . implode(',', $colArr) . '}';
					$colArr   = null;
				}
			}

			$JSON = '{' . implode(',', $rowArr) . '}';

			logWrite( "INFO  " .__METHOD__. "() : Success => $JSON");

			return $JSON;
		}
		else
		{
			logWrite( "WARN " .__METHOD__. "() : Rows is null.");

			return null;
		}
	}

	function getXML()
	{
		$rows = $this->getRows();

		if( $rows!=null)
		{
			$XML = '<root>';

			foreach( $this->rows as $row)
			{
				if( is_array( $row))
				{
					$XML .= '<row>';

					foreach( $row as $colName => $colVal)
					{
						if( !is_numeric( $colName))
						{
							$colVal = str_replace( "'", "\\'", $colVal);
							$colVal = str_replace( '"',  '\"', $colVal);
							$XML   .= "<$colName>$col</$colName>";
						}
					}

					$XML .= '</row>';
				}
			}

			$XML .= "</root>";

			logWrite( "WARN  " .__METHOD__. "() : Success => $XML");

			return $XML;
		}
		else
		{
			logWrite( "WARN  " .__METHOD__. "() : Rows is null.");

			return null;
		}
	}
}
?>