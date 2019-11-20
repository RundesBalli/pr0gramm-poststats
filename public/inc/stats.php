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
$content.= "<h1>Übersicht</h1>".PHP_EOL;

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
"<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Bookmarklet: <a href=\"javascript:void(window.open('https://".$_SERVER['SERVER_NAME']."/stats?post='+encodeURIComponent(location.href)))\">Check Post</a></div>".PHP_EOL.
"</div>".PHP_EOL;

/**
 * Formular zum Prüfen eines Posts
 */
$content.= "<h1>Post prüfen</h1>".PHP_EOL;
$content.= "<form action='/stats' method='post'>".PHP_EOL;
$content.= "<div class='row hover bordered'>".PHP_EOL.
"<div class='col-x-3 col-s-3 col-m-2 col-l-2 col-xl-2'>Post</div>".PHP_EOL.
"<div class='col-x-9 col-s-9 col-m-6 col-l-6 col-xl-6'><input type='text' name='postId' placeholder='Post-ID oder ganzer Link' autofocus tabindex='1' autocomplete='off'></div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-4 col-l-4 col-xl-4'>Die Post-ID oder jeder pr0gramm-Link der eine Post-ID enthält kann hier übergeben werden.</div>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-0 col-l-0 col-xl-0'><div class='spacer-s'></div></div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "<div class='row hover bordered'>".PHP_EOL.
"<div class='col-x-3 col-s-3 col-m-2 col-l-2 col-xl-2'>Prüfen</div>".PHP_EOL.
"<div class='col-x-9 col-s-9 col-m-10 col-l-10 col-xl-10'><input type='submit' name='submit' value='Prüfen' tabindex='2'></div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "</form>".PHP_EOL;

/**
 * Postauswertung
 */
if((isset($_POST['submit']) AND !empty($_POST['postId'])) OR (isset($_GET['post']) AND !empty($_GET['post']))) {
  /**
   * Abfangen ob das Formular übergeben wurde, oder ob ein Post per Bookmarklet übergeben wurde.
   */
  if(isset($_POST['submit']) AND !empty($_POST['postId'])) {
    $post = trim($_POST['postId']);
  } elseif(isset($_GET['post']) AND !empty($_GET['post'])) {
    $post = trim($_GET['post']);
  }

  /**
   * Matchen der PostId
   * 
   * == Matcht: ==
   * 1234567
   * 0123465 (matcht ohne führende 0)
   * https://pr0gramm.com/new/012345 (matcht ohne führende 0)
   * https://pr0gramm.com/top/012345 (matcht ohne führende 0)
   * https://pr0gramm.com/new/012345:comment12345 (matcht ohne führende 0)
   * https://pr0gramm.com/top/012345:comment12345 (matcht ohne führende 0)
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
   * https://pr0gramm.com/
   * https://pr0gramm.com/new
   * https://pr0gramm.com/top
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
  if(preg_match('/(?:(?:http(?:s?):\/\/pr0gramm\.com)?\/(?:top|new|user\/\w+\/(?:uploads|likes)|stalk)(?:(?:\/\w+)?)\/)?([1-9]\d*)(?:(?::comment(?:\d+))?)?/i', $post, $match) === 1) {
    mysqli_query($dbl, "UPDATE `accounts` SET `requestCount` = `requestCount`+1 WHERE `username`='".$username."'") OR DIE(MYSQLI_ERROR($dbl));
    $postId = (int)defuse($match[1]);
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h1>Auswertung - Post-ID ".$postId."</h1>".PHP_EOL;
    $title = "Auswertung - Post-ID ".$postId;

    /**
     * Einbinden des apiCalls (siehe Config) und Abfragen der Tags und Kommentare bei der pr0gramm-API
     */
    require_once($apiCall);
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
    "<div class='col-x-4 col-s-4 col-m-3 col-l-2 col-xl-2'>Confidence</div>".PHP_EOL.
    "<div class='col-x-8 col-s-8 col-m-9 col-l-10 col-xl-10'>Tag</div>".PHP_EOL.
    "</div>".PHP_EOL;
    foreach($sortArray as $key => $confidence) {
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-4 col-s-4 col-m-3 col-l-2 col-xl-2".($confidence < 0.2 ? " warn" : "")."'>".$confidence."</div>".PHP_EOL.
      "<div class='col-x-8 col-s-8 col-m-9 col-l-10 col-xl-10'>".$tags[$key]['tag']."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }
    
    /**
     * Leeren der sortArray Variable.
     */
    unset($sortArray);

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
    asort($minusSortArray);
    arsort($userCommentScore);
    arsort($userCommentCount);

    /**
     * Anzeigen der beliebtesten Kommentare sortiert nach Benis absteigend
     */
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h3>Beliebteste Kommentare</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>ID</div>".PHP_EOL.
    "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>Benis</div>".PHP_EOL.
    "<div class='col-x-12 col-s-6 col-m-6 col-l-6 col-xl-6'>User</div>".PHP_EOL.
    "</div>".PHP_EOL;
    $count = 0;
    foreach($sortArray as $key => $score) {
      $count++;
      if($count > 15) {
        break;
      }
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$comments[$key]['id']."' rel='noopener' target='blank'>".$comments[$key]['id']."</a></div>".PHP_EOL.
      "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>".$score."</div>".PHP_EOL.
      "<div class='col-x-12 col-s-6 col-m-6 col-l-6 col-xl-6'><a href='https://pr0gramm.com/user/".$comments[$key]['name']."' rel='noopener' target='blank'>".$comments[$key]['name']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-0 col-m-0 col-l-0 col-xl-0'><div class='spacer-s'></div></div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der unbeliebtesten Kommentare sortiert nach Benis absteigend
     */
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h3>Unbeliebteste Kommentare</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>ID</div>".PHP_EOL.
    "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>Benis</div>".PHP_EOL.
    "<div class='col-x-12 col-s-6 col-m-6 col-l-6 col-xl-6'>User</div>".PHP_EOL.
    "</div>".PHP_EOL;
    $count = 0;
    foreach($minusSortArray as $key => $score) {
      $count++;
      if($count > 15) {
        break;
      }
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$comments[$key]['id']."' rel='noopener' target='blank'>".$comments[$key]['id']."</a></div>".PHP_EOL.
      "<div class='col-x-6 col-s-3 col-m-3 col-l-3 col-xl-3'>".$score."</div>".PHP_EOL.
      "<div class='col-x-12 col-s-6 col-m-6 col-l-6 col-xl-6'><a href='https://pr0gramm.com/user/".$comments[$key]['name']."' rel='noopener' target='blank'>".$comments[$key]['name']."</a></div>".PHP_EOL.
      "<div class='col-x-12 col-s-0 col-m-0 col-l-0 col-xl-0'><div class='spacer-s'></div></div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der User mit dem meisten Benis auf Kommentare unter dem Post
     */
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h3>User mit dem meisten Benis auf Kommentare (in Summe)</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-6 col-s-6 col-m-4 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-6 col-s-6 col-m-8 col-l-9 col-xl-9'>Benis</div>".PHP_EOL.
    "</div>".PHP_EOL;
    $count = 0;
    foreach($userCommentScore as $user => $score) {
      $count++;
      if($count > 15) {
        break;
      }
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-6 col-s-6 col-m-4 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$user."' rel='noopener' target='blank'>".$user."</a></div>".PHP_EOL.
      "<div class='col-x-6 col-s-6 col-m-8 col-l-9 col-xl-9'>".$score."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }

    /**
     * Anzeigen der User mit den meisten Kommentaren unter dem Post
     */
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h3>User mit den meisten Kommentaren</h3>".PHP_EOL;
    $content.= "<div class='row highlight bold bordered'>".PHP_EOL.
    "<div class='col-x-6 col-s-6 col-m-4 col-l-3 col-xl-3'>User</div>".PHP_EOL.
    "<div class='col-x-6 col-s-6 col-m-8 col-l-9 col-xl-9'>Anzahl Kommentare</div>".PHP_EOL.
    "</div>".PHP_EOL;
    $count = 0;
    foreach($userCommentCount as $user => $commentCount) {
      $count++;
      if($count > 15) {
        break;
      }
      $content.= "<div class='row hover bordered'>".PHP_EOL.
      "<div class='col-x-6 col-s-6 col-m-4 col-l-3 col-xl-3'><a href='https://pr0gramm.com/user/".$user."' rel='noopener' target='blank'>".$user."</a></div>".PHP_EOL.
      "<div class='col-x-6 col-s-6 col-m-8 col-l-9 col-xl-9'>".$commentCount."</div>".PHP_EOL.
      "</div>".PHP_EOL;
    }
  } else {
    $content.= "<div class='spacer-m'></div>".PHP_EOL;
    $content.= "<h1>Fehler</h1>".PHP_EOL;
    $content.= "<div class='warnbox'>Keine Post-ID erkennbar</div>".PHP_EOL;
  }
}
?>
