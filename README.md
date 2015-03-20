# traffic
Crowd source tool for transcribing traffic data

To install and use this crowd-source system:

1) make a folder called 'data' with the raw images in (set .htaccess to stop public access to this folder).
2) copy the folder and call it small_data.
3) run in the folder: mogrify -resize 460x345 *.JPG [9.982% of original dimensions].
4) make a 'segmented' folder, configure apache etc to hide directory listings of this folder.
5) run the code below to make the database.
6) call the addImageFilesToDatabase.php function (this notes into the database all the images in 'data').
7) When enough images have been segmented, call the slice.php function.
8) The segmented images can be transcribed.
9) To allow a user access to certain columns, change their 'level' in the database.


==Database creation details==

CREATE DATABASE crashdata;
USE crashdata;

CREATE TABLE traffic_tablecorners (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userid INT(6) UNSIGNED,
rows INT(6) UNSIGNED,
tablefile INT(6) UNSIGNED,
topleftx INT(6) UNSIGNED,
toplefty INT(6) UNSIGNED,
toprightx INT(6) UNSIGNED,
toprighty INT(6) UNSIGNED,
bottomleftx INT(6) UNSIGNED,
bottomlefty INT(6) UNSIGNED,
bottomrightx INT(6) UNSIGNED,
bottomrighty INT(6) UNSIGNED,
date TIMESTAMP);

CREATE TABLE traffic_users (
userid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
level INT(6),
email VARCHAR(250),
pword VARCHAR(50),
reg_date TIMESTAMP);

CREATE TABLE traffic_tablefiles ( tablefile INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, filename VARCHAR(30) UNIQUE, segmented BOOLEAN);

CREATE TABLE traffic_images (
name VARCHAR(50) PRIMARY KEY,
tablefile INT(6) UNSIGNED,
row INT(6) UNSIGNED,
col INT(6) UNSIGNED);

CREATE TABLE traffic_results_col0 (
userid INT(6) UNSIGNED,
name VARCHAR(50),
datetime DATETIME
);

CREATE TABLE traffic_results_col3 (
userid INT(6) UNSIGNED,
name VARCHAR(50),
location VARCHAR(3000),
lat FLOAT(10,6),
lon FLOAT(10,6)
);

CREATE TABLE traffic_results_col4 (
userid INT(6) UNSIGNED,
name VARCHAR(50),
nature VARCHAR(50),
hitandrun BOOLEAN
);

CREATE TABLE traffic_results_col6 (
userid INT(6) UNSIGNED,
name VARCHAR(50),
vehicle_one VARCHAR(50),
vehicle_two VARCHAR(50)
);

CREATE TABLE traffic_results_col7 (
userid INT(6) UNSIGNED,
name VARCHAR(50),
fatality_one_genderage VARCHAR(50),
fatality_two_genderage VARCHAR(50),
fatality_one_type VARCHAR(50),
fatality_two_type VARCHAR(50),
more BOOLEAN,
nil BOOLEAN
);

