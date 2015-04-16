<?php

class ProfileDelete extends SiteCore {

  public function getContent() {
    return;
  }

  public function processingPOST() {

    if (empty($this -> currentUser)) {
      header('Location:?option=Main');
      exit();
    }

    if (isset($_GET['delete_id_user'])) {

      $_user_id = (int) $_GET['delete_id_user'];

      if ($_user_id) {

        $_query_string =
          "DELETE FROM
            users
          WHERE
            id = $_user_id";
        try {
          $_result = $this -> dataBase -> query($_query_string);
        }
        catch (PDOException $e) {
          echo $e -> getMessage();
        }

        if ($_result) {
          header('Location:?option=UserExit&Exit=1');
          exit();
        }
        else {
          echo SiteLang::getRending('ERROR_17') . ' ' . $_user_id;
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
