<?php

class ProfileEdit extends SiteCore {

  public function processingPOST() {
    
    if (empty($this -> currentUser)) {
      header('Location:?option=Main');
      exit();
    }

    if (!empty($_FILES['avatar']['tmp_name'])) {
      if (!move_uploaded_file($_FILES['avatar']['tmp_name'],
          'file/' . $_FILES['avatar']['name'])) {
        echo SiteLang::getRending('ERROR_12');
        $_img_src = '';
      }
      else {
        $_img_src = $_FILES['avatar']['name'];
      }
    }

    $_user_id     = (int) $_POST['user_id'];
    $_login       = trim(strip_tags($_POST['login']));
    $_email       = trim(strip_tags($_POST['email']));
    $_name        = trim(strip_tags($_POST['user_name']));
    $_surname     = trim(strip_tags($_POST['user_surname']));
    $_password    = trim(strip_tags($_POST['password']));
    $_password_2  = trim(strip_tags($_POST['password_2']));

    if ((!empty($_password) || !empty($_password_2)) && $_password <> $_password_2) {
      echo SiteLang::getRending('ERROR_19');
      return;
    }
    elseif (!empty($_password) && !empty($_password_2)) {
      $_password = md5($_password);
    }
    else {
      $_password = '';
    }

    if (empty($_login) || empty($_email)) {
      echo SiteLang::getRending('ERROR_13');
      return;
    }

    $_empty_img = (empty($_img_src)) ? '' : "avatar='$_img_src'," ;
    $_empty_pass = (empty($_password)) ? '' : "pass='$_password'," ;
    $_query_string =
      "UPDATE
        users
      SET
        login='$_login',
        email='$_email',
        " . $_empty_img . "
        " . $_empty_pass . "
        name='$_name',
        surname='$_surname'
      WHERE
        id='$_user_id'";

    try {
      $_result = $this -> dataBase -> prepare($_query_string) -> execute();
      header('Location:?option=UserProfile&user_id=' . $_user_id);
    }
    catch (PDOException $e) {
      echo SiteLang::getRending('ERROR_14');
      echo $e -> getMessage();
    }
  }

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

          if ($_row['id'] <> $this -> currentUserID || empty($this -> currentUser)) {
            echo SiteLang::getRending('ERROR_15');
            echo '</div>';
            return;
          }

          echo '
          <p class="heading2">' . $_row['login'] . '</p>
          <form id="form_user" enctype="multipart/form-data" action="" method="POST">
            <p>
              <input type="file" name="avatar">
            </p>
            <p>
              <lable for="login">' . SiteLang::getRending('LOGIN_2') . '</lable>
              <input type="text" name="login" value="' . $_row['login'] . '" style="width:100px" required>
              <input type="hidden" name="user_id" value="' . $_row['id'] . '">
            </p>
            <p>
              <lable for="email">' . SiteLang::getRending('EMAIL') . '</lable>
              <input type="email" name="email" value="' . $_row['email'] . '" style="width:100px" required>
            </p>
            <p>
              <lable for="user_name">' . SiteLang::getRending('NAME') . ':</lable>
              <input type="text" name="user_name" value="' . $_row['name'] . '" style="width:100px">
            </p>
            <p>
              <lable for="user_surname">' . SiteLang::getRending('SURNAME') . ':</lable>
              <input type="text" name="user_surname" value="' . $_row['surname'] . '" style="width:100px">
            </p>
            <p>
              <lable for="password">' . SiteLang::getRending('PASS') . '</lable>
              <input type="password" name="password" style="width:100px">
            </p>
            <p>
              <lable for="password_2">' . SiteLang::getRending('PASS_2') . '</lable>
              <input type="password" name="password_2" style="width:100px">
            </p>
            <p>
              <input type="submit" name="button" value="' . SiteLang::getRending('SAVE') . '">
            </p>
          </form>
          <a href="?option=ProfileDelete&delete_id_user=' . $_row['id']. '">'
            . SiteLang::getRending('DELETE') . '</a>';
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
