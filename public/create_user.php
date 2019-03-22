<?php
require_once '../vendor/autoload.php';

// include database and object files
include_once 'config/database.php';
include_once 'objects/user.php';
include_once 'objects/country.php';


// get database connection
$database = new Database();
$db = $database->getConnection();


$logger = new \Monolog\Logger('my_logger');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/my_app.log', \Monolog\Logger::DEBUG));
$logger->pushHandler(new \Monolog\Handler\FirePHPHandler());


// pass connection to objects
$user = new User($db);
$country = new Country($db);

$page_title = "Create User";
include_once "header.php";

if ($_POST) {

    // set user property values
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->country_id = $_POST['country_id'];


    // create the user
    if ($user->create()) {
        $logger->info('Adding a new user', array('username' => $user->name));
        echo "<div class='alert alert-success'>User was created.</div>";
    } // if unable to create the user, tell the user
    else {
        echo "<div class='alert alert-danger'>Unable to create user.</div>";
    }
}
?>
    <div class='right-button-margin'>
        <a href='index.php' class='btn btn-default pull-right'>Read Users</a>
    </div>

    <!-- HTML form for creating a user -->
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <td>Name</td>
                <td><input type='text' name='name' class='form-control'/></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><input type='email' name='email' class='form-control'/></td>
            </tr>
            <tr>
                <td>Country</td>
                <td>
                    <?php
                    // read the user categories from the database
                    $stmt = $country->read();

                    // put them in a select drop-down
                    echo "<select class='form-control' name='country_id'>";
                    echo "<option>Select country...</option>";

                    while ($row_country = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row_country);
                        echo "<option value='{$id}'>{$country}</option>";
                    }

                    echo "</select>";
                    ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">Create</button>
                </td>
            </tr>

        </table>
    </form>
<?php
// footer
include_once "footer.php";
?>