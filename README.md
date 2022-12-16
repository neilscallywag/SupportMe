# SupportMe
 A smaller clone of change.org for learning purposes

(Timezone of server is Asia/Singapore. This is for the TTL)

I also tried creating a cron in SQL (see cron.sql)

# API (special/different operations)
# obtaining device header not implemented

1. Register ✅
    1. ValidateUser()
    2. RegisterUser()
2. Login ✅ returns JWT token (string)
Plan is to delete session using cron/when user asks for validation 
    1. ValidateCredentials()

# API (standard operation CRUD)
JWT must be passed during any of these operation (maybe except see post/comments- to be discussed)

check_token() to be created by me
 
Joshua want to implement but dk how to program router to now only exists /campaign
3. Create SupportMe Campaign
    1. ValidateFields()
    2. CheckCampaignExist()
4. Delete SupportMe Campaign
    1. DeleteCampaign()
5. Pledge Support for Campaign
    1. Pledge(pledgerID,campaignID)
6. Search for Campaign
    1. By ID✅
    2. By Name (simple search with wild card)✅ - SQL exception generates Klein confusing error shown below
    
<b>Fatal error</b>:  Uncaught TypeError: Exception::__construct(): Argument #2 ($code) must be of type int, string given in C:\xampp\htdocs\vendor\klein\klein\src\Klein\Klein.php:954
Stack trace:
#0 C:\xampp\htdocs\vendor\klein\klein\src\Klein\Klein.php(954): Exception-&gt;__construct('SQLSTATE[HY093]...', 'HY093', Object(PDOException))
#1 C:\xampp\htdocs\vendor\klein\klein\src\Klein\Klein.php(645): Klein\Klein-&gt;error(Object(PDOException))
#2 C:\xampp\htdocs\index.php(68): Klein\Klein-&gt;dispatch()
#3 {main}
  thrown in <b>C:\xampp\htdocs\vendor\klein\klein\src\Klein\Klein.php</b> on line <b>954</b><br />

7. Retract Support from campaign
    1. Unpledge(PledgerID,CampaignID)
8. Self User profile
    1. GetCampaigns()
    2. GetUserInfo()
9. Campaign Page
    1. GetCampaignInfo()
    2. GetComments()
    3. CountTotalPledges()
10. Comment on campaign
   1. CommentOnCampaign()
   1. ReplyToComment()
 
## To Do List

- [x] Registration Controller
- [x] Login Controller
- [ ] Authentication System using JWT
- [ ] Database DAO
- [ ] Create Custom Router Class
- [ ] Account for malformed json type i.e missing email and password or other keys for register and login controllers
- [ ] authenticate does not differentiate no user/wrongg pass

-[ ] consider what if client enters data w/not matching id

## CURL to test  (USE CMD)
curl -i -X POST -d "{ \"firstname\":\"sunset boulevard\",\"lastname\":\"test\",\"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/register

curl -i -X POST -d "{ \"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/login

curl -i -X POST -d "{ \"JWT\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzEyMDQ3MzAsImV4cCI6MTY3MTIwODMzMCwiaXNzIjoibG9jYWxob3N0IiwiZGF0YSI6eyJlbWFpbCI6Im1haW51c2VyQGxvbC5jb20iLCJ1c2VyX2lkIjo2fX0.UzQa2sBU9sM6fP32tFZ20lVx6SR_YKpxm35-zXmNrNY\"}" localhost/campaign/id/1

curl -i -X POST -d "{ \"JWT\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzEyMDQ3MzAsImV4cCI6MTY3MTIwODMzMCwiaXNzIjoibG9jYWxob3N0IiwiZGF0YSI6eyJlbWFpbCI6Im1haW51c2VyQGxvbC5jb20iLCJ1c2VyX2lkIjo2fX0.UzQa2sBU9sM6fP32tFZ20lVx6SR_YKpxm35-zXmNrNY\"}" localhost/campaign/search/save


## Installation
1. Clone the repository 
```bash
git clone https://github.com/neilscallywag/SupportMe.git
```
2. Make sure you have Composer installed. Move to the directory where you have composer.json with the command prompt and run the following command:
```bash
composer install
```

## Database Entity Relationship Schema
![Database Schema](images/schema.jpg)


