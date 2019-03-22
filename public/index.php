<?php

require_once '../vendor/autoload.php';

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// set number of records per page
$records_per_page = 5;

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// include database and object files
include_once 'config/database.php';
include_once 'objects/user.php';
include_once 'objects/country.php';

// instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$country = new Country($db);

// query users
$stmt = $user->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();
$page_title = "Read Users";
include_once "header.php";

echo "<div class='right-button-margin'>";
echo "<a href='create_user.php' class='btn btn-default pull-right'>Create User</a>";
echo "</div>";

// display the users if there are any
if ($num > 0) {

    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
    echo "<th>User</th>";
    echo "<th>Email</th>";
    echo "<th>Country</th>";

    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        echo "<tr>";
        echo "<td>{$name}</td>";
        echo "<td>{$email}</td>";
        echo "<td>";
        $country->id = $row['country_id'];
        $country->readName();
        echo $country->country;
        echo "</td>";

        echo "<td>";
// read, edit and delete buttons
        echo "<a href='read_one.php?id={$id}' class='btn btn-primary left-margin'>
    <span class='glyphicon glyphicon-list'></span> Read
</a>
 
<a href='update_user.php?id={$id}' class='btn btn-info left-margin'>
    <span class='glyphicon glyphicon-edit'></span> Edit
</a>
 
<a delete-id='{$id}' class='btn btn-danger delete-object'>
    <span class='glyphicon glyphicon-remove'></span> Delete
</a>";
        echo "</td>";

        echo "</tr>";

    }

    echo "</table>";


// the page where this paging is used
    $page_url = "index.php?";

// count all users in the database to calculate total pages
    $total_rows = $user->countAll();

// paging buttons here
    include_once 'paging.php';
} // tell the user there are no users
else {
    echo "<div class='alert alert-info'>No users found.</div>";
}
// set page footer
include_once "footer.php";
?>