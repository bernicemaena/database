<?php
  include_once("DbManager.php");
   
  class Expense{
    private $id = NULL, $name, $user=array('id'=> NULL);
    private $items = array();
    private $dates = array('start_from'=> NULL, 'end_at'=> NULL);

    private $db_handle = NULL;

    public function __construct(){
      $db_manager = new DbManager();
      $this->db_handle = $db_manager->getHandle();
    }


    /** 
        @@Mutators
    **/

    // update the object's name property
    public function setName($name){
      	$this->name = $name;
      	return $this;
    }




    // update the object's id property
    public function setID($id){
      	$this->id = $id;
      	return $this;
    }


     public function setUser($user){
        $this->user = $user;
        return $this;
    }
   
    // update the object's about me property
    public function setItems($items){
        $this->items = $items;
        return $this;
    }
  
    // update the object's about me property
    public function setDates($dates){
        $this->dates = $dates;
        return $this;
    }


    /** 
      @accessors
    **/

    // get the object's name property
    public function getName(){
   	  return $this->name;
    }

    // get the object's id property
    public function getID(){
   	  return $this->id;
    }

    // get the object's about me property
    public function getItems(){
        return $this->items;
    }

    // get the object's about me property
    public function getDates(){
        return $this->dates;
    }
    public function getUser(){
        return $this->user;
    }



    // add an event to the database
    public function save(){
      $save_data = array('saved'=> false, 'id'=> NULL);
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "insert into expenses (name, user_id, start_from, end_at) values(?, ?, ?, ?)";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getName(),
                          $this->getUser()['id'], 
                          $this->getDates()['start_from'],
                          $this->getDates()['end_at']
                        );
         
         // actual save
         $stmt->execute($db_data);
         
         if($stmt->rowCount() > 0){           
           // get the saved id
           $event_id = $this->db_handle->lastInsertId();
           
           $save_data['saved'] = true;
           $save_data['id'] = $event_id;
         }


          $this->db_handle->commit();
        }catch(PDOException $e){
           echo($e->getMessage());
           $this->db_handle->rollBack();
        }

        return $save_data;
    }


    // check if the name of this user is used
    public function nameUSed(){
      $exists = false;
        $query = "select * from expenses where name = ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getName()));

        if($stmt->rowCount() > 0){
           $exists = true;
        }

        return $exists;     
    }

    // check if this user exists by id
    public function exists(){
      $exists = false;
        $query = "select * from expenses where id = ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getID()));

        if($stmt->rowCount()>0){
           $exists = true;
        }

        return $exists;
    }


    // check if this user exists by id
    public function findID(){
      $id = NULL;
        $query = "select id from expenses where name like ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getName()));
        
        $id = $stmt->fetch()['id'];

        return $id;
    }


    // get event properties
    public function getProperties(){
       $properties = array();

       $properties['id'] = $this->getID();
       $properties['user'] = $this->getUser();
       $properties['name'] = $this->getName();
       $properties['dates'] = $this->getDates();
       $properties['items'] = $this->getItems();

       return $properties;
    }

    // set user data from db
    public function setProperties(){
      $expense = new Expense();
      $combined_properties = array('expense'=> NULL, 'items'=> NULL);

      try{

        //get primary event details like name etc
        $primary_query = "select * from expenses where id = ?";
        $primary_stmt = $this->db_handle->prepare($primary_query);
        $primary_stmt->execute(array($this->getID()));
        $primary_results = $primary_stmt->fetch(PDO::FETCH_ASSOC);
      

        if(is_array($primary_results)){

          //get event hosts
          $items_query = "select * from items where expense_id = ?";
          $items_stmt = $this->db_handle->prepare($items_query);
          $items_stmt->execute(array($this->getID()));
          $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

          // combine all these database properties in one array
          $combined_properties['expense'] = $primary_results;
          $combined_properties['items'] = $items; 

          //pass the array to make functon which will create a new event object
          $expense = Expense::make($combined_properties);
        }

        }catch(PDOException $e){
          echo($e->getMessage());
        }

        return $expense;
      }


      // creates an event instance given the events properties
      public static function make($combined_properties){
        $this_expense = new Expense();
        $item = new Item();

        $expense = $combined_properties['expense'];
        $items = $combined_properties['items'];

        // echo("got items");
        // var_dump($items);

         $this_expense->setID($expense['id'])
              ->setName($expense['name'])
              ->setUser(array('id'=> $expense['user_id']))
              ->setDates(array(
              	 'start_from'=> $expense['start_from'], 'end_at'=> $expense['end_at']
              	));

         // set items
         $temp_items = array();
         if($items != null){
            foreach ($items as $fore_item) { //var_dump($fore_item);
              $fe_temp_item = Item::make(array('item'=> $fore_item))->getProperties();
              array_push($temp_items, $fe_temp_item);
            }
         }
         

         //set class prop
         $this_expense->setItems($temp_items);

         return $this_expense;
    }
        



    // delete a user given their name
    public function delete(){
       $query = "delete from expenses where id = ?";
       $stmt = $this->db_handle->prepare($query);
       $stmt->execute(array($this->getID()));
    }
    

     public function __destruct(){ }

  }// end of class

?>