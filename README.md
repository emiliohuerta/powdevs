# CakePHP
First, execute dump for DB located on root/app/PowDevsStuff/20220822.sql
Then update the database configuration located on app/Config/database.php with the data required adding "powdevs" as the database
After this first setup, I created a Command to be executed on shell, this command connects to the API and inserts all the data on the database.. It can be executed regularly to ensure in case of new items added to the api.
The site contains a user login verification so, create a new user (sorry for the ugly design). Go to /users/add and create a new user
After creating the user go to the root domain and its going to redirect you to the login  page ,complete the login form and its going to display the list of characters
You can create new lists on the sidebar, or click the lists for display the contents for each one.


Things to improve :
For the frontend technology I chose Jquery, I would have chosen ReactJs and developed more reusable components but my decision was made solely for the development time more than anything.

There is still no Tests on the project 
