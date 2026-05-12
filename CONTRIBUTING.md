## Contributing Guidelines

If you are planning to contribute code to this repo, there are few steps.

+ Fork this repo
+ Make changes to your own fork
+ Test it and verify that integrates well with existing code
+ Create a pull request

For testing you will likely need database of this project. We have provided SQL
dump of our database in `/backend/database.sql` Import that to you MySQL database
(or MySQL compatible one). For the code to work without changes you will make 
sure these are true.

- Name of database is `nyetflix`
- A database user with name `nyetflix` exists and has at least `USAGE` privileges
on `nyetflix` database.
- Password for `nyetflix` user is `nyetflix`
- Database server is running on default port and listening on network

Of course all these can changed, you just gonna have modify database connectivity
configration. In that case, look into `backend.php`

API documentation can be found in API.md in this repo.
