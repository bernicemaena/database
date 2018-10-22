<?php
   

   $app->map(['GET', 'POST'], '/expenses/for_user/{user_id}[/]', function($request, $response, $args){
       $expenses = new Expenses(); 

       $user_id = $args['user_id'];
       
       $all_expenses = $expenses->getForUser($user_id);

       return json_encode($all_expenses);
   });


   $app->post('/expense/add[/]', function($request, $response){
      $return = array('saved'=> false, 'id'=> NULL);

      // if(has_session()){
         // $user_id = get_session()['app_session']['user_id'];

          $new_expense = new Expense();
          $return = array('added'=> false);

          $name = $request->getParam('name');
          $user_id = $request->getParam('user_id');
          $start_from = $request->getParam('start_from');
          $end_at = $request->getParam('end_at');
       
          $new_expense->setName($name)
                      ->setDates(array(
                          'start_from'=> $start_from, 'end_at'=> $end_at
                        ))
                      ->setUser(array('id'=> $user_id));

          $return = $new_expense->save();

      // }

       return json_encode($return);
   });


   $app->post('/expense/exists[/]', function($request, $response){
       $new_expense = new Expense();
       $return = array('exists'=> false);

       $expense_id = $request->getParam('expense_id');
       
       $return['exists'] = $new_expense->setID($expense_id)->exists();

       return json_encode($return);

   });

   // .com/index.php/item/delete/2
   $app->get('/expense/get_details/{id}[/]', function($request, $response, $args){
      $return = array();
 
      $new_expense = new Expense();
         
      $expense_id = $args['id']; 

      $return = $new_expense->setID($expense_id)->setProperties()->getProperties();

       return json_encode($return);

   });



   // .com/index.php/item/delete/2
   $app->post('/expense/delete[/]', function($request, $response){
      $return = array('deleted'=> false);
      
      if(has_session()){
         $new_expense = new Item();
         $user_id = $_SESSION['app_session']['user_id'];
         
         $expense_id = $request->getParam('item_id');

         $expense_details = $new_expense->setID($expense_id)->setProperties()->getProperties();
         
         if($expense_details['user']['id'] == $user_id){
            $new_expense->setID($expense_id)->delete();
            $return['deleted'] = true;          
         } 

      }

       return json_encode($return);

   });



   $app->get('/expenses[/]', function($request, $response, $args){
       $expenses = new Expenses(); 
       
       $all_expenses = $expenses->getAll();

       return json_encode($all_expenses);
   });





?>