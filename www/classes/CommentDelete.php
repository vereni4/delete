<?php

class CommentDelete extends SiteCore {

  public function getContent() {
    return;
  }

  public function processingPOST() {

    if (empty($this -> currentUser)) {
      header('Location:?option=Main');
      exit();
    }

    if (isset($_GET['delete_id_comment'])) {

      $_comment_id = (int) $_GET['delete_id_comment'];
      $_text_id = (int) $_GET['id_text'];

      if ($_comment_id) {

        $_query_string =
          "DELETE FROM
            comments_" . $_SESSION['language'] . "
          WHERE
            id = $_comment_id";
        try {
          $_result = $this -> dataBase -> query($_query_string);
        }
        catch (PDOException $e) {
          echo $e -> getMessage();
        }

        if ($_result) {
          header('Location:?option=View&id_text=' . $_text_id);
          exit();
        }
        else {
          echo SiteLang::getRending('ERROR_17') . ' ' . $_comment_id;
        }
      }
      else {
        echo SiteLang::getRending('ERROR_18');
      }
    }
    else {
      echo SiteLang::getRending('ERROR_18');
    }
  }
}

?>
