# determinator_server

Alla begäran skickas till base_url/[method_name], där [method_name] ersätts av metoderna nedan. 

Alla lösenord som skickas måste vara krypterade med sha1.

### User

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** user  |username, password| |  Create new user|
|**PUT** user/*:user* |newPassword |   |Change password on current user | 
|**DELETE** user/*:user* |         |   |Delete user|

###Friend

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** friend/*:user* | | friends |  Return all user friends|
|**POST** friend/*:user* | friend |   | Add friend | 
|**DELETE** friend/*:user* |friend|   |Delete friend connection|
	
###Login


| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** login |username, password |  |  Authorize user|

###Poll

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** poll/*:user* | | polls |  Get polls to user|
|**POST** poll | question, alternative_one, alternative_two, receivers, questioner |   | Add a new poll | 

###Answer

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** answer/*:pollid* | | [null,1,2] |  Get answer of poll, null if not ready |
|**POST** answer/*:pollid* | username, answer |   | Give answer to poll
