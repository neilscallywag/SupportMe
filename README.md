# SupportMe
 A smaller clone of change.org for learning purposes

# Endpoints

##  1. Register

```API
POST /register
```

### Request

| Attribute  |      Type     |  Required |  Description |
|:----------:|:-------------:|:------:|:------:|
| `firstname` |  String  | Yes | First name |
| `lastname` |     String    |   No | Last name |
| `email` |  String  |    Yes | Email |
| `password` |  String  |    Yes| Password |

```json

{
"firstname":"sunset boulevard",
"lastname":"test",
"email":"mainuser@lol.com",
"password":1234 
}

```

### Response
```json
{"message":"Successfully registered"}
```

## 2. Login
```API
POST /login
```

### Request

| Attribute  |      Type     |  Required |  Description |
|:----------:|:-------------:|:------:|:------:|
| `email` |  String  | Yes | Registered email |
| `password` |     String    |   Yes | Password |


```json

{
"email":"mainuser@lol.com",
"password":1234 
}
```


### Response
```json
{
"message":"Login successful",
"token":"JWT-TOKEN"
}
```

## 3. Fetch campaign by campaign id
```API
POST /campaign/id/:id
```

### Request
None

### Response
```json
{
"starter_id":123,
"c_title":"title_here",
"c_description":"description here",
"c_picture":"picture in base64 string"
}
```
## 3. Search campaign using query
```API
POST /campaign/search/:query
```

### Request
None

### Response
```json
{
"starter_id":123,
"c_title":"title_here",
"c_description":"description here",
"c_picture":"picture in base64 string"
}
```

## 3. Create campaign
```API
POST /campaign/create
```

### Request

| Attribute  |      Type     |  Required |  Description |
|:----------:|:-------------:|:------:|:------:|
| `user_id` |  int | Yes | user id accessing website|
| `campaign_title` |  str | Yes | Title of campaign|
| `campaign_description` |  str | Yes | Description of campaign|
| `campaign_picture` |  str | No | Picture encoded in base64|


```json

{
    "user_id":1243,
    "campaign_title":"title_here",
    "campaign_description":"description here",
    "campaign_picture":"picture in base64 string"
}
```

### Response
```json
{
"message":"Campaign successfully created",
}
```
# To Do List
- [x] Registration Controller
- [x] Login Controller
- [x] Authentication System using JWT
- [x] Obtain Device header
- [x] Database DAO
- [x] Create Custom Router Class
- [x] Account for malformed json type i.e missing email and password or other keys for register and login controllers
- [x] authenticate does not differentiate no user/wrong pass
- [x] consider what if client enters data w/not matching id
- [ ] Retract Support from campaign
    1 Unpledge(PledgerID,CampaignID)
- [ ] Self User profile
    1. GetCampaigns()
    2. GetUserInfo()
- [ ] Campaign Page
    1. GetCampaignInfo()
    2. GetComments()
    3. CountTotalPledges()
- [ ] Comment on campaign
   1. CommentOnCampaign()
   1. ReplyToComment()
- [x] Create SupportMe Campaign
    1. ValidateFields() (not yet check character number shd be easy)
- [ ] Delete SupportMe Campaign
    1. DeleteCampaign()
- [ ] Pledge Support for Campaign
    1. Pledge(pledgerID,campaignID)
- [x] Search for Campaign
    1. By ID
    2. By Name (simple search with wild card)




# Installation
1. Clone the repository 
```bash
git clone https://github.com/neilscallywag/SupportMe.git
```
2. Make sure you have Composer installed. Move to the directory where you have composer.json with the command prompt and run the following command:
```bash
composer install
```



# Database Entity Relationship Schema
![Database Schema](images/schema.jpg)


# Testing Commands
```bash
curl -i  -H "Device: Chrome" -d "{ \"email\":\"mainuser@lol.com\",\"password\":1234 }" -X POST localhost/login
```

```bash
curl -i -X POST -d "{ \"firstname\":\"sunset boulevard\",\"lastname\":\"test\",\"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/register
```

```bash
curl -H "Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzEyODI2MjMsImV4cCI6MTY3MTI4NjIyMywiaXNzIjoibG9jYWxob3N0IiwiZGF0YSI6eyJlbWFpbCI6Im1haW51c2VyQGxvbC5jb20iLCJ1c2VyX2lkIjo2fX0.pb8fRwviNAVVqeyEa9xuNIjTk5nADpvcAC_bOqdODhk" -H "Device: Chrome" -d "{\"user_id\":6}" -i -X POST  localhost/campaign/id/1
```

```bash
curl -H "Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzEyODI2MjMsImV4cCI6MTY3MTI4NjIyMywiaXNzIjoibG9jYWxob3N0IiwiZGF0YSI6eyJlbWFpbCI6Im1haW51c2VyQGxvbC5jb20iLCJ1c2VyX2lkIjo2fX0.pb8fRwviNAVVqeyEa9xuNIjTk5nADpvcAC_bOqdODhk" -H "Device: Chrome" -d "{\"user_id\":6}" -i -X POST  localhost/campaign/search/save%20my
```

```bash
curl -H "Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzEyODI2MjMsImV4cCI6MTY3MTI4NjIyMywiaXNzIjoibG9jYWxob3N0IiwiZGF0YSI6eyJlbWFpbCI6Im1haW51c2VyQGxvbC5jb20iLCJ1c2VyX2lkIjo2fX0.pb8fRwviNAVVqeyEa9xuNIjTk5nADpvcAC_bOqdODhk" -H "Device: Chrome" -d "{\"user_id\":6, \"campaign_title\":\"Let us eat cake\",\"campaign_description\":\"shit have flight eh\",\"campaign_picture\":\"base 64 string here\"}" -i -X POST  localhost/campaign/create
```
