<?php 
declare(strict_types=1);
use Shift\Shift;
use PHPUnit\Framework\TestCase;

final class runningTimeTest extends TestCase {
  public function setUp() : void
  {
    $this->Shift = new Shift;
  }

  public function testrunningTime() {
    date_default_timezone_set('Africa/Kampala');
    $d = date('H:i:s'); // now
    $h = (int) date('H');
    $f = $this->Shift->runningTime();

    $this->assertEquals(
      [$f['now'], $f['hour']],
      [$d, $h]
    );
  }
}