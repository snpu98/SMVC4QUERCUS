<?php
define( '__DOC_ROOT__'        , 'E:/SMVC4QUERCUS/WebContent/');
define( '__LIB__'             , __DOC_ROOT__.'_lib/');
define( '__START__'           , __DOC_ROOT__.'start.php');
define( '__CONFIG__'          , __LIB__     .'config.php');
define( '__DEFINE__'          , __LIB__     .'define.php');
define( '__CLASS_LOG__'       , __LIB__     .'class.Log.php');
define( '__CLASS_DATABASE__'  , __LIB__     .'class.Database.php');
define( '__CLASS_MODEL__'     , __LIB__     .'class.Model.php');
define( '__CLASS_CONTROLLER__', __LIB__     .'class.Controller.php');

//include_once(_START0_);
include_once( __CONFIG__);
include_once( __DEFINE__);
include_once( __CLASS_LOG__);
include_once( __CLASS_DATABASE__);
include_once( __CLASS_MODEL__);
include_once( __CLASS_CONTROLLER__);

session_start();
?>