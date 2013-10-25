<?php
// Service information
define( '__SERVICE_NAME__', 'Semi MVC Prototype');
define( '__SERVICE_HOST__', 'http://localhost:9080/smvc/');

// Database information
define( '__DB_HOST__', 'localhost');
define( '__DB_NAME__', 'db_test');
define( '__DB_USER__', 'root');
define( '__DB_PASS__', 'snpu04');
define( '__DB_CHAR__', 'utf8');

// Log information
$Ymd = date("Ymd");
define( '__LOG_MODE__' , true); //true|false
define( '__LOG_PATH__' , __DOC_ROOT__ . "_log/log_$Ymd.txt");

// Path information
define( '__CONTROLLER_BACKEND__', __DOC_ROOT__ . "__controller_backend/");
define( '__CONTROLLER_UI__'     , __DOC_ROOT__ . "__controller_ui/");
define( '__MODEL__'             , __DOC_ROOT__ . "__model/");

// URL information
define( '__URL_CTL_BACKEND__', "__controller_backend/");
define( '__URL_CTL_UI__'     , "__controller_ui/");
define( '__CSS__'            , "css/");
define( '__IMG__'            , "img/");
define( '__JS__'             , "js/");
?>