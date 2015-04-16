<?php

class SiteLang {

  static function getRending($_key) {
    $_lang_array = $GLOBALS['_lang_array_'];

    if (!empty($_lang_array[$_key])) {
      return $_lang_array[$_key];
    }
    else {
      return $_key;
    }
  }
  
}

?>
