# SupportMe

Our implementation of a business logic similar to change.org

- [SupportMe](#supportme)
- [Endpoints](#endpoints)
  - [1. Register](#1-register)
    - [Request](#request)
    - [Response](#response)
  - [2. Login](#2-login)
    - [Request](#request-1)
    - [Response](#response-1)
  - [3. Create campaign](#3-create-campaign)
    - [Request](#request-2)
    - [Response](#response-2)
  - [4. Fetch Campaign](#4-fetch-campaign)
    - [Request](#request-3)
    - [Response - Success 200](#response---success-200)
    - [Response - Fail 204](#response---fail-204)
  - [5. Search Campaign](#5-search-campaign)
    - [Request](#request-4)
    - [Response - Success 200](#response---success-200-1)
    - [Response - Fail 204](#response---fail-204-1)
  - [6. Delete Campaign](#6-delete-campaign)
    - [Request](#request-5)
    - [Response - Success 200](#response---success-200-2)
    - [Response - Fail 401](#response---fail-401)
  - [7. Edit Campaign](#7-edit-campaign)
    - [Request](#request-6)
    - [Response - Success 200](#response---success-200-3)
    - [Response - Fail 400](#response---fail-400)
  - [8. Fetch Campaigns by user](#8-fetch-campaigns-by-user)
    - [Request](#request-7)
    - [Response - Success 200](#response---success-200-4)
    - [Response - Fail 204](#response---fail-204-2)
  - [9. Add Comments](#9-add-comments)
    - [Request](#request-8)
    - [Response - Success 200](#response---success-200-5)
    - [Response - Fail 400](#response---fail-400-1)
  - [10. Edit Comments](#10-edit-comments)
    - [Request](#request-9)
    - [Response - Success 200](#response---success-200-6)
    - [Response - Fail 400](#response---fail-400-2)
  - [11. delete Comments](#11-delete-comments)
    - [Request](#request-10)
    - [Response - Success 200](#response---success-200-7)
    - [Response - Fail 400](#response---fail-400-3)
  - [12. Get pledge count for a given campaign](#12-get-pledge-count-for-a-given-campaign)
    - [Request](#request-11)
    - [Response - Success 200](#response---success-200-8)
    - [Response - Fail 400](#response---fail-400-4)
  - [13. Get list of all pledgers for a given campaign](#13-get-list-of-all-pledgers-for-a-given-campaign)
    - [Request](#request-12)
    - [Response - Success 200](#response---success-200-9)
    - [Response - Fail 400](#response---fail-400-5)
  - [14. Pledge to a given campaign](#14-pledge-to-a-given-campaign)
    - [Request](#request-13)
    - [Response - Success 200](#response---success-200-10)
    - [Response - Fail 400](#response---fail-400-6)
  - [15. Unpledge from a given campaign](#15-unpledge-from-a-given-campaign)
    - [Request](#request-14)
    - [Response - Success 200](#response---success-200-11)
    - [Response - Fail 400](#response---fail-400-7)
- [Installation](#installation)
- [Database Entity Relationship Schema](#database-entity-relationship-schema)
- [Testing Commands](#testing-commands)

# Endpoints

## 1. Register

```API
POST /register
```

### Request

|  Attribute  |  Type  | Required | Description |
| :---------: | :----: | :------: | :---------: |
| `firstname` | String |   Yes    | First name  |
| `lastname`  | String |    No    |  Last name  |
|   `email`   | String |   Yes    |    Email    |
| `password`  | String |   Yes    |  Password   |

```json
{
  "firstname": "sunset boulevard",
  "lastname": "test",
  "email": "mainuser@lol.com",
  "password": 1234
}
```

### Response

```json
{ "message": "Successfully registered" }
```

## 2. Login

```API
POST /login
```

### Request

| Attribute  |  Type  | Required |   Description    |
| :--------: | :----: | :------: | :--------------: |
|  `email`   | String |   Yes    | Registered email |
| `password` | String |   Yes    |     Password     |

```json
{
  "email": "mainuser@lol.com",
  "password": 1234
}
```

### Response

```json
{
  "message": "Login successful",
  "token": "JWT-TOKEN"
}
```

## 3. Create campaign

```API
POST /campaign/create
```

### Request

|       Attribute        | Type | Required |        Description        |
| :--------------------: | :--: | :------: | :-----------------------: |
|       `user_id`        | int  |   Yes    | user id accessing website |
|    `campaign_title`    | str  |   Yes    |     Title of campaign     |
| `campaign_description` | str  |   Yes    |  Description of campaign  |
|   `campaign_picture`   | str  |    No    | Picture encoded in base64 |

```json
{
  "user_id": 1243,
  "campaign_title": "title_here",
  "campaign_description": "description here",
  "campaign_picture": "picture in base64 string"
}
```

### Response

```json
{
  "message": "Campaign successfully created"
}
```

## 4. Fetch Campaign

```API
POST /campaign/id/[:cid]
```

### Request

Authorisation Header Required

| Attribute | Type | Required | Description |
| :-------: | :--: | :------: | :---------: |
|   `id`    | Int  |   Yes    | Campaign ID |

### Response - Success 200

```json
{
  "user_id": "",
  "c_title": "",
  "c_description": "",
  "c_picture": "",
  "updatedAt": ""
}
```

### Response - Fail 204

```json
{
  "error": "No Such Campaign"
}
```

## 5. Search Campaign

```API
GET /campaign/search/[*:str]
```

### Request

Authorisation Header Required

| Attribute |  Type  | Required |         Description          |
| :-------: | :----: | :------: | :--------------------------: |
|   `str`   | string |   Yes    | String mathing campaign name |

### Response - Success 200

```json
{
  "user_id": "",
  "c_title": "",
  "c_description": "",
  "c_picture": "",
  "updatedAt": ""
}
```

### Response - Fail 204

```json
{
  "message": "No Campaign Found"
}
```

## 6. Delete Campaign

```API
GET /campaign/delete/[i:cid]
```

### Request

Authorisation Header Required

| Attribute | Type | Required |     Description     |
| :-------: | :--: | :------: | :-----------------: |
|   `cid`   | int  |   Yes    | Campaign Identifier |

### Response - Success 200

```json
{
  "message": "Campaign successfully deleted"
}
```

### Response - Fail 401

```json
{
  "error": "Campaign was not created by user/ is non-existent"
}
```

## 7. Edit Campaign

```API
POST /campaign/edit/[i:cid]
```

### Request

Authorisation Header Required

|       Attribute        | Type | Required |        Description        |
| :--------------------: | :--: | :------: | :-----------------------: |
|         `cid`          | int  |   Yes    |    Campaign Identifier    |
|       `user_id`        | int  |   Yes    | user id accessing website |
|    `campaign_title`    | str  |   Yes    |     Title of campaign     |
| `campaign_description` | str  |   Yes    |  Description of campaign  |
|   `campaign_picture`   | str  |    No    | Picture encoded in base64 |

```json
{
  "user_id": 1243,
  "campaign_title": "title_here",
  "campaign_description": "description here",
  "campaign_picture": "picture in base64 string"
}
```

### Response - Success 200

```json
{
  "message": "campaign successfully edited"
}
```

### Response - Fail 400

```json
{
  "error": "Campaign was not created by user/ is non-existent"
}
```

## 8. Fetch Campaigns by user

```API
POST /user/campaigns
```

### Request

Authorisation Header Required from which the user id is extracted

### Response - Success 200

```json
{
  "user_id": "",
  "c_title": "",
  "c_description": "",
  "c_picture": "",
  "updatedAt": ""
}
```

### Response - Fail 204

```json
{
  "message": "No Campaign Found"
}
```

## 9. Add Comments

```API
POST /campaign/[i:cid]/add_comment
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute    | Type | Required |     Description     |
| :------------: | :--: | :------: | :-----------------: |
|     `cid`      | int  |   Yes    | Campaign Identifier |
| `comment_text` | str  |   Yes    |    Comment Text     |

### Response - Success 200

```json
{
  "message": "Successfully added comment"
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 10. Edit Comments

```API
POST /campaign/edit_comment/[i:coid]
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute    | Type | Required |    Description     |
| :------------: | :--: | :------: | :----------------: |
|  `comment_id`  | int  |   Yes    | comment Identifier |
| `comment_text` | str  |   Yes    |    Comment Text    |

### Response - Success 200

```json
{
  "message": "Successfully changed comment"
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 11. delete Comments

```API
POST /campaign/delete_comment/[i:cid]
```

### Request

Authorisation Header Required from which the user id is extracted

|  Attribute   | Type | Required |    Description     |
| :----------: | :--: | :------: | :----------------: |
| `comment_id` | int  |   Yes    | comment Identifier |

### Response - Success 200

```json
{
  "message": "Successfully deleted comment"
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 12. Get pledge count for a given campaign

```API
POST /campaign/pledge_count/[*:cid]
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute   | Type | Required |     Description     |
| :-----------: | :--: | :------: | :-----------------: |
| `campaign_id` | int  |   Yes    | campaign Identifier |

### Response - Success 200

```json
{
  "pledge_count": 00
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 13. Get list of all pledgers for a given campaign

```API
POST /campaign/pledge_list/[*:cid]
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute   | Type | Required |     Description     |
| :-----------: | :--: | :------: | :-----------------: |
| `campaign_id` | int  |   Yes    | campaign Identifier |

### Response - Success 200

```json
{
  "pledge_count": 00
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 14. Pledge to a given campaign

```API
POST /campaign/pledge/[*:cid]
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute   | Type | Required |     Description     |
| :-----------: | :--: | :------: | :-----------------: |
| `campaign_id` | int  |   Yes    | campaign Identifier |

### Response - Success 200

```json
{
  "message": "Pledge successfully added"
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

## 15. Unpledge from a given campaign

```API
POST /campaign/unpledge/[*:cid]
```

### Request

Authorisation Header Required from which the user id is extracted

|   Attribute   | Type | Required |     Description     |
| :-----------: | :--: | :------: | :-----------------: |
| `campaign_id` | int  |   Yes    | campaign Identifier |

### Response - Success 200

```json
{
  "message": "Pledge deleted"
}
```

### Response - Fail 400

```json
{
  "error": ""
}
```

# Installation

1. Clone the repository

```bash
git clone https://github.com/neilscallywag/SupportMe.git
```

2. Make sure you have Composer installed. Move to the directory where you have composer.json with the command prompt and run the following command:

```bash
composer install
```

- Change the inc/config.php's `private_key` to your own private key and `ISSUER` to your own issuer.

# Database Entity Relationship Schema

![Database Schema](images/schema.jpg)

# Testing Commands

Note : user_id do not need to be given as it is encoded in JWT

```bash
curl -i  -H "User-Agent: Chrome" -d "{ \"email\":\"mainuser@lol.com\",\"password\":1234 }" -X POST localhost/login
```

```bash
curl -i -X POST -d "{ \"firstname\":\"sunset boulevard\",\"lastname\":\"test\",\"email\":\"mainuser@lol.com\",\"password\":1234 }" localhost/register
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":6}" -i -X POST  localhost/campaign/id/1
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":6}" -i -X POST  localhost/campaign/search/save%20my
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8, \"campaign_title\":\"Let us eat cake\",\"campaign_description\":\"shit have flight eh\",\"campaign_picture\":\"base 64 string here\"}" -i -X POST  localhost/campaign/create
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8 }" -i -X POST  localhost/user/campaigns
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8 }" -i -X POST  localhost/campaign/comments/1
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8 }" -i -X POST  localhost/campaign/pledge_count/1
```

#note user id is not provided in the json

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8, \"pledge_reason\": \"i love you\" }" -i -X POST  localhost/campaign/pledge/1
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{ \"comment_text\": \"i love you\" }" -i -X POST  localhost/campaign/edit_comment/1
```

```bash
curl -H "Authorization: YOUR_ISSUER JWT_TOKEN" -H "User-Agent: Chrome" -d "{\"user_id\":8, \"campaign_title\":\"Let us eat cake\",\"campaign_description\":\"shit have flight eh\",\"campaign_picture\":\"base 64 string here\"}" -i -X POST  localhost/campaign/edit/1
```
