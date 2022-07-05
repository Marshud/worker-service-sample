<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/connection.php";

// Router
use Steampixel\Route;
use Shift\Shift;
use Shift\Timer\Timer;
use Shift\Worker\Worker;


// $shift = new Shift;
$payload = json_decode(file_get_contents('php://input'), true); 
$obj = new stdClass();

include $_SERVER['DOCUMENT_ROOT']."/api/index.php";

// Test route
Route::add('/', function() {
  include 'home.php';
});

// Test route
Route::add('/test', function() {
  // include 'home.php';
  echo phpinfo();
});

// Test route
Route::add('/viewshift', function() {
  include 'shiftstatus.php';
});


/**
 * API Endpoints
 */

Route::add('/api/startshift', function() use($payload, $obj) {
  header('Content-Type: application/json');
  $email = $payload['email'];
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $shift = new Shift($email);
    $obj->status = $shift->startShift();
  } else {
    $obj->status = false;
  }

  return json_encode($obj);
}, 'post');

Route::add('/api/addworker', function() use($payload, $obj) {
  header('Content-Type: application/json');
  $email = $payload['email'];
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $name = $payload['name'];
    $worker = new Worker($email);
    $obj->status = $worker->addWorker($name);
  } else {
    $obj->status = false;
  }
  return json_encode($obj);  
}, 'post');

Route::add('/api/getshift', function() use($payload, $obj) {
  header('Content-Type: application/json');
  $email = $payload['email'];
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $name = $payload['name'];
    $shift = new Shift($email);
    $obj->status = $shift->getShift($name);
  } else {
    $obj->status = false;
  }
  return json_encode($obj); 
}, 'post');



Route::add('/api/gettime', function() use($payload, $obj) {
  // Count real time
  $timer = new Timer;
  header('Content-Type: application/json');
  $obj->time = $timer->runningTime();
  return json_encode($obj);  
}, 'post');

// Run the router
Route::run('/');
