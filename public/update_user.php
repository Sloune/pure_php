<?php
require_once '../vendor/autoload.php';

// get ID of the user to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

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

// prepare objects
$user = new User($db);
$country = new Country($db);

// set ID property of user to be edited
$user->id = $id;

// read the details of user to be edited
$user->readOne();

?>
<?php
// if the form was submitted
if ($_POST) {

    // set user property values
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->country_id = $_POST['country_id'];

    // update the user
    if ($user->update()) {
        $logger->info('User was updated.', array('username' => $user->name));

        echo "<div class='alert alert-success alert-dismissable'>";
        echo "User was updated.";
        echo "</div>";
    } // if unable to update the user, tell the user
    else {
        echo "<div class='alert alert-danger alert-dismissable'>";
        echo "Unable to update user.";
        echo "</div>";
    }
}
?>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value='<?= $user->name; ?>' class='form-control'/></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><input type='text' name='email' value='<?= $user->email; ?>' class='form-control'/></td>
            </tr>

            <tr>
                <td>Country</td>
                <td>

                    <?php
                    $stmt = $country->read();

                    // put them in a select drop-down
                    echo "<select class='form-control' name='country_id'>";

                    echo "<option>Please select...</option>";
                    while ($row_country = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $country_id = $row_country['id'];
                        $country_name = $row_country['country'];

                        // current category of the product must be selected
                        if ($user->country_id == $country_id) {
                            echo "<option value='$country_id' selected>";
                        } else {
                            echo "<option value='$country_id'>";
                        }

                        echo "$country_name</option>";
                    }
                    echo "</select>";
                    ?>
                </td>
            </tr>


            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">Update</button>
                </td>
            </tr>

        </table>
    </form>
<?php
// set page header
$page_title = "Update User";
include_once "header.php";

echo "<div class='right-button-margin'>";
echo "<a href='index.php' class='btn btn-default pull-right'>Read Users</a>";
echo "</div>";

// set page footer
include_once "footer.php";
?>