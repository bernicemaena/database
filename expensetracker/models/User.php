<?php
  
  include_once("SocialEntity.php");
  include_once("DbManager.php");
  
  /*
    contain data that has to do with a user.. 
    acts as the superclass for user-related classes
  */
  class User extends SocialEntity{
    private $password = "", $about_me = "", $earning = 0.0;
    private $earning_for = 'MONTH', $planning_for = 'MONTHLY';
    private $items = array();

    private $db_handle = NULL;

  
    // set the default values for the object variables
    public function __construct($name = "", $id = "", $password = ""){
      	$this->setName($name);
        $this->setID($id);
        $this->setPassword ($password);
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
    public function setAboutMe($abm){
      	$this->about_me = $abm;
      	return $this;
    }

    // update the object's about me property
    public function setEarning($earning){
        $this->earning = $earning;
        return $this;
    }

    // update the object's about me property
    public function setEarningFor($earning_for){
        $this->earning_for = $earning_for;
        return $this;
    }
    
    // update the object's about me property
    public function setPlanningFor($planning_for){
        $this->planning_for = $planning_for;
        return $this;
    }
    
    // update the object's about me property
    public function setItems($items){
        $this->items = $items;
        return $this;
    }


    /** 
        the getter methods
    **/


    // get the object's about me property
    public function getAboutMe(){
      	return $this->about_me;
    }

    // get the object's about me property
    public function getEarning(){
        return $this->earning;
    }
    
    // get the object's about me property
    public function getEarningFor(){
        return $this->earning_for;
    }

    // get the object's about me property
    public function getPlanningFor(){
        return $this->planning_for;
    }

    // get the object's about me property
    public function getItems(){
        return $this->items;
    }




    // add an event to the database
    public function save(){
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "insert into users "
                   ." (name, password, emailaddress, earning, earning_for, planning_for) "
                   ." values(?,?, ?, ?, ?, ?)";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getName(), 
                         sha1($this->getPassword()), 
                          $this->getContacts(),
                          $this->getEarning(), 
                          $this->getEarningFor(),
                          $this->getPlanningFor()
                        );
         
         // actual save
         $stmt->execute($db_data);
         
         // get the saved id
         $event_id = $this->db_handle->lastInsertId();


          $this->db_handle->commit();
        }catch(PDOException $e){
           echo($e->getMessage());
           $this->db_handle->rollBack();
        }

        return $this;
    }

    


    // update an event
    public function setDbEarning(){
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "update users set earning = ?, earning_for = ? where id = ?";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getEarning(), 
                          $this->getEarningFor(), 
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
    

    // update an event
    public function update(){
      try{
         $this->db_handle->beginTransaction();

         //insert basic event details
         $query = "update users set "
                   ." name = ?, emailaddress = ?, earning = ?, earning_for = ?, planning_for = ? "
                   ." where id = ?";

         $stmt = $this->db_handle->prepare($query);

         $db_data = array($this->getName(), 
                          $this->getContacts(), 
                          $this->getEarning(),
                          $this->getEarningFor(), 
                          $this->getPlanningFor(),
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
    
    // check if the user account exists
    public function accountExists(){
      $exists = false;
        $query = "select * from users where name = ? and password = ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getName(), sha1($this->getPassword())));

        if($stmt->rowCount() > 0){
           $exists = true;
        }

        return $exists;     
    }

    // check if the name of this user is used
    public function nameUSed(){
      $exists = false;
        $query = "select * from users where name = ?";


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
        $query = "select * from users where id = ?";


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
        $query = "select id from users where name like ?";


        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($this->getName()));
        
        $id = $stmt->fetch()['id'];

        return $id;
    }


    // get event properties
    public function getProperties(){
       $properties = array();

       $properties['id'] = $this->getID();
       $properties['name'] = $this->getName();
       $properties['contacts'] = $this->getContacts();
       $properties['password'] = $this->getPassword();
       $properties['earning'] = $this->getEarning();
       $properties['earning_for'] = $this->getEarningFor();
       $properties['planning_for'] = $this->getPlanningFor();
       

       return $properties;
    }

    // set user data from db
    public function setProperties(){
      $user = new User();
      $combined_properties = array('user'=> NULL, 'items'=> NULL);

      try{

        //get primary event details like name etc
        $primary_query = "select * from users where id = ?";
        $primary_stmt = $this->db_handle->prepare($primary_query);
        $primary_stmt->execute(array($this->getID()));
        $primary_results = $primary_stmt->fetch(PDO::FETCH_ASSOC);
         

        if(is_array($primary_results)){

          //get event hosts
          $items_query = "select * from items where user_id = ?";
          $items_stmt = $this->db_handle->prepare($items_query);
          $items_stmt->execute(array($this->getID()));
          $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

          // combine all these database properties in one array
          $combined_properties['user'] = $primary_results;
          $combined_properties['items'] = $items;

          //pass the array to make functon which will create a new event object
          $user = User::make($combined_properties);
        }

        }catch(PDOException $e){
          echo($e->getMessage());
        }

        return $user;
      }


      // creates an event instance given the events properties
      public static function make($combined_properties){
        $this_user = new User();
        $item = new Item();

        $user = $combined_properties['user'];
        $items = $combined_properties['items'];

         $this_user->setID($user['id'])
              ->setName($user['name'])
              ->setPassword($user['password'])
              ->setEarning($user['earning'])
              ->setEarningFor($user['earning_for'])
              ->setPlanningFor($user['planning_for']);

         // set items
         $temp_items = array();
         if($items != null){
            foreach ($items as $fore_item) { //var_dump($fore_item);
              $fe_temp_item = Item::make(array('item'=> $fore_item));
              array_push($temp_items, $fe_temp_item);
            }
         }
         

         //set class prop
         $this_user->setItems($temp_items);

         return $this_user;
    }


    
    // delete a user given their name
    public function delete(){
       $query = "delete from users where id = ?";
       $stmt = $this->db_handle->prepare($query);
       $stmt->execute(array($this->getID()));
    }
    
 



      

    // free resources that this object is using
    public function __destruct(){ }


  }// end of class


?>