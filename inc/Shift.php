<?php 
namespace Shift;

use DateTime;

class Shift 
{
  // check whether there's a running user shift
  // check whether the user doesn't have two shifts on the same day
  // work is done in shifts
  // There are only 3 shifts

  /**
   *  @param string $name
   *  @param string $email 
   *  @param int $shift
   * 
   * The method adds a shift to a database when someone starts their shift
   * 
   * @return bool
   *  
   */
  public function addWorker($name, $email) {
    global $db;

    $clean_name = $db->escape(trim($name));
    $h = $this->handleMails($email);
    $clean_email = $h['clean'];
    $unquie = $h['unquie'];
    
    $data = [
      'name'=> $clean_name,
      'email'=>$clean_email,
      'unquie' => $unquie
    ];

    $check = $db->where('email', $clean_email)->getValue(WORKER_DATA, "COUNT(*)");

    if($check > 0) {
      echo "User already exists";
      return false;
    } else {
      $id = $db->insert(WORKER_DATA, $data); 
      if($id) {
        return true;
      } else {
        echo $db->getLastError();
        return false;
      }
    }

  }


  /**
   * @param string $email
   * 
   * The method gets data about the shift of the current user 
   * 
   * @return associative array
   */
  public function getShift ($email) {
    global $db;

    // Handle mails
    $h = $this->handleMails($email);
    
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
      return false;
    }

  }

  /**
   * @param string $unique
   * @access private
   * 
   * A method to Check if the user shift exists already
   * 
   * @return bool
   */
  private function checkUserShift($unquie) {
    global $db;
    // To refactor with in that day
    $check = $db->where('unquie', $unquie)->getValue(ONGOING_SHIFTS, 'count(*)');

    if($check > 0) {
      return true;
    } else {
      return false;
    }

  }

  /**
   * @param string $email
   * @access private
   * A helper function to sanitize and handle emails 
   * calculates $unique value
   * 
   * @return array 
   * 
   */
  private function handleMails($email) {
    global $db;
    $clean_email = $db->escape(trim($email));
    $unquie = md5($clean_email);

    return [
      'clean' => $clean_email,
      'unquie' => $unquie
    ];
  }

  /**
   * @param string $email
   * @param int $shift
   * 
   * Function that starts a shift when someone starts a shift
   * 
   * @return bool
   * 
   */
  public function startShift($email) {
    global $db; 
    $t = $this->determineShift();
    $shift = $t['n'];
    $start = $t['start'];
    $stop = $t['stop'];

    $h = $this->handleMails($email);
    $clean_email = $h['clean'];
    $unquie = $h['unquie'];

    $i = $db->where('email', $clean_email)->getOne(WORKER_DATA);
    $name = $i['name'];

    $ch = $this->checkUserShift($unquie);
    if($ch) {
      // echo "User has already worked the day's shift";
      return false;
    } else {
      $data = [
        'name' => $name,
        'email' => $clean_email,
        'unquie'=> $unquie, // to replace with unique
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
   * A public method to return the running time
   * 
   * @return string
   */
  public function runningTime() {
    date_default_timezone_set('Africa/Kampala');
    $d = date('H:i:s'); // now
    $h = (int) date('H');

    return  [
      'now'=>$d,
      'hour'=>$h
    ];
  }

  /**
   * A public method to determing the shift 
   * @return array
   */
  public function determineShift() {
    date_default_timezone_set('Africa/Kampala');
    $hour = (int) date('H');
    
    $firstshift = range(0,7);
    $secondshift = range(8,15);
    $thirdshift = range(16, 23);

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
