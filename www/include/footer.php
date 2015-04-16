<?php

$_query_string =
  'SELECT
    id_menu, name_menu
  FROM
    menu_' . $_SESSION['language'] . '
  ORDER BY
    id_menu';

echo '</div>
      <div id="bottom">
      <div class="toplinks" style="padding-left:127px;"><a href="?option=Main">' . SiteLang::getRending('MAIN') . '</a></div>
      <div class="sap2">::</div>';

try {

  $_result = $this -> dataBase -> query($_query_string);

  while ($_row = $_result -> fetch()) {
    echo '<div class="toplinks"><a href="?option=Menu&id_menu='
          . $_row['id_menu'] . '">'
          . $_row['name_menu'] . '</a></div>
          <div class="sap2">::</div>';
  }
}
catch (PDOException $e) {
  echo $e->getMessage();
}

echo '</div>
      <div class="copy"><span class="style1">' . SiteLang::getRending('COPYRIGHT') . '</span>
      </div>
      </div>
      </body></html>';
?>
