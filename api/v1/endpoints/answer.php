<?php
function newAnswer($pollId, $username, $answer) {
	global $dbh;
	//Check if there already is a result. This is not an error.
	$res = getResult($pollId);
	if($res > 0)
		return "Result already finished";

	//Check if user/poll combination exist
    $sql = 'SELECT COUNT(*) AS result FROM PollsAskedToUsers WHERE poll = ? AND user = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$pollId, $username] );
	
	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];
	
	if( $result == 0 ){
        throw new Exception("Poll wasn't asked to user.", ERROR_POLL_NOT_TO_USER);
    }

    //Update answer
    $sql = 'UPDATE PollsAskedToUsers SET answer = ? WHERE poll = ? AND user = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$answer, $pollId, $username] );

	//Check if poll has result now, and if it does, set new result.
	$res = haveResult($pollId);
	if( $res ) {
		registerResult($pollId, $res);
	}
	
	return "Success";
}

function getResult($pollId){
	global $dbh;
	$sql = 'SELECT COUNT(*) as result FROM Polls WHERE id = ? AND result IS NOT NULL';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$pollId] );

	$result = $q->fetch(PDO::FETCH_ASSOC)['result'];

	return $result;
}

function haveResult($pollId){
	global $dbh;

	$sql = 'SELECT 	sum(case when T.answer is null then C end) as NULLS,
					sum(case when T.answer = 1 then C end) as ONES,
					sum(case when T.answer = 2 then C end) as TWOS,
					sum(C) as SUM
			FROM (
				SELECT answer, COUNT(*) AS C
				FROM PollsAskedToUsers WHERE poll = ?
				GROUP BY answer ) T ';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$pollId] );
	$qres = $q->fetchAll(PDO::FETCH_ASSOC)[0];

	if($qres['ONES'] > $qres['SUM']/2){ //New answer for poll: 1!
		return 1;
	} elseif ($qres['TWOS'] > $qres['SUM']/2 ) { // New answer for poll: 2!
		return 2;
	} elseif ($qres['NULLS'] == 0 && $qres['ONES'] == $qres['TWOS'] ) { //If no majority is set, and all have answered: randomize answer.
		return rand(1,2);
	}

	return false;
}


function registerResult($pollId, $answer){
	global $dbh;

	$sql = 'UPDATE Polls SET result = ? WHERE id = ?';
	
	$q = $dbh->prepare($sql);
	$q->execute( [$answer, $pollId] );
}