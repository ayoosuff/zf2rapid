#!/usr/bin/env php
<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use Zend\I18n\Translator\Translator;
use ZF2rapid\Console\Application;
use ZFrapidCore\Console\Console;

// define application root
define('ZF2RAPID_ROOT', __DIR__ . '/..');
define('APPLICATION_NAME', 'ZF2rapid');
define('APPLICATION_SLOGAN', 'Rapid Application Development Tool for the ZF2');
define('APPLICATION_VERSION', '0.8.0');

// get vendor autoloading
if (file_exists(ZF2RAPID_ROOT . '/vendor/autoload.php')) {
    require ZF2RAPID_ROOT . '/vendor/autoload.php';
} elseif (file_exists(ZF2RAPID_ROOT . '/../../../vendor/autoload.php')) {
    require ZF2RAPID_ROOT . '/../../../vendor/autoload.php';
} else {
    fwrite(STDERR, "Unable to setup autoloading; aborting\n");
    exit(2);
}

// set locale
Locale::setDefault('en_US');

// setup translator
$translator = new Translator();
$translator->addTranslationFilePattern(
    'PhpArray',
    ZF2RAPID_ROOT . '/language',
    '%s.php',
    'default'
);

// setup console
$console = new Console();
$console->setTranslator($translator);

// configure applications
$application = new Application(
    APPLICATION_NAME,
    APPLICATION_SLOGAN,
    APPLICATION_VERSION,
    include ZF2RAPID_ROOT . '/config/routes.php',
    $console,
    $translator
);

// run application
$exit = $application->run();
exit($exit);
