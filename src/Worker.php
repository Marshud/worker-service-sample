<?php 
namespace Shift\Worker;

use phpDocumentor\Reflection\Types\Boolean;

class Worker implements WorkerInterface{
  public string $name;
  public string $email;
  public string $unique; 

  function __construct(string $name, string $email)
  {
    $this->name = $name;
    $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $this->unique = $this->makeUnique($email);
  }

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

  private function makeUnique($email) :string
  {
    return md5(trim($email));
  }

  public function handleEmail($email) :array
  {
    global $db;
    $clean = $db->escape($email);

    return [
      'clean' => $clean,
      'unique' => $this->unique,
    ];
  }
}
