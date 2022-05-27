<?php 
declare(strict_types=1);
use Shift\Shift;
use PHPUnit\Framework\TestCase;

final class determineTest extends TestCase {

  public function setUp() : void
  {
    $this->Shift = new Shift;
  }

  public function testDetermineShift() {
    $shift = $this->Shift->determineShift();
//       [$shift['n'], $shift['start'], $shift['stop']],
    $this->assertEquals(
      $shift,
      ['n'=>3, 'start'=>16, 'stop'=>23]
    );
  }
}