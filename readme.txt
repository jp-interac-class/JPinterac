Download updated database first.
-teachers_db.sql-

Always pull codes before commit and pushing.
To run php files and to try functionalities always run XAMPP Control Panel.
Start "Apache" and "MySQL". 
Always use this address to run the PHP files.

http://localhost/JPinterac/login.php

main website (login.php)

always start with localhost/(folder name where all the files is)/(name of the php file)























<--------------UPLOADING CSV------------->

Might get an error when you try to upload the CSV po. 

Go to file explorer po and paste this po in the address bar. 

            C:\xampp\mysql\bin\my.ini

then open it using notepad, and search "max_allowed_packet"

it would look like this: max_allowed_packet=64M or max_allowed_packet=1M

change it to max_allowed_packet=10000M

