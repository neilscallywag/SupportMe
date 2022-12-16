# SupportMe
 A smaller clone of change.org for learning purposes
 
## To Do List

- [x] Registration Controller
- [x] Login Controller
- [ ] Authentication System using JWT
- [ ] Database DAO
- [ ] Create Custom Router Class
- [ ] Account for malformed json type i.e missing email and password or other keys for register and login controllers
- [ ] authenticate does not differentiate no user/wrongg pass

## CURL to test  (USE CMD)
curl -i -X POST -d "{ \"firstname\":\"sunset boulevard\",\"lastname\":\"test\",\"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/register

curl -i -X POST -d "{ \"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/login



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


