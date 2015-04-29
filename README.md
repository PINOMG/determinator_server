# determinator_server

Varje request skickas med hjälp av antingen GET eller POST. Alla begäran skickas till base_url/[method_name], där [method_name] ersätts av metoderna nedan. 

Alla lösenord som skickas måste vara krypterade med sha1.

### User
######Create new user

user **POST**

	input: username, password

######Change password on current user

user/:user **PUT**
	
	input: newPassword

######Delete user

user/:user **DELETE**

###Friend

######Return all user friends

friend/:user **GET**

	return array of friends

######Add friend

friend/:user **POST**

	input: friend 

######Delete friend connection

friend/:user **DELETE**

	input: friend
	
###Login

######Authorize user

login **POST**

	input: username, password

###Poll

######Add a new poll

poll **POST**

	input: question, alternative_one, alternative_two, receivers, questioner

######Get polls to user

poll/:user **GET**

	returns array of polls

###Answer

######Give answer

answer/:pollid **POST**

	input: username, answer

######Get answer of poll

answer/:pollid **GET**

	return [null,1,2]

