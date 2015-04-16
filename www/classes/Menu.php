<?php

class Menu extends SiteCore {

  public function getContent(){

    echo '<div id="main">';

    if (isset($_GET['id_menu'])) {

      $_id_menu = (int) $_GET['id_menu'];

      if ($_id_menu) {

        $_query_string =
          "SELECT
            id_menu, name_menu, text_menu
          FROM
            menu_" . $_SESSION['language'] . "
          WHERE
            id_menu = $_id_menu";

        try {
          $_result = $this -> dataBase -> query($_query_string);
        }
        catch (PDOException $e) {
          echo $e -> getMessage();
        }

        if ($_result && $_row = $_result -> fetch()) {
          printf("<p class='heading2' style='font-size:18px'>%s</p>
                  <p>%s</p>
                  ", $_row['name_menu'], $_row['text_menu']);
        }
        else {
          echo SiteLang::getRending('ERROR_5');
        }
      }
      else {
        echo SiteLang::getRending('ERROR_5');
      }
    }
    else {
      echo SiteLang::getRending('ERROR_5');
    }

    echo '</div>';
  }
}

?>
