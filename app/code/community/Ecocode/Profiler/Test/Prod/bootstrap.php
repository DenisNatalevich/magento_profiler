<?php

if (isset($_SERVER['MAGENTO_DIRECTORY'])) {
    $_baseDir = $_SERVER['MAGENTO_DIRECTORY'];
} else {
    $_baseDir = getcwd();
}

/**
 * Define MAGE_PATH
 * Path to Magento
 */
define('MAGENTO_ROOT', $_baseDir);

// Include Mage file by detecting app root
require_once $_baseDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';

if (!Mage::isInstalled()) {
    echo 'Magento Unit Tests can run only on installed version';
    exit(1);
}


/* Replace server variables for proper file naming */
$_SERVER['SCRIPT_NAME']     = $_baseDir . DS . 'index.php';
$_SERVER['SCRIPT_FILENAME'] = $_baseDir . DS . 'index.php';




//flag to check for unittets
define('BASE_TESTS_PATH', realpath(dirname(__FILE__)));
require_once BASE_TESTS_PATH . '/../TestHelper.php';

Mage::app()->cleanCache();
// Removing Varien Autoload, to prevent errors with PHPUnit components
spl_autoload_unregister(array(\Varien_Autoload::instance(), 'autoload'));
spl_autoload_register(function ($className) {
    $filePath = strtr(
        ltrim($className, '\\'),
        array(
            '\\' => '/',
            '_'  => '/'
        )
    );
    @include $filePath . '.php';
});