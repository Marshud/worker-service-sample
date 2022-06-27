<?php 
namespace Shift;

interface ShiftInterface
{
  public function determineShift();

  public function startShift();

  public function getShift($email);

}