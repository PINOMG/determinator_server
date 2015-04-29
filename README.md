# determinator_server

Varje request skickas med hjälp av antingen GET eller POST. Alla begäran skickas till base_url/[method_name], där [method_name] ersätts av metoderna nedan. 

Alla lösenord som skickas måste vara krypterade med sha1.

* betyder att användaren måste kunna autentiseras med hjälp av användarnamn (username) och lösenord (password)

// Create new user

user POST

	input: username, password

// Change password on current user

user/:user PUT
	
	input: newPassword

// Delete user

user/:user DELETE

//Return all user friends

friend/:user GET

	return array of friends

//Add friedn

friend/:user POST

	input: friend 

//Delete friend connection

friend/:user DELETE

	input: friend

//Authorize user

login POST

	input: username, password

//Add a new poll

poll POST

	input: question, alternative_one, alternative_two, receivers, questioner

//Get polls to user

poll/:user GET

	returns array of polls

//Give answer

answer/:pollid POST

	input: username, answer

//Get answer of poll

answer/:pollid GET

	return [null,1,2]

