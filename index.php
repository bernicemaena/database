<?php 
   /*
    
     @doc the application entry page
     @todo define routes and handle them 
   */
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS'); 
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

    session_start();
    
    require_once("./vendor/autoload.php");
    use Slim\Http\Request;
    use Slim\Http\Response;
    use Slim\Http\UploadedFile;

    require_once("expensetracker/models/User.php");
    require_once("expensetracker/models/Users.php");
    require_once("expensetracker/models/Item.php");
    require_once("expensetracker/models/Items.php");
    require_once("expensetracker/models/Expense.php");
    require_once("expensetracker/models/Expenses.php");
    require_once("expensetracker/models/Locations.php");


    $locations = new Locations();
    $app = new Slim\App;

    $container = $app->getContainer();

    // add server
    $container['server'] = "http://localhost/apps/hmp/0.1/index.php/";
    // add location object
    $container['locations'] = $locations;
    // path to add images
    $container['image_uploads_path'] = "images/";


    // start handling routes
    include_once("./expensetracker/routes/user_routes.php");
    include_once("./expensetracker/routes/item_routes.php");
    include_once("./expensetracker/routes/expense_routes.php");
    




    // $_SESSION = null;
    // $_SESSION['user_id']
    // array(1, 2, 3);
    // $bernice = array('age'=> 32, 'name'=> 'bernice');
    // $bernice['age'] .. 32

    // $_SESSION['app_session']['user_id'] = id;
    // $_SESSION['app_session']['user_name'] = name;

    // check if a session exists for a user
    function has_session(){
       return (!empty($_SESSION['app_session']) && !empty($_SESSION['app_session']['user_id']));
    }

    function get_session(){
        return $_SESSION;
    }
    
    function is_session_valid(){
        $id = $_SESSION['user_id'];
        $type = $_SESSION['user_type'];
        $user = new Host();   // ($type == "host" ? 
        return $user->exists(); 
    }

    function delete_session(){
        unset($_SESSION['app_session']);
    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile){
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }


    // run the app
    $app->run();

?>