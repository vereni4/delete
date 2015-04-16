<?php

abstract class SiteCore {
  
  protected $dataBase;
  protected $currentUser;
  protected $currentUserID;
  protected $languages;
  
  protected function getHeader() {
    include 'include/header.php';
  }
  
  protected function getLeftBar() {
    include 'include/left_bar.php';
  }
  
  protected function getMenu() {
    include 'include/menu.php';
  }
  
  protected function getFooter() {
    include 'include/footer.php';
  }
  
  abstract function getContent();
  
  public function getBody() {

    if (!empty($_POST) || isset($_GET['Exit'])
        || isset($_GET['delete_id_text']) || isset($_GET['delete_id_user'])
        || isset($_GET['delete_id_comment'])) {
      $this -> processingPOST();
    }

    $this -> getHeader();
    $this -> getLeftBar();
    $this -> getMenu();
    $this -> getContent();
    $this -> getFooter();
  }

  protected function pagination($_links, $_linked, $_sum_pages, $_page) {
    
    $_link = '<div class="pagination">';
    $_lang = array('page_back' => SiteLang::getRending('BACK'), 'page_next' => SiteLang::getRending('NEXT'));
    
    if ($_page > 1) {
      $_link .= '<a href="' . $_links . ($_page - 1) . $_linked . '">' . $_lang['page_back'] . '</a> ';
    }
    else {
      $_link .= ' ' . $_lang['page_back'] . ' ';
    }
    for ($i = 1; $i <= $_sum_pages; $i++) {
     $_link .= ($i == $_page)? $i : ' <a href="' . $_links . $i . $_linked . '">' . $i . '</a> ';
    }

    if ($_page < $_sum_pages) {
      $_link .= ' <a href="' . $_links . ($_page + 1) . $_linked . '">' . $_lang['page_next'] . '</a>';
    }
    else {
      $_link .= ' ' . $_lang['page_next'] . ' ';
    }

    $_link .= '</div>';
    
    return $_link;
  }

  public function textTrunc($_str, $_maxLen) {
    if (mb_strlen($_str) > $_maxLen) {
      preg_match('/^.{0,' . $_maxLen . '} .*?/ui', $_str, $_match);
      return $_match[0] . '...';
    }
    else {
      return $_str;
    }
  }

  public function __construct() {

    $this -> languages = $GLOBALS['languages'];

    if (!empty($_SESSION['current_user']) && !empty($_SESSION['current_user_id'])) {
      $this -> currentUser = trim(strip_tags($_SESSION['current_user']));
      $this -> currentUserID = (int) $_SESSION['current_user_id'];
    }

    $_options = array(
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );
    try {
      $this -> dataBase = new PDO(
        "mysql:
        host=" . HOST . ";
        dbname=" . DATABASE . ";
        charset=" . CHARSET, USER, PASSWORD, $_options
      );
    }
    catch(PDOException $e) {
      exit($e -> getMessage());
    }
  }
}

?>
