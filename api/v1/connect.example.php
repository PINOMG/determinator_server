<?php
/**
 * The example connect.php file to use. 
 * Rename this file to connect.php and replace the credentials below, then you're ready to go!
 */

//TODO: Replace credentials with those relevant to your MySQL configuration.
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'pinomg');
DEFINE('DB_USER', 'root');
DEFINE('DB_PASS', 'password');

try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


?>
