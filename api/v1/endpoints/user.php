<?php

// Function using post to create user
function createUser($username, $password){
	global $dbh;

    //Check if user exists
    $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

    if( $results > 0 )
        throw new Exception("Username already taken.", ERROR_USERNAME_TAKEN);

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

    if( $results == 0 )
        throw new Exception("Provided user doesn't exist.", ERROR_USER_NOT_FOUND);

    // User exist. update pass.
    $sql = 'UPDATE Users SET password = ? WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$newPassword, $username]);

    return "Success";
}

function deleteUser($username) {
    global $dbh;

    //Check if user exist
    $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

    if( $results == 0 )
        throw new Exception("Provided user doesn't exist.", ERROR_USER_NOT_FOUND);        

    //Since foreign references is used, we need to remove all friends to the user before deletion.
    deleteAllFriends($username);

    $sql = 'DELETE FROM Users WHERE username = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username]);

    return "Success";
}

function deleteAllFriends($username){
    global $dbh;

    $sql = 'DELETE FROM FriendsWith WHERE userOne = ? OR userTwo = ?';

    $q = $dbh->prepare($sql);
    $q->execute([$username, $username]);

    return "Success";
}