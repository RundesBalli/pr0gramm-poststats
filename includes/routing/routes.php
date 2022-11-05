<?php
/**
 * includes/routing/routes.php
 * 
 * Routes
 * 
 * @var array
 */
$routes = [
  /**
   * Error pages
   */
  '404' => 'errors'.DIRECTORY_SEPARATOR.'404.php',
  '403' => 'errors'.DIRECTORY_SEPARATOR.'403.php',

  /**
   * Authentication and session
   */
  'login' => 'login'.DIRECTORY_SEPARATOR.'login.php',
  'logout' => 'login'.DIRECTORY_SEPARATOR.'logout.php',

  /**
   * Statistics
   */
  'stats' => 'stats.php'
];
?>
