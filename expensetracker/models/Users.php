<?php
  require_once("DbManager.php");
  require_once("User.php");

  class Users{
    private $db_handle = null;

    public function __construct(){
      $db_manager = new DbManager();
      $this->db_handle = $db_manager->getHandle(); 
    }

    /*
      get events by no criteria
      return event objects in an array
    */
    public function getAll($offset=0, $limit = 25){
      $users = array();

      try{
         $this->db_handle->beginTransaction();

         $query = "select * from signup limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $users = array_map(function($db_event){
                      $user_properties = array('user'=> $db_event, 'items'=> NULL);
                      return User::make($user_properties)->getProperties();
                   }, $result);


         $this->db_handle->commit();
      }catch(PDOException $e){  }

     return $users;
    }


    //set db_obj to null
    public function __destruct(){
      $this->db_handler = null;
    }

  }//end of class


?>
