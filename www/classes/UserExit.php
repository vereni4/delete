<?php

class UserExit extends SiteCore {

  public function getContent() {
    return;
  }

  public function processingPOST() {
    if (isset($_GET['Exit'])) {
      $this -> currentUser = '';
      $_SESSION['current_user'] = '';
      header('Location:?option=Main');
      exit();
    }
    else {
      echo SiteLang::getRending('ERROR_11');
    }
  }
}

?>
