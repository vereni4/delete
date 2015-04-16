<?php

class Login extends SiteCore {

  public function processingPOST() {
    $_login = trim(strip_tags($_POST['login']));
    $_password = trim(strip_tags($_POST['password']));
    if (empty($_login) || empty($_password)) {
      echo SiteLang::getRending('ERROR_8');
    }
    else {
      $_password = md5($_password);

      $_query_string =
        "SELECT
          id
        FROM
          users
        WHERE
          login = '$_login' AND pass = '$_password'";
      try {
        $_result = $this -> dataBase -> query($_query_string);
      }
      catch (PDOException $e) {
        echo $e -> getMessage();
      }

      if ($_result && $_result -> rowCount() == 1 && $_row = $_result -> fetch()) {
        $_user_id = $_row['id'];
        $_date_of_visit = date('Y-m-d h:i:s A', time());

        $_SESSION['current_user'] = $_login;
        $_SESSION['current_user_id'] = $_user_id;

        $_query_string =
          "UPDATE
            users
          SET
            date_of_visit='$_date_of_visit'
          WHERE
            id='$_user_id'";

        try {
          $_result = $this -> dataBase -> prepare($_query_string) -> execute();
        }
        catch (PDOException $e) {}

        header('Location:?option=UserProfile&user_id=' . $_row['id']);
        exit();
      }
      else {
        echo SiteLang::getRending('ERROR_9');
      }
    }
  }

  public function getContent(){

    echo '<div id="main">';

    if (isset($this -> currentUser)) {
      echo SiteLang::getRending('ERROR_10');
    }
    else {
      echo '
      <form id="form_user" enctype="multipart/form-data" action="" method="POST">
        <p>
          <lable for="login">' . SiteLang::getRending('LOGIN_2') . '</lable>
          <input type="text" name="login" style="width:100px" required>
        </p>
        <p>
          <lable for="password">' . SiteLang::getRending('PASS') . '</lable>
          <input type="password" name="password" style="width:100px" required>
        </p>
        <p><input type="submit" name="button" value="' . SiteLang::getRending('LOGIN_3') . '"></p>
      </form>';
    }

    echo '</div>';
  }
}

?>
