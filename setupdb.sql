	USE traffic;

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
	reg_date TIMESTAMP,
	UNIQUE KEY(email));

	CREATE TABLE traffic_tablefiles ( tablefile INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, filename VARCHAR(30), subdir VARCHAR(30), segmented BOOLEAN, UNIQUE KEY (filename, subdir));

#this tracks the order and widths of the columns from the different sources.
	CREATE TABLE traffic_tablesources ( subdir VARCHAR(30), colindex INT(6), colid INT(6), width FLOAT);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',0,0,13.2);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',1,1,12.5);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',2,2,12.6);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',3,3,8.2);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',4,4,8.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',5,5,4.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',6,6,8.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',7,7,8.2);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',8,8,8.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',9,9,8.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',10,10,4.0);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Wandegeya',11,11,5.3);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',0,0,15.74);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',1,3,7.72);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',2,1,7.27);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',3,-1,7.72);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',4,2,6.68);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',5,-1,11.61);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',6,4,6.73);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',7,7,6.13);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',8,-1,10.36);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',9,-1,7.92);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',10,-1,5.53);
	INSERT INTO traffic_tablesources (subdir, colindex, colid, width) VALUES ('Katwe',11,-1,6.57);

	CREATE TABLE traffic_images (
	name VARCHAR(50) PRIMARY KEY,
	tablefile INT(6) UNSIGNED,
	row INT(6) UNSIGNED,
	col INT(6) UNSIGNED,
	broken BOOL);

	CREATE TABLE traffic_results_col0 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	datetime DATETIME,
        UNIQUE KEY (userid, name)
	);

	CREATE TABLE traffic_results_col3 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	location VARCHAR(3000),
	lat FLOAT(10,6),
	lon FLOAT(10,6),
        UNIQUE KEY (userid, name)
	);

	CREATE TABLE traffic_results_col4 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	nature VARCHAR(50),
	hitandrun BOOLEAN,
        UNIQUE KEY (userid, name)
	);

	CREATE TABLE traffic_results_col6 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	vehicle_one VARCHAR(50),
	vehicle_two VARCHAR(50),
        UNIQUE KEY (userid, name)
	);

	CREATE TABLE traffic_results_col7 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	fatality_one_genderage VARCHAR(50),
	fatality_two_genderage VARCHAR(50),
	fatality_one_type VARCHAR(50),
	fatality_two_type VARCHAR(50),
	more BOOLEAN,
	nil BOOLEAN,
        UNIQUE KEY (userid, name)
	);

        CREATE TABLE traffic_results_col8 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	injury_one_genderage VARCHAR(50),
	injury_two_genderage VARCHAR(50),
	injury_one_type VARCHAR(50),
	injury_two_type VARCHAR(50),
	more BOOLEAN,
	nil BOOLEAN,
        UNIQUE KEY (userid, name)
	);

        CREATE TABLE traffic_results_col9 (
	userid INT(6) UNSIGNED,
	name VARCHAR(50),
	injury_one_genderage VARCHAR(50),
	injury_two_genderage VARCHAR(50),
	injury_one_type VARCHAR(50),
	injury_two_type VARCHAR(50),
	more BOOLEAN,
	nil BOOLEAN,
        UNIQUE KEY (userid, name)
	);
