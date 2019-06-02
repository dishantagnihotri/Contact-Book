This contact book application allows a user to create a personal account from where they can create their contacts list with the following information: Name, Contact Number, & Address. It validates the correct address using GOOGLE MAPS API while user register.

Later, User can access that contact's information with Google Street View images & WalkScore Results. [ of contact's stored address ] 

# For Setup
  - PHP
  - XAMPP / PHP Server [ php -s localhost:3000 ]
  - PHPMyAdmin / Sql Database

# Languages Used
  - PHP
  - JavaScript
  - HTML4
  - Bootstrap v4

# Technologies Used
  - WalkScore API: To find the Walkscore.
  - Google Maps API: To validate the correct address.
  - Google Street View API: To fetch address associated images.

# Database
This project doesn't have SQL files included. You can create one by yourself by naming the database 'contactbook' & has two tables.

  - users: To store credentials.
  - contact: To store address.
Insted of using pivot tables, I used `user_id` as a foreign key to connect both of these tables.

The above tables should have following structure:
	•	Users: Id [primary_key], Username, First_name, Last_name, Password
	•	Contacts: Id [primary_key], First_name, Last_name, Street [for full address], Lat, Lng, Phone, User_id [foreign_key]









