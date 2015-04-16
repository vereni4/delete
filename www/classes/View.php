<?php

class View extends SiteCore {

  public function processingPOST() {
    
    $_title       = trim(strip_tags($_POST['comment-title']));
    $_text        = trim(strip_tags($_POST['comment-text']));
    $_article_id  = trim(strip_tags($_POST['article_id']));
    $_author_id   = $this -> currentUserID;
    $_date        = date('Y-m-d h:i:s A', time());
    
    $_title = (!empty($_title)) ? $_title : $this -> textTrunc($_text, 15);

    if (empty($_text)) {
      echo SiteLang::getRending('ERROR_8');
    }
    else {

      $_query_string =
        "INSERT INTO comments_" . $_SESSION['language'] . "
          (article_id, author_id, title, text, date)
        VALUES
          ('$_article_id', '$_author_id', '$_title', '$_text', '$_date')";
      try {
        $_result = $this -> dataBase -> query($_query_string);
      }
      catch (PDOException $e) {
        echo $e -> getMessage();
      }

      if ($_result) {
        header('Location:?option=View&id_text=' . $_article_id);
        exit();
      }
    }
  }

  public function getContent(){

    echo '<div id="main">';

    if (isset($_GET['id_text'])) {

      $_id_text = (int) $_GET['id_text'];

      if ($_id_text) {

        $_query_string =
          "SELECT
            id, title, author, date, img_src, text
          FROM
            article_" . $_SESSION['language'] . "
          WHERE
            id = $_id_text";
        try {
          $_result = $this -> dataBase -> query($_query_string);
        }
        catch (PDOException $e) {
          echo $e -> getMessage();
        }

        if ($_result && $_row = $_result -> fetch()) {

          printf("<p class='heading2' style='font-size:18px'>%s</p>
                  <p class='author'>%s | %s</p>
                  <p><img style='margin-right:5px; width: 150px; float: left' src='%s' alt=''>%s</p>
                  ", $_row['title'], $_row['author'], $_row['date'], $_row['img_src'], $_row['text']);
          
          if (!empty($this -> currentUser)) {
            echo "<a href='?option=ArticleEdit&amp;id_text=" . $_row['id'] . "'>"
                  . SiteLang::getRending('EDIT') . "</a>
                  <a href='?option=ArticleDelete&amp;delete_id_text=" . $_row['id']
                  . "'>" . SiteLang::getRending('DELETE') . "</a></p>";

          include 'include/comments.php';
          
          }
        }
        else {
          echo SiteLang::getRending('ERROR_6') . ' ' . $id_text;
        }
      }
      else {
        echo SiteLang::getRending('ERROR_7');
      }
    }
    else {
      echo SiteLang::getRending('ERROR_7');
    }
    echo '</div>';
  }
}

?>
