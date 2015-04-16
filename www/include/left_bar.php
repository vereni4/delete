<?php

echo '<div class="quick-bg">
      <div id="spacer">
        <div id="rc-bg">' . SiteLang::getRending('MENU') . '</div>
      </div>';

$_query_string = 
  'SELECT
    id_category, name_category
  FROM
    category_' . $_SESSION['language'] . '
  ORDER BY
    id_category';

try {
  $_result = $this -> dataBase -> query($_query_string);
  while ($_row = $_result -> fetch()) {
    echo '<div class="quick-links">» <a href="
          ?option=Category&amp;id_category='
          . $_row['id_category'] . '">'
          . $_row['name_category'] . '</a>
          </div>';
  }       
} 
catch (PDOException $e) {
  echo $e -> getMessage();
}

if (!empty($this -> currentUser)) {
  echo '<hr><div class="quick-links"><a href="?option=ArticleEdit">'
    . SiteLang::getRending('ARTICLE_INSERT') . '</a></div>';
}
echo '</div>';

?>
