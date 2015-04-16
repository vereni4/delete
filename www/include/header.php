<?
echo '
  <!DOCTYPE html>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>' . SiteLang::getRending('TITLE') . '</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <script title="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
    <script title="text/javascript" src="/js/MainScript.js"></script>
  </head>
  <body>
      <div id="language">
      <p>' . SiteLang::getRending('LANG') . ': 
        <a href="?language=en">
          <img style="margin-right: 1px; width: 17px" src="./images/flag_en.jpg" alt="">English</a> | 
        <a href="?language=ua">
          <img style="margin-right: 1px; width: 17px" src="./images/flag_ua.jpg" alt="">Українська</a>
      </p>
      </div>
      <div id="reg-auth">
      <p>
';
if (empty($this -> currentUser)) {
  echo '
    <a href="?option=Login">' . SiteLang::getRending('LOGIN') . '</a>
    <a href="?option=Registration">' . SiteLang::getRending('REGISTRATION') . '</a>';
}
else {
  echo '<a href="?option=UserProfile&user_id=' . $this -> currentUserID . '">' . $this -> currentUser . '</a>';
  echo '<a href="?option=UserExit&Exit=1">' . SiteLang::getRending('EXIT') . '</a>';
}
echo '      
      </p>
    </div>
     <div id="border">
       <div id="header">
          <div id="left">
            <div id="logo">
              <div class="name">' . SiteLang::getRending('TITLE') . '</div>
              <div class="tag"><p>' . SiteLang::getRending('LOGO') . '</p></div>
            </div>
          </div>
          <div id="car"></div>
        </div>
';
?>
