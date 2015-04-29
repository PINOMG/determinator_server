<?php

require_once 'API.class.php';
require_once 'connect.php';
require_once 'endpoints/answer.php';
require_once 'endpoints/friend.php';
require_once 'endpoints/login.php';
require_once 'endpoints/poll.php';
require_once 'endpoints/user.php';

class MyAPI extends API
{
    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    /**
     * Example of an Endpoint
     */
    protected function example() {
        if ($this->method == 'GET') {

            echo "<pre>";
            print_r($this->args[1]);
            echo "</pre>";

            //return "Your name is " . $this->User;
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return "Only accepts GET requests";
        } 
    }

    // User endpoint, for creating, changing and deleting user
    protected function user(){
        if( $this->method == 'POST'){
            //Check if correct parameters.
            if(! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) )
                return "Wrong Parameters";

            return createUser($this->request['username'], $this->request['password']);
        } elseif( $this->method == 'PUT') {
            return null;
        } elseif( $this->method == 'DELETE') {
            return null;
        } else {
            return null;
        }
    }

    protected function createUser() {
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        if( ! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) ){
            return "Request on wrong form. Parameters not recognized.";
        } 

        
    }

    protected function deleteUser() {
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        if( ! $this->authenticatedUser() )
            return "User is not authenticated";

        global $dbh;

        $sql = 'DELETE FROM Users WHERE username = ?';

        $q = $dbh->prepare($sql);
        $q->execute([$this->request['username']]);

        return "Success";

    }

    protected function loginUser(){
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        return $this->authenticatedUser() ? "Success" : "Not authenticated";       
    }

    protected function changePassword(){
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        if( ! $this->authenticatedUser() )
            return "User is not authenticated";

        global $dbh;

        $sql = 'UPDATE Users SET password = ? WHERE username = ?';

        $q = $dbh->prepare($sql);
        $q->execute([$this->request['newPassword'], $this->request['username']]);

        return "Success";
    }

    protected function addFriend(){
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        global $dbh;

        $sql = 'SELECT COUNT(*) AS results FROM (
                    SELECT * FROM FriendsWith 
                    WHERE userOne = ? AND userTwo = ?
                UNION 
                    SELECT * FROM FriendsWith
                    WHERE userOne = ? AND userTwo = ? 
                ) AS mu';

        $q = $dbh->prepare($sql);
        $q->execute([
            $this->request['username'],
            $this->request['userTwo'],
            $this->request['userTwo'],
            $this->request['username']
        ]);


        $results = $q->fetch(PDO::FETCH_ASSOC)['results']; 

        if( $results > 0 ){
            return "Friends already exists";
        } 

        $sql = 'INSERT INTO FriendsWith VALUES (?,?)';

        $q = $dbh->prepare($sql);
        $q->execute([
            $this->request['username'],
            $this->request['userTwo']
        ]);

        return "Success";
    }

    protected function deleteFriend(){
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        global $dbh;


        //First combination
        $sql = 'DELETE FROM FriendsWith WHERE userOne = ? AND userTwo = ?';

        $q = $dbh->prepare($sql);
        $q->execute([
            $this->request['username'],
            $this->request['userTwo']
        ]);


        // Second plausible combination
        $sql = 'DELETE FROM FriendsWith WHERE userOne = ? AND userTwo = ?';

        $q = $dbh->prepare($sql);
        $q->execute([
            $this->request['userTwo'],
            $this->request['username']
        ]);

        return "Success";
    }

    protected function createPoll(){
        if( ! $this->isPost() )
            return "Only accepts POST requests";

        if( ! array_key_exists('question', $this->request) || 
            ! array_key_exists('alternative_one', $this->request) ||
            ! array_key_exists('alternative_two', $this->request) ||
            ! array_key_exists('receivers', $this->request) ){
            return "Request on wrong form. Parameters not recognized.";
        } 

        $receivers = json_decode($this->request['receivers']);

        $sql = 'INSERT INTO Polls (question, alternative_one, alternative_two, questioner) VALUES (?,?,?,?)';

        $q = $dbh->prepare($sql);
        $q->execute([
            $this->request['question'],
            $this->request['alternative_one'],
            $this->request['alternative_two'],
            $this->request['username']
        ]);

        foreach ($receivers as $receiver) {
            # code...
        }
    }

	protected function getFriends() {
		if( ! $this->isGet() )
			return "Only accepts GET requests";
			
		if( ! array_key_exists('username', $this->request) ){
            return "Request on wrong form. Parameters not recognized.";
        } 

        global $dbh;

		$sql = 'SELECT userTwo FROM FriendsWith WHERE userOne =?' ;
		
		$q = $dbh->prepare($sql);
		$q->execute( [$this->request['username']]);
		
		$results = $q->fetchAll(PDO::FETCH_ASSOC);
		
		if (empty($results)) {
			return "You have no friends!";
		} else {
			return $results;
		}
	}
	
    private function authenticatedUser(){
        if( ! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) ){
            return "Request on wrong form. Parameters not recognized.";
        } 

        global $dbh;

        $sql = 'SELECT COUNT(*) AS results FROM Users WHERE username = ? AND password = ?';

        $q = $dbh->prepare($sql);
        $q->execute([$this->request['username'], $this->request['password']]);

        $results = $q->fetch(PDO::FETCH_ASSOC)['results'];

        return $results > 0;
    }

    private function isPost(){
        return $this->method == 'POST';
    }
	
	private function isGet() {
		return $this->method == "GET";
	}


}