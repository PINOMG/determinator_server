<?php 

//Function to get all friends of a user
function getFriends($username) {
	global $dbh;
	
	$sql = 'SELECT userTwo FROM FriendsWith WHERE userOne =?' ;
		
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
		
	$results = $q->fetchAll(PDO::FETCH_ASSOC);
		
	if (empty($results)) {
		return "You have no friends!";
	} else {
		return $results;
	}
}

//Function to add a new friends
function addFriend($username, $userTwo) {
	global $dbh;

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

    if( $results > 0 ){
		return "Friends already exists";
    } 
	
	//Add userTwo as a friend of the user
    $sql = 'INSERT INTO FriendsWith VALUES (?,?)';

    $q = $dbh->prepare($sql);
    $q->execute( [$username, $userTwo] );

    return "Success";
}

function deleteFriend($username, $userTwo) {
	global $dbh;

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