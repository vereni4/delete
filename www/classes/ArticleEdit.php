<?php

class ArticleEdit extends SiteCore {

  public function processingPOST() {
    
    if (empty($this -> currentUser)) {
      header('Location:?option=Main');
      exit();
    }

    foreach ($this -> languages as $_key_lang => $_lang) {
      if (!empty($_FILES['img_src_' . $_key_lang]['tmp_name'])) {
        if (!move_uploaded_file($_FILES['img_src_' . $_key_lang]['tmp_name'],
            'file/' . $_FILES['img_src_' . $_key_lang]['name'])) {
          echo SiteLang::getRending('ERROR_12');
          $_img_src = '';
        }
        else {
          $_img_src = 'file/' . $_FILES['img_src_' . $_key_lang]['name'];
        }
      }

      $_id_text   = $_POST['id_text_' . $_key_lang];
      $_title     = $_POST['title_' . $_key_lang];
      $_text      = $_POST['text_' . $_key_lang];
      $_category  = $_POST['category_' . $_key_lang];
      $_author    = $this -> currentUser;
      $_date      = date('Y-m-d', time());

      $_discription = $this -> textTrunc($_POST['text_' . $_key_lang], 150);

      if (empty($_title) || empty($_text) || empty($_category)) {
        echo SiteLang::getRending('ERROR_13');
        return;
      }

      if (empty($_id_text)) {
        $_query_string =
          "INSERT INTO article_" . $_key_lang . "
            (title, author, date, img_src, text, discription, id_category)
          VALUES
            ('$_title', '$_author', '$_date', '$_img_src', '$_text', '$_discription', '$_category')";
      }
      else {
        $_empty_img = (empty($_img_src)) ? '' : "img_src='$_img_src'," ;
        $_query_string =
          "UPDATE
            article_" . $_key_lang . "
          SET
            title='$_title',
            author='$_author',
            date='$_date',
            " . $_empty_img . "
            text='$_text',
            discription='$_discription',
            id_category='$_category'
          WHERE
            id='$_id_text'";
      }

      try {
        $_result = $this -> dataBase -> prepare($_query_string) -> execute();
        if (empty($_id_text)) {
          header('Location:?option=View&id_text=' . $this -> dataBase -> lastInsertId());
        }
        else {
          header('Location:?option=View&id_text=' . $_id_text);
        }
      }
      catch (PDOException $e) {
        echo SiteLang::getRending('ERROR_14');
        echo $e -> getMessage();
      }
    }
  }

  public function getContent() {

    echo '<div id="main">';
    echo '<form enctype="multipart/form-data" action="" method="POST">';
    if (empty($this -> currentUser)) {
      echo SiteLang::getRending('ERROR_15');
      echo '</form></div>';
      return;
    }

    echo '<p id="article-edit">';
    foreach ($this -> languages as $_key_lang => $_lang) {
      echo '<span id="article-languages-' . $_key_lang . '"> ' . $_lang . ' </span>';
    }
    echo '</p>';

    foreach ($this -> languages as $_key_lang => $_lang) {

      if (isset($_GET['id_text'])) {

        $_id_text = (int) $_GET['id_text'];

        if ($_id_text) {

          $_query_string =
            "SELECT
              id, title, text, id_category
            FROM
              article_" . $_key_lang . "
            WHERE
              id = $_id_text";
          try {
            $_result = $this -> dataBase -> query($_query_string);
          }
          catch (PDOException $e) {
            echo $e -> getMessage();
          }

          if ($_result && $_row = $_result -> fetch()) {

            echo '
            <div id="article-edit-' . $_key_lang . '">
              <p>' . SiteLang::getRending('ARTICLE_TITLE') . '<br />
                <input type="text" name="title_' . $_key_lang . '" style="width:420px" value="' . $_row['title'] . '">
                <input type="hidden" name="id_text_' . $_key_lang . '" value="' . $_row['id'] . '">
              </p>
              <p>' . SiteLang::getRending('IMAGE') . '<br />
                <input type="file" name="img_src_' . $_key_lang . '">
              </p>
              <p>' . SiteLang::getRending('ARTICLE_TEXT') . '<br />
                <textarea name="text_' . $_key_lang . '" cols="50" rows="7">' . $_row['text'] . '</textarea>
              </p>
              <select name="category_' . $_key_lang . '">
              ';
              
              $_categories = $this -> getCategories($_key_lang);

              foreach ($_categories as $_row_category) {
                if ($_row_category['id_category'] == $_row['id_category']) {
                  echo '<option selected value="' . $_row_category['id_category'] . '">'
                    . $_row_category['name_category'] . '</option>';
                }
                else {
                  echo '<option value="' . $_row_category['id_category'] . '">'
                    . $_row_category['name_category'] . '</option>';
                }
              }
              echo '
              </select>
              <p>
                <input type="submit" name="button_' . $_key_lang . '" value="' . SiteLang::getRending('SAVE') . '">
              </p>
            </div>';
          }
          else {
            echo SiteLang::getRending('ERROR_6') . ' ' . $id_text;
          }
        }
        else {
          echo SiteLang::getRending('ERROR_7');
        }
      }
      else {

        $_categories = $this -> getCategories($_key_lang);

        echo '
        <div id="article-edit-' . $_key_lang . '">
          <p>' . SiteLang::getRending('ARTICLE_TITLE') . '<br />
            <input type="text" name="title_' . $_key_lang . '" style="width:420px">
          </p>
          <p>' . SiteLang::getRending('IMAGE') . '<br />
            <input type="file" name="img_src_' . $_key_lang . '">
          </p>
          <p>' . SiteLang::getRending('ARTICLE_TEXT') . '<br />
            <textarea name="text_' . $_key_lang . '" cols="50" rows="7"></textarea>
          </p>
          <select name="category_' . $_key_lang . '">
          ';
          
          foreach ($_categories as $_row) {
            echo '<option value="' . $_row['id_category'] . '">' . $_row['name_category'] . '</option>';
          }
          echo '
          </select>
          <p>
            <input type="submit" name="button_' . $_key_lang . '" value="' . SiteLang::getRending('SAVE') . '">
          </p>
        </div>';
      }
    }
    echo '</form></div>';
  }

  protected function getCategories($_language = 'en') {

    $_query_string = 
      'SELECT
        id_category, name_category
      FROM
        category_' . $_language . '
      ORDER BY
        id_category';

    try {
      $_result = $this -> dataBase -> query($_query_string);
      $_rows = array();
      while ($_row = $_result -> fetch()) {
        $_rows[] = $_row;
      }       
    } 
    catch (PDOException $e) {
      echo $e -> getMessage();
    }
    return $_rows;
  }
}

?>
