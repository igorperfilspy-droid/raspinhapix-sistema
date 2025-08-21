<?php<?php 
session_start();<?php 
<?php 
$_SESSION<?php =<?php [];<?php 
<?php 
session_destroy();<?php 
<?php 
if<?php (ini_get("session.use_cookies"))<?php {<?php 
<?php $params<?php =<?php session_get_cookie_params();<?php 
<?php setcookie(session_name(),<?php '',<?php time()<?php -<?php 42000,<?php 
<?php $params["path"],<?php $params["domain"],<?php 
<?php $params["secure"],<?php $params["httponly"]<?php 
<?php );<?php 
}<?php 
<?php 
header("Location:<?php /logout/logout.php");<?php 
exit;<?php 
?>