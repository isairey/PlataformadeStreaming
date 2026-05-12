# BACKEND API CONTRACT

This contract governs the exachange of data between frontend and backend parts of the system.

- Only POST requests are processed. Others are ignored either silently or with message.
- Textual data is exchanged in JSON format.
- Authentication method used is JWT sent as `Authorization: Bearer ` header.
- Unless stated otherwise, all requests on the api methods are expected to have authorization header.
- All json data fields are required, unless stated otherwise.
- All responses from api contain `ok` field. It is a boolean value and indicates success/failure.
- If `ok` field is `false`, it means the request didn't succeed. In that case there will be an 
    accompanying `message` field containing detail.
- All api requests dealing with content be preceeded by setting a profile. Without setting a profile,
    a user can not access content.
- When a user is registered, authenticated or set new profile, a new JWT is generated. The client
    should save the token to use with subsequent requests. In case token is lost,
    `authenticate` must be called to have new token issued.

CAUTION: Exact address of endpoint may differ depending on server document root when deployed.

## subscribe

Sign up new user to the platform.

### Endpoint

`api/subscribe.php`

### Request

+ `name` - default profile name
+ `email` - chosen username of the user
+ `password` - password of the user

**Note:** This method doesn't require `Authorization` header.

```
{
    "name": "mathos",
    "username": "mathos",
    "password": "password"
}
```

### Response

+ `token` - bearer token for use with subsequent requests.
+ `user` -  user data containing `user_id` and `username`.

```
{
    "ok": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NDY5NjUyMTQs ...",
    "user": {
        "user_id": 15,
        "username": "mathos"
    }
}
```

## changePassword

Chnage password for currently logged in user account.

### Endpoint

`api/changePassword.php`

### Request

A single string which the new password for the account.

```
"test"
```

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## deleteUser

Delete currently logged in user account.

### Endpoint

`api/deleteUser.php`

### Request

Empty

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## authenticate

Sign in a previously registered user.

### Endpoint

`api/authenticate.php`

### Request

+ `email` - email of the user about to login
+ `password` - password of the user

**Note:** This method doesn't require `Authorization` header.

```
{
    "username": "mathos",
    "password": "password"
}
```

### Response

+ `token` - bearer token for use with subsequent request
+ `user` -  user data containing `user_id` and `username`.

```
{
    "ok": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NDY5NjUyMTQs ...",
    "user": {
        "user_id": 15,
        "username": "mathos"
    }
}
```

## addProfile

Creates a new user profile. A user can have multiple profiles under one user account.

### Endpoint

`api/addProfile.php`

### Request

+ `name` - name of the new profile.

```
{
    "name": "myProfile"
}
```

### Response

+ `profile` information about the newly added profile including its `name`, `user_id` it belogs to
    and a unique `profile_id` to the profile.
```
{
    "ok": true,
    "profile": {
        "name": "myProfile",
        "user_id": 15,
        "profile_id": "3"
    }
}
```


## setProfile

Selects a profile, among profiles of authenticated user, to be used for content retrieval.

### Endpoint

`api/setProfile.php`


### Request

A single integer: the `profile_id` of the target profile.

```
3
```

### Response

+ `toekn` a new JWT token. This tokon should replace the token issued during authentication
    as the new token includes both `profile` and `user` whereas the previous token covers `user` only.

```
{
    "ok": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOj ..."
}
```

## updateProfile

Update profile information for already existing profile owned by current user.

### Endpoint

`api/updateProfile.php`

### Request

+ `profile_id` profile_id of the target profile. The profile must be owned by the user making
    the request. This field is mandatory.
+ `name` new name of the profile, if name is being updated.
+ `picture` new profile picture, if picture is being updated.

```
{
    "name": "dadel",
    "profile_id": 2
}
```

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## deleteProfile

Delete one of profiles of authenticated user.

### Endpoint

`api/deleteProfile.php`

### Request

A single integer: the `profile_id` of the target profile.

```
2
```

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## getAllProfiles

Fetch all profiles on user making the query.

### Endpoint

`api/getAllProfiles.php`

### Request

Empty

### Response

+ `profiles` an array of profiles. Each profile can have `user_id`, `profile_id`,
    `name` fields and an optional `picture` field.

```
{
    "ok": true,
    "profiles": [
        {
            "user_id": 15,
            "profile_id": 2,
            "name": "babel",
            "picture": null
        },
        {
            "user_id": 15,
            "profile_id": 6,
            "name": "test",
            "picture": null
        }
    ]
}
```

## addToList

Add a movie to user's 'MyList'.

### Endpoint

`api/addToList.php`

### Request

A single integer: the `movie_id` of the target movie.

```
1
```

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## getMyList

Fetches saved list items of a user profile.

### Endpoint

`api/getMyList.php`

### Request

Empty

### Response

+ `items` - array of movies each with `movie_id` and `type` indicating the category of the movie.
    1 - short film, 2 - series.

Example

```
{
    "ok": true,
    "items": [
        {
            "movie_id": 1,
            "type": 2
        }
    ]
}
```

## removeFromList

Remove a movie from users' `MyList`. 

### Endpoint

`api/removeFromList.php`

### Request

A single integer: the `movie_id` of the target movie. The movie must on the list for the 
    request to succeed.

```
5
```

### Response

Empty on success(except for `ok` field commonly found in every response).

```
{
    "ok": true
}
```

## getFilms

Fetches all short film types of movies and not tv-series.

### Endpoint

`api/getFilms.php`

### Request

Empty

### Response

+ `films` an array of movies each with `movie_id` and  `type` of movie information in it.

```
{
    "ok": true,
    "films": [
        {
            "movie_id": 3,
            "type": 1
        }
    ]
}
```

## getSeries

Fetches all tv-series types of movies and not short films.

### Endpoint

`api/series.php`

### Request

Empty

### Response

+ `movies` - array of movies each with `movie_id` and `type` information.

```
{
    "ok": true,
    "series": [
        {
            "movie_id": 1,
            "type": 2
        }
    ]
}
```

## getMovie

Fetch movie information for a specific movie.

### Endpoint

`api/getMovie.php`

### Request

A single and only int value specifying movie id whose information is being requested.

```
2
```

### Response

+ `movie`: movie information. If json has video section, it is means the movie is a short film. Otherwise it is a series.

Example:

```
{
    "ok": true,
    "movie": {
        "movie_id": 2,
        "title": "Pilot",
        "description": "Walter white turns 50 and discovers he has cancer. ...",
        "type": 3
    }
}
```

## getSeason

For movies that are series type, fetches all episodes of a specific season.

### Endpoint

`api/getSeason.php`

### Request

+ `movie_id` movie id of the series to which the season in question belongs to.
+ `season_no` integer value specifying the season_no.

### Response

+ `episodes`: an array of movie information. In addition each movie has `episode_no` added to it.

```
{
    "ok": true,
    "episodes": [
        {
            "episode_no": 1,
            "movie_id": 2,
            "title": "Pilot",
            "description": "Walter white turns 50 and discovers he has cancer. ...",
            "type": 3
        }
    ]
}
```


## getHistory

Fetch watch history of current user profile. Whenever a movie is streamed, that action is logged on 
    server ant its progress recorded. This request is means to access that data.

### Endpoint

`api/getHistory.php`

### Request

Empty

### Response

+ `history` an array of movies each with `movie_id` and `type`.

```
{
    "ok": true,
    "history": [
        {
            "movie_id": 2,
            "type": 3
        }
    ]
}
```


## getRecents

Fetch recently added movies. This api method can be used to get data to fill `recents` category or tab
    in a typical browser based client.

### Endpoint

`api/getRecents.php`

### Request

Empty

### Response

+ `movies` an array of movies each with `movie_id` and `type`.

```
{
    "ok": true,
    "movies": [
        {
            "movie_id": 3,
            "type": 1
        },
        {
            "movie_id": 1,
            "type": 2
        }
    ]
}
```

## getGenre

Fetch a list of movies that belong in a specific genre.

### Endpoint

`api/getGenre.php`

### Request

A single string specifying genre name being requested.

### Response

+ `movies` an array of movies each with `movie_id` and `type`.

```
{
    "ok": true,
    "movies": [
        {
            "movie_id": 2,
            "type": 3
        }
    ]
}
```

## Watch

Start streaming a video. Can be used to fetch both playlists and segments.

### Endpoint

`api/watch.php`

### Request

+ `movie_id` - Id of movie whose stream data is being requested.
+ `request_type` - type information requested. Either `manifest` for playlist or `segement` for fetching segments.
+ `segment_info` - (optional) if request type is `segment`, this array provides more info about the segment. It has:
    - `sgement_no` - if request type is `segment`, segment_no of the segment.
    - `start` - starting position of segment(in seconds) from the beginning video.

Example: playlist request
```

{
    "movie_id": 4808477627,
    "request_type": "manifest"
}
```
Example: segment request

```
{
    "movie_id": 4808477627,
    "request_type": "segment",
    "segment_info" : {
        "segment_no": 353,
        "start": 23454
    }
}
```

### Response

Binary data. `Content-Type` header is set to `application/vnd.apple.mpegurl` for playlist response and `video/MP2T` to segment responses.



## getMe

Fetch user and, if set, profile information currently held token identifies. This can be used to determine profile
    for example to get profile information when used previously saved token.

### Endpoint

`api/getMe.php`

### Request

Empty

### Response

+ `user_id` user_id of user currently being identified.
+ `profile_id` if available, profile_id of profile set with the token.
+ `profile` complete profile information, as would be returned by `getProfile`.
+ `user` user information including `user_id` and `username`.

```
{
    "ok": true,
    "data": {
        "user_id": 15,
        "profile_id": 2,
        "user": {
            "user_id": 15,
            "username": "mathos"
        },
        "profile": {
            "user_id": 15,
            "profile_id": 2,
            "name": "dadel",
            "picture": null
        }
    }
}
```
