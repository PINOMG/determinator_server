# determinator_server
All requests are sent to base_url/[method_name], where [method_name] is replaced by the methods below. To get started simply:

1. Clone this repository to your server.
2. Import the *pinomg.sql* code to your database.
3. Rename the */api/v1/connect.example.php* to */api/v1/connect.php* and follow the instructions in the file.
4. Now you're good to go! Determinate away!

*Note: The ER diagramme of the database is available in the docs/ folder.*

####Dependencies
PHP version >= 5.4.0 

##Requests
###User endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** user  |username, password| |  Create new user|
|**PUT** user/*:user* |newPassword |   |Change password on current user | 
|**DELETE** user/*:user* |         |   |Delete user|
|**GET** user | | users | Get all users. (Array of username strings) |

###Friend endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** friend/*:user* | | users |  Return all user's friends (Array of username strings)|
|**POST** friend/*:user* | username |   | Add friend| 
|**DELETE** friend/*:userone/:usertwo* |||Delete friend connection|
	
###Login endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** login |username, password |  |  Authorize user|

###Poll endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** poll/*:user* | | polls |  Get polls to user (Array of JSON poll objects)|
|**POST** poll | question, alternative_one, alternative_two, receivers, questioner |   | Add a new poll | 

###Answer endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** answer/*:pollid* | | [0,1,2] |  Get answer of poll, 0 if not ready |
|**POST** answer/*:pollid* | username, answer |   | Give answer to poll


##Response
###JSON Scheme
Responses are structured according to a modified version of Google JSON Style Guide, as below:

```
object {
  string apiVersion?;
  string method?;
  object {
    string message?;
    array [
      object {}*;
    ] items?;
  }* data?;
  object {
    integer code?;
    string message?;
  }* error?;
}*;
```

*Note [1]*: The JSON response should contain either a data object or an error object, but not both. If both data and error are present, the error object takes precedence.

*Note [2]*: The data object should contain either a message string or an items array, but not both. If both are present, the items array takes precedence.

###Example
Below is an example of a response from request **GET** *friend/Björn*.
```json
{
  "apiVersion":"1",
  "method":"friend.GET",
  "data": {
    "items": [ 
      "Ebba",
      "Martin" 
    ]
  }
}
```
###Error codes

#### System specific
**1** Parameters not correct.

**2** Specified endpoint not found.

**3** Endpoint not supporting HTTP method.

#### Answer
**4** Poll wasn't asked to user.

#### Login
**5** Wrong credentials.

**6** Username already taken.

#### General
**7** Provided user doesn't exist.

#### Friends
**8** Provided userTwo doesn't exist.

**9** Provided users are already friends.
