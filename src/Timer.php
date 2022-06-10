<?php 
namespace Shift\Timer;

class Timer implements TimerInterface
{

  public function runningTime(): array
  {
    date_default_timezone_set('Africa/Kampala');
    $d = date('H:i:s'); // now
    $h = (int) date('H');

    return  [
      'now'=>$d,
      'hour'=>$h
    ];
  }

}