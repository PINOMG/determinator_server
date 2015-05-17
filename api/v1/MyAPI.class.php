<?php

/*** Constants for error codes. ***/
 //"Parameters not correct." 
DEFINE("ERROR_WRONG_PARAMS", 1);
 //"Specified endpoint not found." 
DEFINE("ERROR_NO_ENDPOINT", 2);
 //"Endpoint not supporting HTTP method." 
DEFINE("ERROR_NO_METHOD", 3);
 //"Poll wasn't asked to user." 
DEFINE("ERROR_POLL_NOT_TO_USER", 4);
 //"Wrong credentials."  
DEFINE("ERROR_WRONG_CREDENTIALS", 5);
 // "Username already taken."  
DEFINE("ERROR_USERNAME_TAKEN", 6);
 // "Provided user doesn't exist." 
DEFINE("ERROR_USER_NOT_FOUND", 7);
 //"Provided userTwo doesn't exist." 
DEFINE("ERROR_USERTWO_NOT_FOUND", 8);
 //"Provided users are already friends." 
DEFINE("ERROR_ALREADY_FRIENDS", 9);

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
        if( $this->isPost() ){ // Create user

            //Check if correct parameters.
            if(! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);
                

            return createUser($this->request['username'], $this->request['password']);

        } elseif( $this->isPut() ) { // Change password

            //Check if correct parameters and parameter set
            if(! isset( $this->args[0] ) || ! array_key_exists('newPassword', $this->request) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS); 

            return changePassword($this->args[0], $this->request['newPassword']);

        } elseif( $this->isDelete()) {

            // Check if argument is set
            if(! isset( $this->args[0] ) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);

            return deleteUser($this->args[0]);

        } else {
            throw new Exception("Endpoint not supporting used HTTP method", ERROR_NO_METHOD);
        }
    }

    protected function login(){
        if( $this->isPost() ){
            if(! array_key_exists('username', $this->request) || ! array_key_exists('password', $this->request) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);

            return login($this->request['username'], $this->request['password']);
        } else {
            throw new Exception("Endpoint not supporting used HTTP method", ERROR_NO_METHOD);
        }
    }

	//Friend endpoint, for adding friend, getting all friends and deleting friend
    protected function friend() {
	
        if( $this->isPost() ) { // Add friend
			
			//Check parameters
			if(! isset( $this->args[0] ) || ! array_key_exists('userTwo', $this->request) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);
				
			return addFriend( $this->args[0], $this->request['userTwo'] );
			
		} elseif ($this->isGet() ) { // Get friends
		
			//Check parameters
			if(! isset( $this->args[0] ) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);
			
			return getFriends($this->args[0]);
			
		} elseif( $this->isDelete() ) {
		
			//Check parameters
			if(! isset( $this->args[0] ) || ! isset($this->args[1]) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);
			
			return deleteFriend( $this->args[0], $this->args[1] );
			
		} else {
            throw new Exception("Endpoint not supporting used HTTP method", ERROR_NO_METHOD);

        }
    }

    protected function answer(){
        if ( $this->isPost() ){
            if( ! isset( $this->args[0] ) ||
                ! array_key_exists('username', $this->request) || 
                ! array_key_exists('answer', $this->request) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);

            return newAnswer($this->args[0], $this->request['username'], $this->request['answer']);
        } elseif ( $this->isGet() ) {
            if( ! isset( $this->args[0] ) )
                throw new Exception("Parameters not correct", ERROR_WRONG_PARAMS);

            return getResult($this->args[0]);
        } else {
            throw new Exception("Endpoint not supporting used HTTP method", ERROR_NO_METHOD);
        }
    }

	//Poll endpoint, for getting and creating polls
	protected function poll() {
		
		if( $this->isGet() ) { // Get polls
			
			// Check parameters
			if(! isset( $this->args[0] ) )
                throw new Exception("Parameters not correct", 1);
                
				
			return getPolls($this->args[0]);
		
		} elseif( $this->isPost() ) { // Create poll
			
			//Check parameters
			if( ! array_key_exists('question', $this->request) || 
            ! array_key_exists('alternative_one', $this->request) ||
            ! array_key_exists('alternative_two', $this->request) ||
            ! array_key_exists('receivers', $this->request) ||
			! array_key_exists('username', $this->request)) {
			     throw new Exception("Parameters not correct", 1);
                 
				
			} 
		
			return createPoll( $this->request['question'], $this->request['alternative_one'], $this->request['alternative_two'], $this->request['receivers'], $this->request['username'] );
		} else {
            throw new Exception("Endpoint not supporting used HTTP method", ERROR_NO_METHOD);
            
        }
	}

    private function isPost(){
        return $this->method == 'POST';
    }
	
	private function isGet() {
		return $this->method == "GET";
	}

    private function isDelete(){
        return $this->method == 'DELETE';
    }

    private function isPut(){
        return $this->method == 'PUT';
    }
}
