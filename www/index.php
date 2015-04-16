<?php

session_start();
header('Content-Type: text/html; charset= UTF-8');

require_once('config.php');
require_once('classes/SiteCore.php');
require_once('classes/SiteLang.php');

if (isset($_GET['language'])) {
  $_SESSION['language'] = trim(strip_tags($_GET['language']));
}
elseif (!isset($_GET['language']) && !isset($_SESSION['language'])) {
  $_SESSION['language'] = 'en';
}
if ($_SESSION['language'] <> 'en' && $_SESSION['language'] <> 'ua') {
  $_SESSION['language'] = 'en';
}

$_included_file = './languages/' . $_SESSION['language'] . '.php';
if (file_exists($_included_file)) {
  $_lang_array_ = include $_included_file;
}
else {
  $_lang_array_ = include './languages/en.php';
}

$_class = 'Main';

if (isset($_GET['option'])) {
  $_class = trim(strip_tags($_GET['option']));
}

$_included_file = 'classes/' . $_class . '.php';

if (file_exists($_included_file)) {
  
  include_once($_included_file);
  
  if (class_exists($_class)) {
    $_body_object = new $_class();
    $_body_object -> getBody();
  }
  else {
    exit(SiteLang::getRending('ERROR_1'));
  }
}
else {
  exit(SiteLang::getRending('ERROR_2'));
}

?>
