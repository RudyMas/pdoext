# History
## 2016-10-05:
* Errors will now be handled by throwing Exceptions
* Updated the code for PHP7
## 2016-06-27:
* Bugfix: error message fixed
## 2016-06-25:
* functie fetchAll aangemaakt
## 2016-04-05: Bugfix
* functie exec hernoemd naar execQuery
## 2016-03-13: Kleine aanpassingen
* functie cleanInvoer aangepast voor PDO
* Foutmelding bij functie query en execute opgelost
## 2016-03-08: Toevoegen Prepared statements
* functie prepare (var $statement, $options = NULL) aangemaakt
* functie bindParam (var $paramno, $param, $type, $maxlen, $driverdata) aangemaakt
* functie bindValue (var $paramno, $param, $type) aangemaakt
* functie execute ($bound_input_params) aangemaakt
## 2016-03-07: Kleine aanpassingen
* functie exec (var $query) aangemaakt
* functie fetch aangepast (Toegevoegd var $internalData hiervoor)
## 2016-03-06: Aanmaken DBconnect Class (PDO versie voor MySQLi)
* functie cleanInvoer (var $inhoud)
*  functie queryItem (var $query, $veld)
*  functie queryRom (var $query)
*  functie insert (var $query)
*  functie update (var $query)
*  functie delete (var $query)
*  functie fetch (var $row)
*  functie query (var $query)
*  __construct (var $host, $gebruiker, $paswoord, $dbname, $charset, $dbtype)