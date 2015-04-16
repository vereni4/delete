<?php

echo '<div id="mainarea">
      <div class="heading">
      <div class="toplinks" style="padding-left:30px;"><a href="?option=Main">' . SiteLang::getRending('MAIN') . '</a></div>
      <div class="sap2">::</div>';
      
$_query_string = 'SELECT id_menu, name_menu FROM menu_' . $_SESSION['language'] . ' ORDER BY id_menu';
try {
  $_result = $this -> dataBase -> query($_query_string);
  while($_row = $_result -> fetch()) {
    echo '<div class="toplinks"><a href="?option=menu&amp;id_menu=' . $_row['id_menu'] . '">' . $_row['name_menu'] . '</a></div>
          <div class="sap2">::</div>';
  }
}
catch (PDOException $e) {
  echo $e -> getMessage();
}

echo '</div>';

?>
