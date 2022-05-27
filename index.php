<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/connection.php";

// Router
use Steampixel\Route;
use Shift\Shift;


$shift = new Shift;
$payload = json_decode(file_get_contents('php://input'), true); 

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
 * Endpoints
 */

Route::add('/api/addworker', function() use($payload, $shift) {
  header('Content-Type: application/json');
  return addWorkerApi();  
}, 'post');

Route::add('/api/getshift', function() use($payload, $shift) {
  header('Content-Type: application/json');
  return getShiftApi();  
}, 'post');

Route::add('/api/startshift', function() use($payload, $shift) {
  header('Content-Type: application/json');
  return startShift();  
}, 'post');

Route::add('/api/gettime', function() use($payload, $shift) {
  // Count real time
  header('Content-Type: application/json');
  return getTime();  
}, 'post');

// Run the router
Route::run('/');
