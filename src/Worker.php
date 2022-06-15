<?php 
namespace Shift\Worker;

use phpDocumentor\Reflection\Types\Boolean;

class Worker implements WorkerInterface{
  public string $name;
  public string $email;
  public string $unique; 

  /**
   * @param string name
   * @param string email
   * Defines the structure of an object 
   */
  function __construct(string $name, string $email)
  {
    $this->name = $name;
    $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $this->unique = $this->makeUnique($email);
  }

  /**
   * Public method adds a worker to the datase
   * @return bool
   */
  public function addWorker() :bool
  {
    global $db;

    $data = [
      'name'=> $this->name,
      'email'=>$this->email,
      'unquie' => $this->unique
    ];

    $check = $db->where('email', $this->email)->getValue(WORKER_DATA, "COUNT(*)");

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
   * Helper method in a class that makes the email unquie
   * @return string
   */
  private function makeUnique($email) :string
  {
    return md5(trim($email));
  }

  /**
   * Public function for handling emails
   */
  public function handleEmail($email) :array
  {
    global $db;
    $clean = $db->escape($email);
    $unique = $this->makeUnique($email);

    return [
      'clean' => $clean,
      'unique' => $unique,
    ];
  }
}
