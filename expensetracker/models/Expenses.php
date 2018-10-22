<?php
  require_once("DbManager.php");
  require_once("Expense.php");

  class Expenses{
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

         $query = "select * from expenses limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $items = array_map(function($expense_properties){
                      
                      $items = (new Items())->getExpenseItems($expense_properties['id']);

                      return Expense::make(
                         array('expense'=> $expense_properties, 'items'=> $items)
                       )->getProperties();
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

         $query = "select * from expenses where user_id = ? limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query);
         $stmt->execute(array($user_id)); 
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


         $items = array_map(function($expense_properties){
                      // echo("get items of an expense "+ $expense_properties['id']);
                      $expense_items = $this->getExpenseItems($expense_properties['id']);
                      // var_dump($expense_items);
                      return Expense::make(array(
                        'expense'=> $expense_properties, 
                        'items'=> $expense_items
                      ))->getProperties();
                   }, $result);

         $this->db_handle->commit();
      }catch(PDOException $e){  }

     return $items;
    }


    public function getExpenseItems($expense_id, $offset = 0, $limit = 25){
      $items = array(); // echo("in getExpenseItems ". $expense_id ."<br>");

      try{

         $query = "select * from items where expense_id = ? limit $limit offset $offset";
         $stmt = $this->db_handle->prepare($query); // echo("after execute ". $expense_id ."<br>");
         $stmt->execute(array($expense_id));  
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         $items = $result;
         // var_dump($result);

      }catch(PDOException $e){ 
         echo($e->getMessage());
      }

     return $items;
    }


    //set db_obj to null
    public function __destruct(){
      $this->db_handler = null;
    }

  }//end of class


?>
