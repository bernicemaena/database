<?php
   
   // to use sessions
   // session_start();

   // include 'file.php';
   // require 'file2.php';

   include_once('file.php');
   require_once('file2.php');

   // variables
   $name = 'bernice';

   // output
   echo($name);

   // arrays
   $students = array(6, 3, 5, ..);    

   // ouput
   foreach ($students as $key => $value) {
   	 echo("my index is ". $key ." my value ". $value);
   }

   // strings
   $str1 = "i love erlang ";
   $str2 = "but i just learnt php";

   $str3 = $str1 +" a lot "+  $str2;


   // functions

   function say_something(){
   	// echo("im bernice");
   	return "im bernice";
   }

   // calling
   say_something();   // im bernice
   $mydata = say_something();   
   echo(say_something());


   // class

   
   class User{
      private $id = NULL, $name;


   }

   // object
   $user_0 = new User();
   $user_0->$id = 9;
   $user_0->$id;  // 9



   class User{
      private $id = NULL, $name;
      
      public __construct($passed_id, $passed_name){
          $this->id = $passed_id;
      }
      
      function setID($sm){
      	$this->id = $sm;
      }

      function getID(){
      	return $this->id;
      }


      function setProperties(){
         $this->id;
         selectt * from users where id = $this->id;
      }

      function getProperties(){
         array('passwordd'=> val, 'user_type'=> 'ADMIN');

         return array('password' 'type');
      }



   }

   // object
   $user_0 = new User(5, 'name');
   $user_0->$id;  // 5





?>

   php_learn
      ph.php

      file.php
        <php include('file2.php') ?>

      file2.php
