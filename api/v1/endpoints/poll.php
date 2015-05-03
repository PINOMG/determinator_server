<?php

//Function to get all polls
function getPolls($username) {
	global $dbh;
	
	//Check if user exists
	$sql = 'SELECT COUNT(*) AS result FROM Users WHERE username = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 )
        throw new Exception("Provided user doesn't exist", 7);
        
	
	$sql = 'SELECT poll, answer 
			FROM PollsAskedToUsers PATU
			LEFT JOIN Polls P
			ON PATU.poll = P.id
			WHERE PATU.user = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$username] );
	
	$result = $q->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

function createPoll($question, $alternative_one, $alternative_two, $receivers_json, $username) {
	global $dbh;
	
	//Create a new poll
    $sql = 'INSERT INTO Polls (question, alternative_one, alternative_two, questioner) VALUES (?,?,?,?)';

    $q = $dbh->prepare($sql);
    $q->execute( [$question, $alternative_one, $alternative_two, $username] );
	
	$id = $dbh->lastInsertId();

	$receivers = json_decode($receivers_json);
	$sql = 'INSERT INTO PollsAskedToUsers (user, poll) VALUES (:receiver, :poll_id)';
	$q = $dbh->prepare($sql);
	$q->bindValue(':poll_id', $id, PDO::PARAM_INT);
	$q->bindParam(':receiver', $receiver, PDO::PARAM_STR);
	
	foreach ($receivers as $receiver) {
		$q->execute();
	}
	return "Success";
}