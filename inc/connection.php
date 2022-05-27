<?php 
  require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

  // DB Constants
  define('DBHOST', 'mysql');
  define('DBUSER', 'root');
  define('DBPASS', 'password');
  define('DBNAME', 'workshift');
  define('U', 'unquie');

  // Table constants
  define('WORKER_DATA', 'tbl_workersdata');
  define('ONGOING_SHIFTS', 'tbl_ongoing_workshifts');

  $db = new MysqliDb (DBHOST, DBUSER, DBPASS, DBNAME);
  
  include 'Shift.php';