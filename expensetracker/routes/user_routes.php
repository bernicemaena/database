<?php
   


   $app->post('/user/get_session[/]', function($request, $response){
       $new_user = new User();
       $return = array('session_assigned'=> false);

       $user_name = $request->getParam('user_name');
       $user_password = $request->getParam('user_password');
       
       if($new_user->setName($user_name)->setPassword($user_password)->accountExists()){
          
          $user_id = (new User())->setName($user_name)->findID(); 

          $_SESSION['app_session']['user_id'] = $user_id;
          $return['session_assigned'] = true;
       }else{
          $return['session_assigned'] = false;
       }
      
       return json_encode($return);

   });

   $app->post('/user/add[/]', function($request, $response){
       $new_user = new User();
       $return = array('added'=> false);

       $user_name = $request->getParam('user_name');
       $user_password = $request->getParam('user_password');
       $user_email = $request->getParam('user_email');
       // $earning = $request->getParam('earning');
       // $earning_for = $request->getParam('earning_for');
       
       $new_user->setName($user_name)->setPassword($user_password)->setContacts($user_email)
                ->setEarning(0)->setEarningFor('MONTH');

       if($new_user->nameUsed()){
           $return['added'] = false;
       }else{
       	   $new_user->save();
       	   $return['added'] = true;
       }

       return json_encode($return);

   });




   $app->post('/user/set-earning[/]', function($request, $response){
       $new_user = new User();
       $return = array('set'=> false);

       $earning = $request->getParam('earning');
       $earning_for = $request->getParam('earning_for');
       $user_id = $request->getParam('user_id');
       //echo("got earning ". $earning ." get earning_for ". $earning_for ." user_id ". $user_id);
       $new_user->setID($user_id)
                ->setEarning($earning)
                ->setEarningFor($earning_for)
                ->setDbEarning();

       $return['set'] = true;

       return json_encode($return);

   });

   $app->post('/user/name_used[/]', function($request, $response){
       $new_user = new User();
       $return = array('name_used'=> false);

       $user_name = $request->getParam('user_name');
       
       $return['name_used'] = $new_user->setName($user_name)->nameUsed();
       //$return['name_used'] = $bool;

       return json_encode($return);

   });


   $app->post('/user/exists[/]', function($request, $response){
       $new_user = new User();
       $return = array('exists'=> false);

       $user_id = $request->getParam('user_id');
       
       $return['exists'] = $new_user->setID($user_id)->nameUsed();
       //$return['name_used'] = $bool;

       return json_encode($return);

   });  

   // .com/index.php/item/delete/2
   $app->get('/user/get_details/with_name/{name}[/]', function($request, $response, $args){
      $return = array('user'=> NULL);
 
      $new_user = new User(); 
         
      $user_id = $new_user->setName($args['name'])->findID(); 

      $return = $new_user->setID($user_id)->setProperties()->getProperties();

      return json_encode($return);

   });

   $app->post('/user/account_exists[/]', function($request, $response){
       $new_user = new User();
       $return = array('account_exists'=> false);

       $user_name = $request->getParam('user_name');
       $user_password = $request->getParam('user_password');
       
       $return['account_exists'] = $new_user->setName($user_name)
                                            ->setPassword($user_password)
                                            ->accountExists();
       //$return['name_used'] = $bool;

       return json_encode($return);

   });

   $app->post('/user/delete[/]', function($request, $response){
       $new_user = new User();
       $return = array('deleted'=> false);

       $user_id = $request->getParam('user_id');
       
       $new_user->setID($user_id)->delete();
       $return['deleted'] = true;

       return json_encode($return);

   });

   $app->post('/user/logout[/]', function($request, $response){
       $return = array('loged_out'=> false);
       
       delete_session();

       $return['loged_out'] = true;

       return json_encode($return);

   });



   $app->get('/users[/]', function($request, $response, $args){
       $users = new Users(); echo("sfdsd");
       
       #$offset = $args['offset'];
       #$limit = $args['limit'];

       $all_users = $users->getAll();

       return json_encode($all_users);

   });


    $app->get('/test[/]', function($request, $response){
       $users = new Users(); // echo("sfdsd");
       
       #$offset = $args['offset'];
       #$limit = $args['limit'];

       #$users->getAll();

        return json_encode(array('id'=> 345));
    });



?>