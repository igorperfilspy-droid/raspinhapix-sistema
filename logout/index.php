<?php session_start();<?php $_SESSION =<?php [];<?php session_destroy();<?php if (ini_get("session.use_cookies"))<?php {<?php $params =<?php session_get_cookie_params();<?php setcookie(session_name(),<?php '',<?php time()<?php -<?php 42000,<?php $params["path"],<?php $params["domain"],<?php $params["secure"],<?php $params["httponly"]<?php );<?php 
}<?php header("Location:<?php /logout/logout.php");<?php 
exit;<?php 
?>