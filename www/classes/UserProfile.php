<?php

class UserProfile extends SiteCore {

  public function getContent(){

    echo '<div id="main">';

    if (isset($_GET['user_id'])) {

      $_user_id = (int) $_GET['user_id'];

      if ($_user_id) {

        $_query_string =
          "SELECT
            id, login, email, name, surname, registration_date, date_of_visit, avatar
          FROM
            users
          WHERE
            id = $_user_id";
        try {
          $_result = $this -> dataBase -> query($_query_string);
        }
        catch (PDOException $e) {
          echo $e -> getMessage();
        }

        if ($_result && $_row = $_result -> fetch()) {

          if ($_row['id'] == $this -> currentUserID && !empty($this -> currentUser)) {
            $_edit_text = '<a href="?option=ProfileEdit&user_id=' . $_row['id'] . '">'
                          . SiteLang::getRending('EDIT') . '</a>
                          <a href="?option=ProfileDelete&delete_id_user=' . $_row['id']. '">'
                          . SiteLang::getRending('DELETE') . '</a>';
          }
          else {
            $_edit_text = '';
          }

          $_avatar_src = 'file/' . $_row['avatar'];

          if (!file_exists($_avatar_src) || !is_file($_avatar_src)) {
            $_avatar_src = 'images/no-image.png';
          }

          echo '
          <p class="heading2">' . $_row['login'] . '</p>
          <img style="margin-right: 5px; width: 150px; height: 150px; float: left" src="' . $_avatar_src . '" alt="">'
          . ((!empty($this -> currentUser)) ? '<p>' . SiteLang::getRending('EMAIL') . ' ' . $_row['email'] . '</p>' : '') .
          '<p>' . SiteLang::getRending('NAME') . ' (' . SiteLang::getRending('SURNAME') . '): '
          . $_row['name'] . ' ' . $_row['surname'] . '</p>
          <p>' . SiteLang::getRending('REG_DATE') . ': ' . $_row['registration_date'] . '</p>
          <p>' . SiteLang::getRending('DATE_OF_VISIT') . ': ' . $_row['date_of_visit'] . '</p>'
          . $_edit_text . '
          ';
        }
        else {
          echo SiteLang::getRending('ERROR_6') . ' ' . $_user_id;
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
