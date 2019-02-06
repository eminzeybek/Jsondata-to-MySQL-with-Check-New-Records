# Json Data to Mysql with check new records and updated records
- Step 1 : Json data path on remote server or localhost is defined on code
```
$url = 'HTTP://WWW.TESTSITE.COM/data.json';
```
- Step 2 : Create Database and Database connect information is defined.
```
$mysql_hostname = "localhost"; // HOSTNAME
$mysql_user = "root"; //DATABASE USERNAME
$mysql_password = "rootroot"; //DATABASE USER PASSWORD
$mysql_database = "datajson"; //DATABASE NAME
```
- Step 3 : Set time zone and characterset
```
date_default_timezone_set('Europe/Istanbul');
mysql_query('SET NAMES UTF8'); 
mysql_query("SET CHARACTER SET utf8"); 
mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'"); 
```
- Step 4 : Match fields in the sql with fields in the database
```
INSERT INTO datas (`id` ,`name` ,`surname` ,`sex` ,`phone` ,`city`) VALUES (NULL ,  '".$character->name."',  '".$character->surname."',  '".$character->sex."',  '".$character->phone."',  '".$character->city."');"
```
- Step 5 : Run.

> You can check the data instantly by running this file with cronjob.
