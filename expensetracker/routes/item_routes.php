<?php
   

   $app->get('/items/for_user/{user_id}[/]', function($request, $response, $args){
       $items = new Items(); 

       $user_id = $args['user_id'];
       
       $all_items = $items->getForUser($user_id);

       return json_encode($all_items);
   });


   $app->post('/item/add[/]', function($request, $response){
      $return = array('added'=> false);

      // if(has_session()){
         // $user_id = get_session()['app_session']['user_id'];

          $new_item = new Item();
          $return = array('added'=> false);

          $name = $request->getParam('name');
          $user_id = $request->getParam('user_id');
          $expense_id = $request->getParam('expense_id');
          $cost = $request->getParam('cost');
          $priority = $request->getParam('priority');
          $frequency = $request->getParam('frequency');
       
          $new_item->setName($name)
                   ->setCost($cost)
                   ->setFrequency($frequency)
                   ->setPriority($priority)
                   ->setExpense(array('id'=> $expense_id))
                   ->setUser(array('id'=> $user_id));

          $return['added'] = $new_item->save();

      // }

       return json_encode($return);
   });


   $app->post('/item/exists[/]', function($request, $response){
       $new_item = new Item();
       $return = array('exists'=> false);

       $item_id = $request->getParam('item_id');
       
       $return['exists'] = $new_item->setID($item_id)->exists();

       return json_encode($return);

   });

   // .com/index.php/item/delete/2
   $app->get('/item/get_details/{id}[/]', function($request, $response, $args){
      $return = array();
 
      $new_item = new Item();
         
      $item_id = $args['id'];

      $return = $new_item->setID($item_id)->setProperties()->getProperties();

       return json_encode($return);

   });



   // .com/index.php/item/delete/2
   $app->post('/item/delete[/]', function($request, $response){
      $return = array('deleted'=> false);
      
      if(has_session()){
         $new_item = new Item();
         $user_id = $_SESSION['app_session']['user_id'];
         
         $item_id = $request->getParam('item_id');

         $item_details = $new_item->setID($item_id)->setProperties()->getProperties();
         
         if($item_details['user']['id'] == $user_id){
            $new_item->setID($item_id)->delete();
            $return['deleted'] = true;          
         } 

      }

       return json_encode($return);

   });



   $app->get('/items[/]', function($request, $response, $args){
       $items = new Items(); 
       
       $all_items = $items->getAll();

       return json_encode($all_items);

   });





?>