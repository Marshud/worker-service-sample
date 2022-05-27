<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$obj = new stdClass();

function getShiftApi() {
  global $payload, $shift, $obj; 
  $email = $payload['email'];

  $obj->status = $shift->getShift($email);

  return json_encode($obj);
}

function addWorkerApi() {
  global $payload, $shift, $obj; 

  $name = $payload['name'];
  $email = $payload['email'];

  $obj->status = $shift->addWorker($name, $email);

  return json_encode($obj);
}

function startShift() {
  global $payload, $shift, $obj; 

  $email = $payload['email'];

  $obj->status = $shift->startShift($email);

  return json_encode($obj);
}

function getTime() {
  global $shift, $obj;

  $obj->time = $shift->runningTime();

  return json_encode($obj);
}



