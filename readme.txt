Download updated database first.
-teachers_db.sql-

Always pull codes before commit and pushing.
To run php files and to try functionalities always run XAMPP Control Panel.
Start "Apache" and "MySQL". 
Always use this address to run the PHP files.

http://localhost/JPinterac/login.php

main website (login.php)

always start with localhost/(folder name where all the files is)/(name of the php file)





<-------------Needed programs and apps------------->

XAMPP: https://www.apachefriends.org/download.html (Version: 8.2.12 / PHP 8.2.12)
Visual Studio Code: https://code.visualstudio.com/
GitBash: https://git-scm.com/downloads
GitHub account: https://github.com/


Install all of the needed programs.

Go to this website for reference: https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository

Then you can open your xampp htdocs folder which you can locate here in your file explorer: C:\xampp\htdocs

create a folder JPinterac inside the htdocs folder.

If your account is not configured in gitbash use this command: 
   	git config --global user.name "Your Username"
   	git config --global user.email "your_email@example.com"

Open your folder and right click then choose gitbash here

Once the terminal is open you can type this: 
	git clone https://github.com/jp-interac-class/JPinterac.git










<--------------UPLOADING CSV------------->

Might get an error when you try to upload the CSV po. 

Go to file explorer po and paste this po in the address bar. 

            C:\xampp\mysql\bin\my.ini

then open it using notepad, and search "max_allowed_packet"

it would look like this: max_allowed_packet=64M or max_allowed_packet=1M

change it to max_allowed_packet=10000M









<-------------DOWNLOADING THE DATABASE------------->


Go to the google drive that have shared to you and download the teachers_db.sql file

