<?php
require_once '../vendor/autoload.php';

// check if value was posted
if ($_POST) {

    require_once '../vendor/autoload.php';

    // include database and object file
    include_once 'config/database.php';
    include_once 'objects/user.php';

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    $logger = new \Monolog\Logger('my_logger');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/my_app.log', \Monolog\Logger::DEBUG));
    $logger->pushHandler(new \Monolog\Handler\FirePHPHandler());

    // prepare user object
    $user = new User($db);

    // set user id to be deleted
    $user->id = $_POST['object_id'];

    // delete the user
    if ($user->delete()) {
        echo "Object was deleted.";
        $logger->info('User was deleted.', array('username' => $user->name));
    } // if unable to delete the user
    else {
        echo "Unable to delete object.";
    }
}
?>