<?php

  include_once 'expensetracker/models/User.php';
  include_once 'expensetracker/models/Users.php';
  include_once 'expensetracker/models/Item.php';
  include_once 'expensetracker/models/Items.php';
  include_once 'expensetracker/models/Expense.php';
  include_once 'expensetracker/models/Expenses.php';

  $user_0 = new User();


  // echo($user_0);

  $user_0->setName('bernice')->setPassword('bern123')
           ->SetContacts('bern123@gmail.com')
           ->setEarning(300000.00)->setEarningFor('MONTHLY')
           ->setPlanningFor('MONTHLY');


  // check if user exists

  // check if name is used


  // save
  if($user_0->nameUsed()){
  	#echo("name used cant add <br>");
  }else{
    #$user_0->save();
  }




  // test items

  $item_0 = new Item();
   
  $item_0->setName('test item')->setFrequency('DAILY')->setCost(140)->setUser(array('id'=> 1));

  //$item_0->save();

  // check if name is used
  if((new User())->setName('bernice')->nameUsed()){
    // echo('name is used');
  }else{
    // echo('name is not used');
  }




  // all users
  $users = new Users();
  $all_users = $users->getAll();
  //var_dump($all_users);

  /**
  echo(sha1('david123') .'<br>');

  if(sha1('david123') == '5ad7ac9412efd3cb9bc0fa558b7b880443ec30bd'){
     echo('they marchg');
  }else{
     echo('they dont');
  }

  echo('<br>'. sha1('bern123'));
  */

   $expense_0 = new Expense();
   $expense_0->setName('August budget')
             ->setUser(array('id'=> 6))
             ->setDates(array(
                'start_from'=> (new DateTime())->format('Y-m-d'),
                'end_at'=> (new DateTime())->format('Y-m-d')
              ));


   // $expense_0->save();

   
   // expenses for david
   // $all_davids = (new Expenses())->getForUser(6);

   // var_dump($all_davids);

   
   $items = array(
        array(
              'user_id'=> 1, 'name'=> 'noodles', 'cost'=> 60, 'priority'=> 'LOW', 
              'frequency'=> 'DAILY', 'expense_id'=> 8
            ),
        array(
              'user_id'=> 6, 'name'=> 'sheesha', 'cost'=> 10000, 'priority'=> 'LOW', 
              'frequency'=> 'ANNUALLY', 'expense_id'=> 4),
        array(
              'user_id'=> 1, 'name'=> 'laptop bag', 'cost'=> 2000, 'priority'=> 'LOW', 
              'frequency'=> 'ANNUALLY', 'expense_id'=> 6),
        array(
              'user_id'=> 6, 'name'=> 'colgate', 'cost'=> 130, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 9),
        array(
              'user_id'=> 7, 'name'=> 'bread', 'cost'=> 50, 'priority'=> 'LOW', 
              'frequency'=> 'DAILY', 'expense_id'=> 12),
        array(
              'user_id'=> 7, 'name'=> 'butter', 'cost'=> 350, 'priority'=> 'LOW', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 12),
        array(
              'user_id'=> 9, 'name'=> 'laundry', 'cost'=> 700, 'priority'=> 'HIGH', 
              'frequency'=> 'WEEKLY', 'expense_id'=> 13),
        array(
              'user_id'=> 7, 'name'=> 'cocoa', 'cost'=> 230, 'priority'=> 'LOW', 
              'frequency'=> 'WEEKLY', 'expense_id'=> 12),
        array(
              'user_id'=> 9, 'name'=> 'electricity', 'cost'=> 1300, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 13),
        array(
              'user_id'=> 9, 'name'=> 'transport', 'cost'=> 3500, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 14),
        array(
              'user_id'=> 9, 'name'=> 'rent', 'cost'=> 15000, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 13),
        array(
              'user_id'=> 1, 'name'=> 'transport', 'cost'=> 1000, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 6),
        array(
              'user_id'=> 1, 'name'=> 'rent', 'cost'=> 10000, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 8),
        array(
              'user_id'=> 6, 'name'=> 'noodles', 'cost'=> 500, 'priority'=> 'LOW', 
              'frequency'=> 'WEEKLY', 'expense_id'=> 11),
        array(
              'user_id'=> 9, 'name'=> 'shoes', 'cost'=> 4500, 'priority'=> 'MEDIUM', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 13),
        array(
              'user_id'=> 1, 'name'=> 'shoes', 'cost'=> 4400, 'priority'=> 'MEDIUM', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 6),
        array(
              'user_id'=> 1, 'name'=> 'ebooks', 'cost'=> 5000, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 7),
        array(
              'user_id'=> 1, 'name'=> 'wifi', 'cost'=> 2000, 'priority'=> 'HIGH', 
              'frequency'=> 'MONTHLY', 'expense_id'=> 7)
   );

   foreach ($items as $item) {
     $item_obj = (new Item())->setName($item['name'])
                             ->setFrequency($item['frequency'])
                             ->setPriority($item['priority'])
                             ->setCost($item['cost'])
                             ->setUser(array('id'=> $item['user_id']))
                             ->setExpense(array('id'=> $item['expense_id']));
     // $item_obj->save();
   }



   $expenses = array(
        array('user_id'=> 1, 'name'=> '2018 budget', 'start_from'=> '2018-01-01', 'end_at'=> '2018-12-31'),
        array('user_id'=> 1, 'name'=> '2018 mid-year budget', 'start_from'=> '2018-06-01', 'end_at'=> '2018-09-01'),
        array('user_id'=> 6, 'name'=> '2018 january budget', 'start_from'=> '2018-01-01', 'end_at'=> '2018-02-01'),
        array('user_id'=> 6, 'name'=> '2019 proposed budget', 'start_from'=> '2019-01-01', 'end_at'=> '2019-12-31'),
        array('user_id'=> 6, 'name'=> '2018 budget', 'start_from'=> '2018-01-01', 'end_at'=> '2018-12-31'),
        array('user_id'=> 7, 'name'=> '2019 budget', 'start_from'=> '2019-01-01', 'end_at'=> '2019-12-31'),
        array('user_id'=> 9, 'name'=> 'vacation 2 budget', 'start_from'=> '2018-08-07', 'end_at'=> '2018-08-19'),
        array('user_id'=> 9, 'name'=> 'vacation 1 budget', 'start_from'=> '2018-04-21', 'end_at'=> '2018-05-07')
   );

   foreach ($expenses as $expense) {
     $expense_obj = (new Expense())->setName($expense['name'])
                                   ->setDates(array(
                                     'start_from'=> $expense['start_from'],
                                     'end_at'=> $expense['end_at']
                                   ))
                                   ->setUser(array('id'=> $expense['user_id']));
     // $expense_obj->save();

   }

   
   // $expenses_obj = (new Expenses())->getExpenseItems(1);
   // var_dump($expenses_obj);
   
   $user_expenses = (new Expenses())->getForUser(1);
   var_dump($user_expenses[2]);
   
   echo("<br><br><br><br>");
   echo('name of first item in first expense '. $user_expenses[2]['items'][1]['name'] ."<br><br>");
   var_dump($user_expenses[2]['items'][0]);

?>