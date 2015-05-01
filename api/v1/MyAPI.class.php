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

    // User endpoint, for creating, changing and deleting user
    protected function user(){
        if( $this->method == 'POST'){ // Create user

            //Check if correct parameters.
            if(! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) )
                return "Wrong Parameters";

            return createUser($this->request['username'], $this->request['password']);

        } elseif( $this->method == 'PUT') { // Change password

            //Check if correct parameters and parameter set
            if(! isset( $this->args[0] ) || ! array_key_exists('newPassword', $this->request) )
                return "Wrong Parameters"; 

            return changePassword($this->args[0], $this->request['newPassword']);

        } elseif( $this->method == 'DELETE') {

            // Check if argument is set
            if(! isset( $this->args[0] ) )
                return "Wrong Parameters"; 

            return deleteUser($this->args[0]);

        } else {
            return null;
        }
    }

    protected function login(){
        if( $this->isPost() ){
            if(! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) )
                return "Wrong Parameters";

            return login($this->request['username'], $this->request['password']);
        } else {
            return null; //Add error handling here.
        }
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

	//Friend endpoint, for adding friend, getting all friends and deleting friend
    protected function friend() {
	
        if( $this->isPost() ) { // Add friend
			
			//Check parameters
			if(! isset( $this->args[0] ) || ! array_key_exists('userTwo', $this->request) )
                return "Wrong Parameters"; 
				
			return addFriend( $this->args[0], $this->request['userTwo'] );
			
		} elseif ($this->isGet() ) { // Get friends
		
			//Check parameters
			if(! isset( $this->args[0] ) )
                throw new Exception("Wrong parameters", 1);
			
			return getFriends($this->args[0]);
			
		} elseif( $this->method == 'DELETE') {
		
			//Check parameters
			if(! isset( $this->args[0] ) || ! array_key_exists('userTwo', $this->request) )
                return "Wrong Parameters"; 
			
			return deleteFriend( $this->args[0], $this->request['userTwo'] );
			
		}
    }

    protected function answer(){
        if ( $this->isPost() ){
            if( ! isset( $this->args[0] ) ||
                ! array_key_exists('username', $this->request) || 
                ! array_key_exists('answer', $this->request) )
                return "Wrong Parameters"; 

            return newAnswer($this->args[0], $this->request['username'], $this->request['answer']);
        } elseif ( $this->isGet() ) {
            if( ! isset( $this->args[0] ) )
                return "Wrong parameters";

            return getResult($this->args[0]);
        }
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

    private function isPost(){
        return $this->method == 'POST';
    }
	
	private function isGet() {
		return $this->method == "GET";
	}
}
