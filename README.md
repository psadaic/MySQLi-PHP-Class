MADSQLi-PHP-Class
=================

Small PHP MySQLi class for fast and easy MySQL database management.

Welcome to the MADSQLi-PHP-Class wiki!

# Usage

## Include class in your script
`include('madsqli.class.php');`

## Create mysqli connection
### Required:
* Hostname: e.g. localhost, 127.0.0.1, ...
* Username: e.g. root, ...
* Password: e.g. mypassword, ...
* Database: e.g. testdatabase, ...

### Optional:
* Port: e.g. 3306, ... (You can leave the port empty if you're using default mysql port, 3306)

`$test = new MADSQLi('hostname','username','password','database', port);`

## Select data
### Required:
* Table name: e.g. mytable, ...

### Optional:
* Array of rows: `$rows=array('row1','row2','row3');`
* Where condition: e.g. field1>10, ... (only 1 condition is supported per query)
* Order by: e.g. row1 ASC, row2 DESC, ... (only 1 condition is supported per query)
* Limit: e.g. 10 (display first 10 results in database that match the above conditions)

`$get = $test->select('tablename',$rows,'wherecondition','orderby','limit');`

## Insert data
### Required
* Table name
* Array of rows: `$rows=array('row1','row2','row3');`
* Array of values: `$values=array('value1','value2','value3');`

`$test->insert('tablename',$rows,$values);`

## Update Data
### Required
* Table name
* Array of rows: `$rows=array('row1','row2','row3');`
* Array of values: `$values=array('value1','value2','value3');`
* Where condition: e.g. field1=3, ... (only 1 condition is supported per query)

`$test->update('tablename',$rows,$values,'wherecondition');`

## Delete data
### Required:
* Table name
* Where condition: e.g. field1=5, ... (only 1 condition is supported per query)

`$test->delete('tablename','wherecondition');`

## Custom SQL query
### Required:
* SQL query: e.g. `"SELECT * FROM mytable"`
* True/false - true returns array with results (e.g. for SELECT), use false for (INSERT, DELETE, UPDATE, ...)

`$get = $test->custom_query("SELECT * FROM mytable", true);`

`$test->custom_query("INSERT INTO mytable (t1, t2, t3) VALUES ('1','2','3')", false);`

## Close mysqli connection

`$test->kill();`
