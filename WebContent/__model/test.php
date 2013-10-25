<?php
class test
{
	private $model;

	function __construct()
	{
		$this->model = new Model("t_test");
	}

	function isKey( $_key)
	{
		$where = "WHERE f_key='$_key'";

		$this->model->select( 'f_idx', $where);

		return is_array( $this->model->getRow());
	}

	function get( $_key = null, $_offset='0', $_limit = null)
	{
		$where = null;

		if( $_key!=null)
		{
			$where  = "WHERE f_key='$_key' ";
		}

		if( $_limit!=null)
		{
			$where .= "LIMIT $_offset, $_limit";
		}

		if( $this->model->select( null, $where))
		{
			return $this->model->getRows();
		}
		else
		{
			return false;
		}
	}

	function add( $_key, $_val)
	{
		$this->model->setColArr( array( 'f_key'=>$_key, 'f_val'=>$_val));

		return $this->model->insert();
	}

	function mod( $_idx, $_key, $_val)
	{
		$this->model->setPKVal( $_idx);

		$this->model->setColArr( array( 'f_key'=>$_key, 'f_val'=>$_val));

		return $this->model->update();
	}

	function del( $_idx)
	{
		$this->model->setPKVal( $_idx);

		return $this->model->delete();
	}
}
?>