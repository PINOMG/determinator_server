<?php
function login($username,$password){
	
    global $dbh;

    $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ? AND password = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username, $password]);

    $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

    if( $results > 0 )
    	return "Success";
    else
    	throw new Exception("Wrong credentials.", ERROR_WRONG_CREDENTIALS);
}