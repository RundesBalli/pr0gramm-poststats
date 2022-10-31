<?php
/**
 * includes/database/sql.php
 * 
 * Establishes the database connection and sets up the correct charset.
 */
$dbl = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPass, $mysqlDb) OR DIE(MYSQLI_ERROR($dbl));
mysqli_set_charset($dbl, $mysqlCharset) OR DIE(MYSQLI_ERROR($dbl));
?>
