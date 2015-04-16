<?php

class ArticleDelete extends SiteCore {

  public function getContent() {
    return;
  }

  public function processingPOST() {

    if (empty($this -> currentUser)) {
      header('Location:?option=Main');
      exit();
    }

    if (isset($_GET['delete_id_text'])) {

      $_id_text = (int) $_GET['delete_id_text'];

      if ($_id_text) {

        foreach ($this -> languages as $_key_lang => $_lang) {
          $_query_string =
            "DELETE FROM
              article_" . $_key_lang . "
            WHERE
              id = $_id_text";
          try {
            $_result = $this -> dataBase -> query($_query_string);
          }
          catch (PDOException $e) {
            echo $e -> getMessage();
          }

          if ($_result) {
            $_is_ok = 1;
          }
          else {
            $_is_ok = 0;
            echo SiteLang::getRending('ERROR_17') . ' ' . $id_text;
          }
        }
        
        if ($_is_ok) {
            header('Location:?option=Main');
            exit();
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
