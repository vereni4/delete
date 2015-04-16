<?php

class Registration extends SiteCore {

  public function processingPOST() {
    
    $_login       = trim(strip_tags($_POST['login']));
    $_password    = trim(strip_tags($_POST['password']));
    $_password_2  = trim(strip_tags($_POST['password_2']));
    $_email       = trim(strip_tags($_POST['email']));
    $_reg_date    = date('Y-m-d h:i:s A', time());
    
    if (empty($_login) || empty($_password) || empty($_password_2) || empty($_email)) {
      echo SiteLang::getRending('ERROR_8');
    }
    elseif ($_password <> $_password_2) {
      echo SiteLang::getRending('ERROR_19');
    }
    else {
      $_password = md5($_password);
      $_password_2 = md5($_password_2);

      $_query_string =
        "SELECT
          id 
        FROM
          users
        WHERE
          login = '$_login' AND pass = '$_password' AND pass = '$_email'";
      try {
        $_result = $this -> dataBase -> query($_query_string);
      }
      catch (PDOException $e) {
        exit($e -> getMessage());
      }

      if ($_result && $_result -> rowCount() == 1) {
        exit(SiteLang::getRending('ERROR_20'));
      }
      
      $_query_string =
        "INSERT INTO users
          (login, pass, email, registration_date, date_of_visit)
        VALUES
          ('$_login', '$_password', '$_email', '$_reg_date', '$_reg_date')";
      try {
        $_result = $this -> dataBase -> query($_query_string);
      }
      catch (PDOException $e) {
        echo $e -> getMessage();
      }

      if ($_result) {
        $_SESSION['current_user'] = $_login;
        $_SESSION['current_user_id'] = $this -> dataBase -> lastInsertId();
        header('Location:?option=UserProfile&user_id=' . $this -> dataBase -> lastInsertId());
        exit();
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
        <p>
          <lable for="password_2">' . SiteLang::getRending('PASS_2') . '</lable>
          <input type="password" name="password_2" style="width:100px" required>
        </p>
        <p>
          <lable for="email">' . SiteLang::getRending('EMAIL') . '</lable>
          <input type="email" name="email" style="width:100px" required>
        </p>
        <p><input type="submit" name="button" value="' . SiteLang::getRending('LOGIN_3') . '"></p>
      </form>';
    }

    echo '</div>';
  }
}

?>
