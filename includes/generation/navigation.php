<?php
/**
 * includes/generation/navigation.php
 * 
 * Generates navigation elements.
 */
$requestedPage = (!empty($_GET['page']) ? $_GET['page'] : NULL);
$a = " class='active'";
$nav = "";

/**
 * Check if the user is logged in.
 */
if(!empty($loginNav) AND $loginNav === TRUE) {
  $nav.= "<div id='toggleElement'><a id='toggle'>&#x2630; ".$lang['nav']['toggle']."</a></div>";
  $nav.= "<div><a href='/stats'".($requestedPage == 'stats' ? $a : NULL).">Statistiken</a><a href='/logout'".($requestedPage == 'logout' ? $a : NULL).">Logout</a></div>";
}
?>
