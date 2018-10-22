<?php
  
  include_once("SocialEntity.php");
  include_once("DbManager.php");
  
  /*
    contain data that has to do with a user.. 
    acts as the superclass for user-related classes
  */
  class Item extends SocialEntity{
    //private $id = NULL;
    private $frequency = 'MONTHLY', $priority = 'MEDIUM', $cost = 0.0;
    private $user = array('id'=> NULL);
    private $expense = array('id'=> NULL);

    private $db_handle = NULL;


    // set the default values for the object variables
    public function __construct($name = "", $id = "", $password = ""){
      	$this->setName($name);
        $this->setID($id);
        $this->setPassword($password);
   	    $this->type = "user";
   	    $this->location = NULL;

        $db_manager = new DbManager();
        $this->db_handle = $db_manager->getHandle();
    }  

    // 
    public function __toString(){
       return "id :". $this->getID() ." , name :". $this->getName();
    } 
      
      
    /**
        setter methods
    **/

    // update the object's about me property
    public function setCost($cost){
      	$this->cost = $cost;
      	return $this;
    }

    // update the object's about me property
    public function setPriority($prio){
        $this->priority = $prio;
        return $this;
    }

    // update the object's about me property
    public function setFrequency($freq){
        $this->frequency = $freq;
        return $this;
    }

    // update the object's about me property
    public function setUser($user){
        $this->user = $user;
        return $this;
    }
    
    // update the object's about me property
    public function setExpense($expense){
        $this->expense = $expense;
        return $this;
    }

    /** 
        the getter methods
    **/


    // get the object's about me property
    public function getCost(){
      	return $this->cost;
    }

    // get the object's about me property
    public function getPriority(){
        return $this->priority;
    }

    // get the object's about me property
    public function getFrequency(){
        return $this->frequency;
    }
    
    // get the object's about me property
    public function getUser(){
        return $this->user;
    }
  
    // get the object's about me property
    public function getExpense(){
        return $this->expense;
    }





    // add an item to the database
    public function save(){
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "insert into items (name, cost, frequency, priority, user_id, expense_id) "
                  ." values(?, ?, ?, ?, ?, ?)";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getName(), $this->getCost(), 
                          $this->getFrequency(), $this->getPriority(),  
                          $this->getUser()['id'], $this->getExpense()['id']
                        );

         $stmt->execute($db_data);

         $event_id = $this->db_handle->lastInsertId();


          $this->db_handle->commit();
        }catch(PDOException $e){
           echo($e->getMessage());
           $this->db_handle->rollBack();
        }

        return $this;
    }

        



    // update an item
    public function update(){
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "update items set name = ?, frequency = ?, priority = ?, cost = ? where id = ?";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getName(), $this->getPriority(), 
                          $this->getFrequency(), $this->getCost(),
                          $this->getID()
                       );

         $stmt->execute($db_data);

         $this->db_handle->commit();
      }catch(PDOException $e){
         echo($e->getMessage());
         $this->db_handle->rollBack();
      }

      return $this;
    }
    
    // check if the name of this user is used
    public function nameUSed(){
      $exists = false;
        $query = "select * from items where name = ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getName()));

        if($stmt->rowCount()>0){
           $exists = true;
        }

        return $exists;     
    }

    // check if this item exists by id
    public function exists(){
      $exists = false;
        $query = "select * from items where id = ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getID()));

        if($stmt->rowCount()>0){
           $exists = true;
        }

        return $exists;
    }


    // get item properties
    public function getProperties(){
       $properties = array();

       $properties['id'] = $this->getID();
       $properties['name'] = $this->getName();
       $properties['frequency'] = $this->getFrequency();
       $properties['priority'] = $this->getPriority();
       $properties['cost'] = $this->getCost();
       $properties['user'] = $this->getUser();
       $properties['expense'] = $this->getExpense();

       return $properties;
    }

    // set user data from db
    public function setProperties(){
      $item = new Item();
      $combined_properties = array();

      try{

        //get primary event details like name etc
        $primary_query = "select * from items where id = ?";
        $primary_stmt = $this->db_handle->prepare($primary_query);
        $primary_stmt->execute(array($this->getID()));
        $primary_results = $primary_stmt->fetch(PDO::FETCH_ASSOC);
        
        if(is_array($primary_results)){
          $combined_properties['item'] = $primary_results;
          //pass the array to make functon which will create a new event object
          $item = Item::make($combined_properties);
        }

        }catch(PDOException $e){
          echo($e->getMessage());
        }

        return $item;
      }


      // creates an event instance given the events properties
      public static function make($combined_properties){
        $this_item = new Item();

        $item = $combined_properties['item'];

         $this_item->setID($item['id'])
              ->setName($item['name'])
              ->setCost($item['cost'])
              ->setPriority($item['priority'])
              ->setFrequency($item['frequency'])
              ->setUser(array('id'=> $item['user_id']))
              ->setExpense(array('id'=> $item['expense_id']));
          

         return $this_item;
    }


    
    // delete an items given their id
    public function delete(){
       $query = "delete from items where id = ?";
       $stmt = $this->db_handle->prepare($query);
       $stmt->execute(array($this->getID()));
    }
    
 



      

    // free resources that this object is using
    public function __destruct(){ }


  }// end of class


?>