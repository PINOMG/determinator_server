<?php
$dev = true;

if($dev){
	DEFINE('DB_HOST', 'localhost');
	DEFINE('DB_NAME', 'pinomg');
	DEFINE('DB_USER', 'root');
	DEFINE('DB_PASS', 'kantarell');
} else {
	DEFINE('DB_HOST', 'pinomg-202715.mysql.binero.se');
	DEFINE('DB_NAME', '202715-pinomg');
	DEFINE('DB_USER', '202715_io35569');
	DEFINE('DB_PASS', 'elloabbe');
}

// Hi
try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


?>