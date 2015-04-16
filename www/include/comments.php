<?php
if (empty($this -> currentUser)) {
  echo '<h2>Коментарі заборонені</h2>';
  echo '<p>Увійдіть, щоб залишити коментар.</p>';
  return;
}

$_config = 10;
$_page = isset($_GET['page'])? intval($_GET['page']) : 1;
if ($_page) {
    
  $_limits = ($_page - 1) * $_config;

  $_query_string =
    "SELECT
      comments.id, comments.article_id, comments.author_id, 
      users.login, comments.title, comments.text, comments.date
    FROM
      comments_" . $_SESSION['language'] . " AS comments
    LEFT JOIN users ON comments.author_id=users.id
    WHERE
      article_id = $_id_text
    ORDER BY
      date DESC
    LIMIT $_limits, $_config";
  try {
    $_result = $this -> dataBase -> query($_query_string);
  }
  catch (PDOException $e) {
    echo $e -> getMessage();
    echo '</div>';
    return;
  }

  echo '<div class="comments-template">
    <ol class="commentlist">

    <h3 id="respond">' . SiteLang::getRending('ADD_COMMENT') . '</h3>

    <form action="" method="post" id="commentform">

    <p>
      <input type="text" name="comment-title" id="comment-title" size="40" tabindex="1" />
      <label for="comment-title"><small>' . SiteLang::getRending('COMMENT_TITLE') . '</small></label>
    </p>

    <p>
      <textarea name="comment-text" id="comment" cols="60" rows="10" tabindex="4" required></textarea>
    </p>

    <p>
      <input name="comment-submit" type="submit" id="submit" tabindex="5" value="' . SiteLang::getRending('SEND') . '" />
      <input type="hidden" name="article_id" value="' . $_id_text . '" />
    </p>

    </form>
    ';

  while($_row = $_result -> fetch()) {

    echo '<li class="comment" id="comment-' . $_row['id'] . '">';
    echo '<p class="author"><a href="?option=UserProfile&user_id='
          . $_row['author_id']. '">' . $_row['login']. '</a> | ' . $_row['date'] . ' ';
    
    if (!empty($this -> currentUser) && $this -> currentUserID == $_row['author_id']) {
      echo "<a style='font-size:10px' href='?option=CommentDelete&delete_id_comment=" . $_row['id']
            . "&id_text=" . $_id_text . "'>" . SiteLang::getRending('DELETE') . "</a></p>";

    }

    echo '</p><p class="heading2" style="font-size:15px">' . $_row['title'] . '</p>';
    echo '<p>' . $_row['text'] . '</p>';

    echo '</li>';
  }

  echo '</ol>';

  $_count = $this -> dataBase -> query('SELECT count(0) FROM comments_' . $_SESSION['language']) -> fetch()['count(0)'];
  if ($_count > $_config) {
    $_pages = ceil($_count / $_config);
    echo $this -> pagination('?option=View&id_text=' . $_id_text . '&page=', '', $_pages, $_page);
  }

  echo '</div>';
}
?>