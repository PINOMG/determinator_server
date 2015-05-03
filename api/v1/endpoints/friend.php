<?php 

//Function to get all friends of a user
function getFriends($username) {
	global $dbh;
	
	//Check if user exists
	$sql = 'SELECT COUNT(*) AS result FROM Users WHERE username = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 )
        throw new Exception("Provided user doesn't exist.", ERROR_USER_NOT_FOUND);
	
	// Get friends
	$sql = 'SELECT userTwo AS user FROM FriendsWith WHERE userOne = ?
                UNION
            SELECT userOne AS user FROM FriendsWith WHERE userTwo = ?' ;
		
	$q = $dbh->prepare($sql);
	$q->execute( [$username, $username] );
		
	$results = $q->fetchAll(PDO::FETCH_COLUMN, 0);

	return ($results);
}

//Function to add a new friends
function addFriend($username, $userTwo) {
	global $dbh;
	
	//Check if user exists
	$sql = 'SELECT COUNT(*) AS result FROM Users WHERE username = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 )
        throw new Exception("Provided user doesn't exist.", ERROR_USER_NOT_FOUND);
	
	//Check if userTwo exists
	$sql = 'SELECT COUNT(*) AS result FROM Users WHERE username = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$userTwo] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 )
        throw new Exception("Provided userTwo doesn't exist.", ERROR_USERTWO_NOT_FOUND);

	//Check if userTwo already is a friend of the user
    $sql = 'SELECT COUNT(*) AS results FROM (
                SELECT * FROM FriendsWith 
                  WHERE userOne = ? AND userTwo = ?
            UNION 
                SELECT * FROM FriendsWith
                  WHERE userOne = ? AND userTwo = ? 
            ) AS mu';

    $q = $dbh->prepare($sql);
    $q->execute( [$username, $userTwo, $userTwo, $username] );

    $results = $q->fetch(PDO::FETCH_ASSOC)['results']; 

    if( $results > 0 )
		throw new Exception("Friends already exist.", ERROR_ALREADY_FRIENDS);
	
	//Add userTwo as a friend of the user
    $sql = 'INSERT INTO FriendsWith VALUES (?,?)';

    $q = $dbh->prepare($sql);
    $q->execute( [$username, $userTwo] );

    return "Success";
}

function deleteFriend($username, $userTwo) {
	global $dbh;
	
	//Check if user exists
	$sql = 'SELECT COUNT(*) AS result FROM Users WHERE username = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 )
        throw new Exception("Provided user doesn't exist.", ERROR_USER_NOT_FOUND);
	
	//First combination
    $sql = 'DELETE FROM FriendsWith WHERE userOne = ? AND userTwo = ?';

    $q = $dbh->prepare($sql);
    $q->execute( [$username, $userTwo] );

    // Second plausible combination
    $sql = 'DELETE FROM FriendsWith WHERE userOne = ? AND userTwo = ?';

    $q = $dbh->prepare($sql);
    $q->execute( [$userTwo, $username] );
	
    return "Success";
}