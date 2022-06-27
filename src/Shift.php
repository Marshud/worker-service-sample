<?php
namespace Shift;
use Shift\Worker\Worker;
use Shift\Timer\Timer;

class Shift implements ShiftInterface {
  public $email;

  function __construct($email)
  {
    $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
  }

  /**
   * @param string $unique
   * @access private
   * 
   * A method to Check if the user shift exists already
   * 
   * @return bool
   */
  private function checkUserShift($unique) :bool
  {
    global $db;
    // To refactor with in that day
    $check = $db->where('date(db_timestamp) == curdate()')->where('unquie', $unique)->getValue(ONGOING_SHIFTS, 'count(*)');

    if($check > 0) {
      return true;
    } else {
      return false;
    }

  }

  /**
   * @param string $email
   * 
   * The method gets data about the shift of the current user 
   * 
   * @return associative array
   */
  public function getShift ($email): array
  {
    global $db;

    // Handle mails
    $worker = new Worker($this->email);
    $h = $worker->handleEmail($this->email);
    
    $clean_email = $h['clean'];
    $unquie = $h['unquie'];

    if($this->checkUserShift($unquie)) {
      $res = $db->where('email', $clean_email)->getOne(ONGOING_SHIFTS);
      return [
        'name' => $res['name'],
        'email' => $res['email'],
        'unquie' => $res['unquie'],
        'start' => $res['start'],
        'stop' => $res['stop'],
        'shift' => $res['shift'],
        'status' => true
      ];
    } else {
      return [
        'status'=> false
      ];
    }
  }

  /**
   * A public function to start a shift
   * @return bool
   */
  public function startShift(): bool
  {
    global $db; 
    $t = $this->determineShift();
    $shift = $t['n'];
    $start = $t['start'];
    $stop = $t['stop'];

    $worker = new Worker($this->email);
    $h = $worker->handleEmail($this->email);
    $clean_email = $h['clean'];
    $unique = $h['unique'];

    $i = $db->where('email', $clean_email)->getOne(WORKER_DATA);
    $name = $i['name'];

    $ch = $this->checkUserShift($unique);
    if($ch) {
      // echo "User has already worked the day's shift";
      return false;
    } else {
      $data = [
        'name' => $name,
        'email' => $clean_email,
        'unquie'=> $unique, // to replace with unique
        'shift'=>$shift,
        'start'=>$start,
        'stop'=>$stop
      ];

      $id = $db->insert(ONGOING_SHIFTS, $data);

      if($id) {
        return true;
      } else {
        // echo "There was an error: ".$db->getLastError();
        return false;
      }
    }
  }

  /**
   * Public method to determine the shift
   * @return array
   */
  public function determineShift(): array
  {
    $t = new Timer;
    $running  = $t->runningTime();
    $hour = $running['hour'];
    $firstshift = range(0,7);
    $secondshift = range(8,15);

    $shift = [];
    if(in_array($hour, $firstshift)) {
      $shift['n'] = 1;
      $shift['start'] = 0;
      $shift['stop'] = 7;
    } elseif (in_array($hour, $secondshift)) {
      $shift['n'] = 2;
      $shift['start'] = 8;
      $shift['stop'] = 15;
    } else {
      $shift['n'] = 3;
      $shift['start'] = 16;
      $shift['stop'] = 23;
    }

    return $shift;
  }

}