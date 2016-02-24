
# Web Host Account Manager ( www.whamcp.com / whamcp at gmail.com )

A PHP Web Application to manage multiple cPanel Servers.  

## INSTALLATION GUIDE

### 1. REQUIREMENTS

a) Linux OS  
b) Web Server (Apache preferred)  
c) PHP v5.3.x  
	c, a) ioncube loader  
	c, b) support for mysql database  
	c, c) curl with ssl support  
	c, d) json support  
	c, e) libxml support  
d) MySQL v5.1 or above  
e) Disk Space: 10MB  

### 2. HOW TO INSTALL

**NOTE:** This guide assume that you are trying to set up WHAM on http://www.mydomain.com/wham/ with www.mydomain.com hosted from path /home/mydomain/public_html/

#### Copy files to install directory and fix permissions
```
# cp -r wham/ /home/mydomain/public_html/
# chown -R mydomain:mydomain /home/mydomain/public_html/wham/
```
### 3. DATABASE

Create a database on the server using `PHPMyAdmin`, assign a mysql user to it with `ALL PRIVILEGES`

Export the database schema ( *db_dump/wham.sql* ) into newly created MySQL database

From ssh (as root), the commands would be like:
```
# mysqladmin create whamdb
# mysql

mysql > GRANT ALL PRIVILEGES ON whamdb.* TO whamadmin@localhost IDENTIFIED BY 'mystrongpassword';
```
```
# mysql whamdb < db_dump/wham.sql
```
### 4. CONFIGURE WHAM!

Mention the database, host, username and password in the config file `application/config/database.php`:
```
$db['default']['hostname'] = 'localhost';  
$db['default']['username'] = '';  
$db['default']['password'] = '';  
$db['default']['database'] = '';  
```
**IMPORTANT:** DO NOT MODIFY ANY OTHER LINES

Mention the url on which you wish to run WHAM! in file `application/config/config.php`:
```
$config['base_url']	= 'http://www.mydomain.com/wham/';
```
**NOTE:** Trailing slash '/' is necessary at the end of the url. 

**IMPORTANT:** DO NOT MODIFY ANY OTHER LINES  

### 5. CRON

**WHAM!** has a cron script that syncs account details from all servers that you add, into its database. This script can only be executed from command-line. It is recommended to run this every hour.
```
0 * * * * php /home/mydomain/public_html/wham/index.php cron sync > /dev/null 2>&1
```
### 6. WHAT NEXT

The installation steps have been completed. You can access WHAM! at the following URL:

http://www.mydomain.com/wham/  
username: admin  
password: admin123  

You should change your password as soon as possible via *WHAM! -> Settings -> Reset Password*

If all goes well, you can go ahead and start adding Data center, and then your servers into it and manage them.

**ENJOY WHAM!**
