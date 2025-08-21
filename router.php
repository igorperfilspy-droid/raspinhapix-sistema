<?php
//<?php router.php
if<?php (php_sapi_name()<?php ==<?php 'cli-server')<?php {
<?php $path<?php =<?php parse_url($_SERVER['REQUEST_URI'],<?php PHP_URL_PATH);
<?php $fullPath<?php =<?php __DIR__<?php .<?php $path;
<?php if<?php ($path<?php !==<?php '/'<?php &&<?php file_exists($fullPath))<?php {
<?php return<?php false;
<?php }
}
require<?php __DIR__<?php .<?php '/index.php';
