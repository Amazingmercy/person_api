# Person API

The Person API is a simple RESTful API built using PHP and MySQL that allows you to perform CRUD (Create, Read, Update, Delete) operations on a resource called "Person." You can add, retrieve, modify, and delete user details using this API.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP (>= 7.0)
- MySQL (>= 5.6)
- Web server (e.g., Apache or Nginx)
- Postman or a similar API testing tool


## Setup

1. Clone this repository to your local machine:
```bash
   git clone https://github.com/Amazingmercy/person_api.git
```
2. Set up the Database
3. Configure the database connection settings in db_config.php:
4. Start your web server

## Setup the Database

1. Create a new MySQL database for the Person API. You can do this using the MySQL command-line client:

   ```sql
   CREATE DATABASE person_db;
2. Use the Database
   USE person_db;
3. Create Tables
```
CREATE TABLE person(
id INT AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(255) NOT NULL,
last_name VARCHAR(255) NOT NULL,
career_path VARCHAR(50)
);

```


## Usage

1. Adding a New Person
To add a new person to the database, make a POST request to /api with the following JSON data:
```{
  "first_name": "John",
  "last_name": "Doe",
  "age": 30,
  "career_path": "Software Engineer"
}
```
2. Fetching User Details
To retrieve user details, make a GET request to one of the following endpoints:

/api/{id}: Fetch a user by ID.
/api/{first_name}: Fetch a user by first name.
/api/{last_name}: Fetch a user by last name.

3. Modifying User Details
To update user details, make a PUT request to /api/{id}, /api/{first_name}, or /api/{last_name} with the updated JSON data.

4. Deleting User
To delete a user, make a DELETE request to one of the following endpoints:

/api/{id}: Delete a user by ID.
/api/{first_name}: Delete a user by first name.
/api/{last_name}: Delete a user by last name.

# API Endpoints

POST /api: Add a new person.
GET /api/{id}: Get user details by ID.
GET /api/{first_name}: Get user details by first name.
GET /api/{last_name}: Get user details by last name.
PUT /api/{id}: Update user details by ID.
PUT /api/{first_name}: Update user details by first name.
PUT /api/{last_name}: Update user details by last name.
DELETE /api/{id}: Delete a user by ID.
DELETE /api/{first_name}: Delete a user by first name.
DELETE /api/{last_name}: Delete a user by last name.

# Response Format
API responses are in JSON format and include the following fields:

data: The response data (e.g., user details or success message).
error: An error message if an error occurs.
message: A success message (for successful operations).

# Status Codes
200 OK: Successful request.
400 Bad Request: Invalid request or data.

# Contributing
Contributions are welcome! Feel free to open an issue or create a pull request.
