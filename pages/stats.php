<?php
/**
 * pages/stats.php
 * 
 * Stats page
 */

/**
 * Inclusion of the cookieCheck.
 */
require_once(INCLUDE_DIR.'session'.DIRECTORY_SEPARATOR.'check.php');


/**
 * Title
 */
$title = "Statistiken";
$content.= "<h1>Statistiken</h1>";

/**
 * Allgemeine Infos und Links
 */
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-12'>Eingeloggt als: <span class='warn bold'>".$userRow['username']."</span></div>".
"</div>";

/**
 * Bookmarklet
 */
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-12'>Bookmarklet: <a href=\"javascript:void(window.open('https://".$_SERVER['SERVER_NAME']."/stats?post='+encodeURIComponent(location.href)))\">Check Post</a></div>".
"</div>";

/**
 * Form to enter a to be checked post id
 */
$content.= "<h2>Post prüfen</h2>";
$content.= "<form action='/stats' method='post'>";
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-12'><input type='text' name='postId' placeholder='Post-ID oder ganzer Link' autofocus tabindex='1' autocomplete='off'></div>".
"</div>";
$content.= "<div class='row'>".
"<div class='col-s-12 col-l-12'><input type='submit' name='submit' value='Prüfen' tabindex='2'></div>".
"</div>";
$content.= "</form>";

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
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h2>Auswertung - Post-ID <a href='https://pr0gramm.com/new/".$postId."' rel='noopener' target='blank'>".$postId."</a></h2>";
    $title = "Auswertung - Post-ID ".$postId;

    /**
     * Einbinden des apiCalls (siehe Config) und Abfragen der Tags und Kommentare bei der pr0gramm-API
     */
    require_once($apiCall);
    $response = apiCall("https://pr0gramm.com/api/items/info?itemId=".$postId);

    /**
     * Löschung der alten Daten
     */
    mysqli_query($dbl, "DELETE FROM `tags` WHERE `postId`='".$postId."'") OR DIE(MYSQLI_ERROR($dbl));
    mysqli_query($dbl, "DELETE FROM `comments` WHERE `postId`='".$postId."'") OR DIE(MYSQLI_ERROR($dbl));

    /**
     * Einfügen der Tags in die Datenbank
     */
    foreach($response['tags'] as $key => $value) {
      mysqli_query($dbl, "INSERT INTO `tags` (`postId`, `tag`, `confidence`) VALUES ('".$postId."', '".defuse($value['tag'])."', '".defuse($value['confidence'])."')") OR DIE(MYSQLI_ERROR($dbl));
    }

    /**
     * Einfügen der Kommentare in die Datenbank
     */
    foreach($response['comments'] as $key => $value) {
      mysqli_query($dbl, "INSERT INTO `comments` (`postId`, `commentId`, `score`, `up`, `down`, `username`) VALUES ('".$postId."', '".defuse($value['id'])."', '".defuse(($value['up']-$value['down']))."', '".defuse($value['up'])."', '".defuse($value['down'])."', '".defuse($value['name'])."')") OR DIE(MYSQLI_ERROR($dbl));
    }

    /**
     * Tags nach Confidence
     */
    $content.= "<h3>Tags nach Confidence</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-4 col-l-2'>Confidence</div>".
    "<div class='col-s-8 col-l-10'>Tag</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT * FROM `tags` WHERE `postId`='".$postId."' ORDER BY `confidence` DESC") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-4 col-l-2".($row['confidence'] < 0.2 ? " warn" : "")."'>".$row['confidence']."</div>".
      "<div class='col-s-8 col-l-10'>".$row['tag']."</div>".
      "</div>";
    }

    /**
     * Anzeigen der beliebtesten Kommentare sortiert nach Benis absteigend
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>Beliebteste Kommentare, sortiert nach Gesamtbenis</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-3 col-l-3'>ID</div>".
    "<div class='col-s-3 col-l-3'>Benis</div>".
    "<div class='col-s-6 col-l-6'>User</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT * FROM `comments` WHERE `postId`='".$postId."' ORDER BY `score` DESC LIMIT 15") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-3 col-l-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$row['commentId']."' rel='noopener' target='blank'>".$row['commentId']."</a></div>".
      "<div class='col-s-3 col-l-3'>".$row['score']." <span class='darken'>(".$row['up']."/".$row['down'].")</span></div>".
      "<div class='col-s-6 col-l-6'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-0 col-l-0'><div class='spacer-s'></div></div>".
      "</div>";
    }

    /**
     * Anzeigen der unbeliebtesten Kommentare sortiert nach Benis aufsteigend
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>Unbeliebteste Kommentare, sortiert nach Gesamtbenis</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-3 col-l-3'>ID</div>".
    "<div class='col-s-3 col-l-3'>Benis</div>".
    "<div class='col-s-6 col-l-6'>User</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT * FROM `comments` WHERE `postId`='".$postId."' ORDER BY `score` ASC LIMIT 15") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-3 col-l-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$row['commentId']."' rel='noopener' target='blank'>".$row['commentId']."</a></div>".
      "<div class='col-s-3 col-l-3'>".$row['score']." <span class='darken'>(".$row['up']."/".$row['down'].")</span></div>".
      "<div class='col-s-6 col-l-6'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-0 col-l-0'><div class='spacer-s'></div></div>".
      "</div>";
    }

    /**
     * Anzeigen der Kommentare mit dem meisten Plus
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>Kommentare mit dem meisten Plus</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-3 col-l-3'>ID</div>".
    "<div class='col-s-3 col-l-3'>Benis</div>".
    "<div class='col-s-6 col-l-6'>User</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT * FROM `comments` WHERE `postId`='".$postId."' ORDER BY `up` DESC LIMIT 5") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-3 col-l-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$row['commentId']."' rel='noopener' target='blank'>".$row['commentId']."</a></div>".
      "<div class='col-s-3 col-l-3'>".$row['score']." <span class='darken'>(".$row['up']."/".$row['down'].")</span></div>".
      "<div class='col-s-6 col-l-6'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-0 col-l-0'><div class='spacer-s'></div></div>".
      "</div>";
    }

    /**
     * Anzeigen der Kommentare mit dem meisten Minus
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>Kommentare mit dem meisten Minus</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-3 col-l-3'>ID</div>".
    "<div class='col-s-3 col-l-3'>Benis</div>".
    "<div class='col-s-6 col-l-6'>User</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT * FROM `comments` WHERE `postId`='".$postId."' ORDER BY `down` DESC LIMIT 5") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-3 col-l-3'><a href='https://pr0gramm.com/new/".$postId.":comment".$row['commentId']."' rel='noopener' target='blank'>".$row['commentId']."</a></div>".
      "<div class='col-s-3 col-l-3'>".$row['score']." <span class='darken'>(".$row['up']."/".$row['down'].")</span></div>".
      "<div class='col-s-6 col-l-6'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-0 col-l-0'><div class='spacer-s'></div></div>".
      "</div>";
    }

    /**
     * Anzeigen der User mit dem meisten Benis auf Kommentare unter dem Post
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>User mit dem meisten Benis auf Kommentare (in Summe)</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-6 col-l-3'>User</div>".
    "<div class='col-s-6 col-l-9'>Benis</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT sum(`score`) AS `totalBenis`, sum(`up`) AS `totalUp`, sum(`down`) AS `totalDown`, `username` FROM `comments` WHERE `postId`='".$postId."' GROUP BY `username` ORDER BY `totalBenis` DESC LIMIT 15") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-6 col-l-3'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-6 col-l-9'>".$row['totalBenis']." <span class='darken'>(".$row['totalUp']."/".$row['totalDown'].")</span></div>".
      "</div>";
    }

    /**
     * Anzeigen der User mit dem wenigsten Benis auf Kommentare unter dem Post
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>User mit dem wenigsten Benis auf Kommentare (in Summe)</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-6 col-l-3'>User</div>".
    "<div class='col-s-6 col-l-9'>Benis</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT sum(`score`) AS `totalBenis`, sum(`up`) AS `totalUp`, sum(`down`) AS `totalDown`, `username` FROM `comments` WHERE `postId`='".$postId."' GROUP BY `username` ORDER BY `totalBenis` ASC LIMIT 15") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-6 col-l-3'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-6 col-l-9'>".$row['totalBenis']." <span class='darken'>(".$row['totalUp']."/".$row['totalDown'].")</span></div>".
      "</div>";
    }

    /**
     * Anzeigen der User mit den meisten Kommentaren (Anzahl)
     */
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h3>User mit den meisten Kommentaren (Anzahl)</h3>";
    $content.= "<div class='row highlight bold bordered'>".
    "<div class='col-s-6 col-l-3'>User</div>".
    "<div class='col-s-6 col-l-9'>Anzahl Kommentare</div>".
    "</div>";
    $result = mysqli_query($dbl, "SELECT count(`username`) AS `totalCount`, `username` FROM `comments` WHERE `postId`='".$postId."' GROUP BY `username` ORDER BY `totalCount` DESC LIMIT 15") OR DIE(MYSQLI_ERROR($dbl));
    while($row = mysqli_fetch_array($result)) {
      $content.= "<div class='row hover bordered'>".
      "<div class='col-s-6 col-l-3'><a href='https://pr0gramm.com/user/".$row['username']."' rel='noopener' target='blank'>".$row['username']."</a></div>".
      "<div class='col-s-6 col-l-9'>".$row['totalCount']."</div>".
      "</div>";
    }
  } else {
    $content.= "<div class='spacer-m'></div>";
    $content.= "<h1>Fehler</h1>";
    $content.= "<div class='warnbox'>Keine Post-ID erkennbar</div>";
  }
}
?>
