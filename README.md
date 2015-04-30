# determinator_server
All requests are sent to base_url/[method_name], where [method_name] is replaced by the methods below.

All passwords sent must be encrypted with the sha1-algorithm.


##Response
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

*Notice*: The JSON response should contain either a data object or an error object, but not both. If both data and error are present, the error object takes precedence.

##Requests
###User endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** user  |username, password| |  Create new user|
|**PUT** user/*:user* |newPassword |   |Change password on current user | 
|**DELETE** user/*:user* |         |   |Delete user|

###Friend endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** friend/*:user* | | friends |  Return all user friends|
|**POST** friend/*:user* | friend |   | Add friend | 
|**DELETE** friend/*:user* |friend|   |Delete friend connection|
	
###Login endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**POST** login |username, password |  |  Authorize user|

###Poll endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** poll/*:user* | | polls |  Get polls to user|
|**POST** poll | question, alternative_one, alternative_two, receivers, questioner |   | Add a new poll | 

###Answer endpoint

| METHOD        | INPUT       | RETURN        | DESCRIPTION   |   
| ------------- |-------------| ------------- | ------------- |
|**GET** answer/*:pollid* | | [0,1,2] |  Get answer of poll, 0 if not ready |
|**POST** answer/*:pollid* | username, answer |   | Give answer to poll
