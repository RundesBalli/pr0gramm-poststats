<?php
/**
 * includes/loader.php
 * 
 * Configuration and function loader
 */

/**
 * Basic configuration, functions and config checks
 */
require_once(__DIR__.DIRECTORY_SEPARATOR.'constants.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'configCheck.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'output.php');
/**
 * Database connection and functions
 */
require_once(__DIR__.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'sql.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'defuse.php');
?>
