<?php
/**
 * stats.php
 * 
 * Statistikseite
 */

/**
 * Einbinden der Cookieüberprüfung.
 */
require_once('cookie.php');

/**
 * Titel und Überschrift
 */
$title = "Statistiken";
$content.= "<h1>Post prüfen</h1>".PHP_EOL;

/**
 * Allgemeine Infos und Links
 */
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Eingeloggt als: <span class='warn bold'>".$username."</span></div>".PHP_EOL.
"</div>".PHP_EOL;

/**
 * Bookmarklet
 */
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Bookmarklet: <a href=\"javascript:void(window.open('".$_SERVER['SERVER_NAME']."/stats?post='+encodeURIComponent(location.href)))\">Check Tags</a></div>".PHP_EOL.
"</div>".PHP_EOL;

/**
 * Formular zum Prüfen eines Posts
 */
$content.= "<form action='/stats' method='post'>".PHP_EOL;
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-3 col-xl-2'>Post</div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-4 col-xl-4'><input type='text' name='postId' placeholder='Post-ID oder ganzer Link' autofocus tabindex='1'></div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-5 col-xl-6'>Die Post-ID oder jeder pr0gramm-Link der eine Post-ID enthält kann hier übergeben werden.</div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "<div class='row'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-3 col-xl-2'>Prüfen</div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-4 col-xl-4'><input type='submit' name='submit' value='Prüfen' tabindex='2'></div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "</form>".PHP_EOL;

/**
 * Postauswertung
 */
if(isset($_POST['submit']) AND !empty($_POST['postId'])) {
  /**
   * == Matcht: ==
   * 1234567
   * https://pr0gramm.com/new/12345
   * https://pr0gramm.com/top/12345
   * https://pr0gramm.com/new/12345:comment12345
   * https://pr0gramm.com/top/12345:comment12345
   * https://pr0gramm.com/new/12345:comment
   * https://pr0gramm.com/top/12345:comment
   * https://pr0gramm.com/new/tag/12345
   * https://pr0gramm.com/top/tag/12345
   * https://pr0gramm.com/new/tag/12345:comment12345
   * https://pr0gramm.com/top/tag/12345:comment12345
   * https://pr0gramm.com/new/12345/12345
   * https://pr0gramm.com/top/12345/12345
   * https://pr0gramm.com/new/12345/12345:comment12345
   * https://pr0gramm.com/top/12345/12345:comment12345
   * https://pr0gramm.com/user/asdf/uploads/12345
   * https://pr0gramm.com/user/asdf/likes/12345
   * https://pr0gramm.com/user/asdf/uploads/12345:comment12345
   * https://pr0gramm.com/user/asdf/likes/12345:comment12345
   * https://pr0gramm.com/user/asdf/uploads/tag/12345:comment12345
   * https://pr0gramm.com/user/asdf/likes/tag/12345:comment12345
   * https://pr0gramm.com/user/asdf/uploads/12345/12345:comment12345
   * https://pr0gramm.com/user/asdf/likes/12345/12345:comment12345
   * https://pr0gramm.com/stalk/12345
   * https://pr0gramm.com/stalk/12345:comment12345
   * /new/12345
   * /top/12345
   * /new/12345:comment12345
   * /top/12345:comment12345
   * /new/12345:comment
   * /top/12345:comment
   * /new/tag/12345
   * /top/tag/12345
   * /new/tag/12345:comment12345
   * /top/tag/12345:comment12345
   * /new/12345/12345
   * /top/12345/12345
   * /new/12345/12345:comment12345
   * /top/12345/12345:comment12345
   * /user/asdf/uploads/12345
   * /user/asdf/likes/12345
   * /user/asdf/uploads/12345:comment12345
   * /user/asdf/likes/12345:comment12345
   * /user/asdf/uploads/tag/12345:comment12345
   * /user/asdf/likes/tag/12345:comment12345
   * /user/asdf/uploads/12345/12345:comment12345
   * /user/asdf/likes/12345/12345:comment12345
   * /stalk/12345
   * /stalk/12345:comment12345
   * 
   * == Matcht nicht: ==
   * 0
   * 0123465
   * https://pr0gramm.com/
   * https://pr0gramm.com/new
   * https://pr0gramm.com/top
   * https://pr0gramm.com/new/012345
   * https://pr0gramm.com/top/012345
   * https://pr0gramm.com/new/012345:comment12345
   * https://pr0gramm.com/top/012345:comment12345
   * https://pr0gramm.com/user
   * https://pr0gramm.com/user/asdf
   * https://pr0gramm.com/user/asdf/uploads
   * https://pr0gramm.com/user/asdf/likes
   * https://pr0gramm.com/stalk
   * /
   * /new
   * /top
   * /user
   * /user/asdf
   * /user/asdf/uploads
   * /user/asdf/likes
   * /stalk
   */
  if(preg_match('/(^([1-9]\d*)$|(?:http(?:s?):\/\/pr0gramm\.com)?\/(?:top|new|user\/\w+\/(?:uploads|likes)|stalk)(?:(?:\/\w+)?)\/([1-9]\d*)(?:(?::)comment(?:\d+))?)/i', trim($_POST['postId']), $match) === 1) {
    $postId = (int)defuse($match[1]);
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h1>Auswertung - Post-ID ".$postId;
    /**
     * Abfragen der Tags und Kommentare bei der pr0gramm-API
     */
    $response = apiCall("https://pr0gramm.com/api/items/info?itemId=$postId");
    $content.= "<h3>Tags nach Confidence</h3>".PHP_EOL;
    
    /**
     * Auswertung der Tags und Anzeige sortiert nach Confidence absteigend
     */
    $tags = $response['tags'];
    foreach($tags as $key => $value) {
      $sortArray[$key] = $value['confidence'];
    }
    arsort($sortArray);
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2'>Confidence</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-10 col-xl-10'>Tag</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($sortArray as $key => $confidence) {
      $content.= "<div class='row'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2".($confidence < 0.2 ? " warn" : "")."'>".$confidence."</div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-10 col-xl-10'>".$tags[$key]['tag']."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Auswertung der Kommentare
     */
    $comments = $response['comments'];
    foreach($comments as $key => $value) {
      $sortArray[$key] = ($value['up']-$value['down']);
      $userCommentScore[$value['name']] = (isset($userCommentScore[$value['name']]) ? $userCommentScore[$value['name']]+$sortArray[$key] : $sortArray[$key]);
      $userCommentCount[$value['name']] = (isset($userCommentCount[$value['name']]) ? $userCommentCount[$value['name']]+1 : 1);
    }
    $minusSortArray = $sortArray;
    arsort($sortArray);
    arsort($minusSortArray);
    arsort($userCommentScore);
    arsort($userCommentCount);

    /**
     * Anzeigen der beliebtesten Kommentare sortiert nach Benis absteigend
     */
    $content.= "<h3>Beliebteste Kommentare</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2'>ID</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-7 col-xl-7'>Benis</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($sortArray as $key => $score) {
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2'><a href='https://pr0gramm.com/new/".$postId.":comment".$comments[$key]['id']."' rel='noopener' target='blank'>".$comments[$key]['id']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$comments[$key]['name']."' rel='noopener' target='blank'>".$comments[$key]['name']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-7 col-xl-7'>".$score."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der unbeliebtesten Kommentare sortiert nach Benis absteigend
     */
    $content.= "<h3>Unbeliebteste Kommentare</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2'>ID</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-7 col-xl-7'>Benis</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($minusSortArray as $key => $score) {
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-2 col-xl-2'><a href='https://pr0gramm.com/new/".$postId.":comment".$comments[$key]['id']."' rel='noopener' target='blank'>".$comments[$key]['id']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$comments[$key]['name']."' rel='noopener' target='blank'>".$comments[$key]['name']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-7 col-xl-7'>".$score."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der User mit dem meisten Benis auf Kommentare unter dem Post
     */
    $content.= "<h3>User mit dem meisten Benis auf Kommentare</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-9 col-xl-9'>Benis</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($userCommentScore as $user => $score) {
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$user."' rel='noopener' target='blank'>".$user."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-9 col-xl-9'>".$score."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der User mit den meisten Kommentaren unter dem Post
     */
    $content.= "<h3>User mit den meisten Kommentaren</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-9 col-xl-9'>Anzahl Kommentare</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($userCommentCount as $user => $count) {
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$user."' rel='noopener' target='blank'>".$user."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-9 col-xl-9'>".$count."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }
  } else {
    $content.= "<div class='warnbox'>Keine Post-ID erkennbar</div>".PHP_EOL;
  }
}
?>
