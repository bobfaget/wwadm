<?php
// location of your /temp directory relative to this file. In my case this file is in the same directory.
$tempDir = "";
// username for e-commerce MySQL DB
$user = "u868558704_ww";
// password for e-commerce MySQL DB
$password = "y8vztaqu";
// e-commerce DB name to backup
$dbName = "u868558704_ww";
// e-commerce DB hostname
$dbHost = "mysql.hostinger.in";
// e-commerce backup file prefix
$dbPrefix = "hi_";

// create backup sql file
$sqlFile = $tempDir.$dbPrefix.".sql";
$createBackup = "mysqldump -h ".$dbHost." -u ".$user." --password='".$password."' ".$dbName." > ".$sqlFile;
exec($createBackup);

//to backup multiple databases, copy all of the above code for each DB, rename the variables to something unique, and set their values to whatever is appropriate for the different databases.
?>
