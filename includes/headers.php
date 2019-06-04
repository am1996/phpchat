<?php
session_start();
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
define('SECRET',"131dfvgasd21as");
define('BASE_URL',"$protocol$_SERVER[HTTP_HOST]");
include("./core/configs.php");
include("./core/database.php");
include("./core/user.php");
include("./core/signsys.php");
include("./core/csrf.php");
?>