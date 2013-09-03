Project
=======
This project aims to represent data in charts.

##Set up the project environment
###Step 1: The database
You can set up the database by either importing the `database.sql` file using `phpMyAdmin` or simply using the command line.<br />
If you want to change the database name, you can do it by changing the <em>line 22</em> of the `database.sql` file.<br />

The next step is to provide the right credentials to the `db-cred.php.template` file in the <strong>config</strong> folder to enable the connection to the database.<br />
You might remove the <em>template</em> extension afterwards.

###Step 2: Populate the database
To do so, you have to open the `testManager.php` file in the <strong>DBManager</strong> and provide an actual folder path to the class constructor.
```php
$manager = new DB_Manager('your/folder/path/', $dbo);
````
Now, you can run this file in a web browser. Normally, you will get some messages telling you that the database had been updated and that a bunch of json files had been created.<br />
You can check the specified folder to see if the files are really there.<br />
<em>Please note that this file can easily be scheduled on Linux using crontab. The functions used will take care of creating the files and updating the database if necessary.</em><br />

###Step 3: Create an admin to access the application
The password hashing process uses the `password_*` functions which are part of the php language for the versions `>= 5.5.0`. So, if you are using an older version, including the `password.php` file is mandatory.<br />
<em> Note that these functions requires `PHP >= 5.3.7` OR a version that has the `$2y` as it is written in the Library documentation.</em><br />
To generate a hashed password, you can run this:
```php
/**
 * In this case, we want to increase the default cost for BCRYPT to 12.
 * Note that we also switched to BCRYPT, which will always be 60 characters.
 */
$options = [
    'cost' => 12,
];
echo password_hash("yourpassword", PASSWORD_BCRYPT, $options)."\n";
```
The default cost is 10. You can also provide a manual process of generating salts in the options.<br />

After that, you have to store the generated hash and a chosen email in the `admins` table like so :
```sql
INSERT INTO admins(user_pass, user_email) VALUES('yourhashedpassword', 'youremail');
```
And you are done.

###Final step: Launch the application
Just go to the application folder in a web browser, you'll be prompted to a login form. After entering the right password and email, you'll be able to access the application.<br />
If all the previous steps went well, you should see your data represented in different charts and tables.

####Further links
<a href="http://www.php.net/manual/en/ref.password.php" title="PHP hashing functions">PHP hashing functions</a><br />
<a href="https://github.com/ircmaxell/password_compat" title="original hashing functions">The hashing functions in github</a><br />
<a href="http://codahale.com/how-to-safely-store-a-password/" title="original hashing functions">Why you should use bcrypt ?</a><br />
