<?php
  require_once("DbManager.php");
  require_once("Items.php");

  class Items{
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
      $items = array();

      try{
         $this->db_handle->beginTransaction();

         $query = "select * from items limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $items = array_map(function($item_properties){
                      return Item::make($item_properties)->getProperties();
                   }, $result);


         $this->db_handle->commit();
      }catch(PDOException $e){  }

     return $items;
    }

    /*
      get events by no criteria
      return event objects in an array
    */
    public function getForUser($user_id, $offset=0, $limit = 25){
      $items = array();

      try{
         $this->db_handle->beginTransaction();

         $query = "select * from items where user_id = ? limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(array($user_id)); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $items = array_map(function($item_properties){
                      return Item::make(array('item'=> $item_properties))->getProperties();
                   }, $result);


         $this->db_handle->commit();
      }catch(PDOException $e){  }

     return $items;
    }


    /*
      get events by no criteria
      return event objects in an array
    */
    public function getExpenseItems($expense_id, $offset = 0, $limit = 25){
      $items = array();

      try{
         $this->db_handle->beginTransaction();

         $query = "select * from items where expense_id = ? limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(array($expense_id)); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $items = array_map(function($item_properties){
                      return Expense::make(array('item'=> $item_properties))->getProperties();
                   }, $result);


         $this->db_handle->commit();
      }catch(PDOException $e){  }

     return $items;
    }

    //set db_obj to null
    public function __destruct(){
      $this->db_handler = null;
    }

  }//end of class


?>
