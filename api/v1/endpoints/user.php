<?php

// Function using post to create user
function createUser($username, $password){
	global $dbh;

    //Check if user exists
    $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

    if( $results > 0 ){
        return "User already exists.";
    }

    //Create new user
    $sql = 'INSERT INTO Users VALUES (?,?)';
    $q = $dbh->prepare($sql);
    $q->execute([$username, $password]);

    return "Success";
}

function changePassword($username, $newPassword){
    global $dbh;

    //Check if user exist
    $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

    if( $results == 0 ){
        return "User doesn't exists.";
    }

    // User exist. update pass.
    $sql = 'UPDATE Users SET password = ? WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$newPassword, $username]);

    return "Success";
}

function deleteUser($username) {
    global $dbh;
    echo $username;

    deleteAllFriends($username);

    $sql = 'DELETE FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    return "Success";
}

function deleteAllFriends($username){
        global $dbh;
    echo $username;

    $sql = 'DELETE FROM FriendsWith WHERE userOne = ? OR userTwo = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username, $username]);

    return "Success";
}