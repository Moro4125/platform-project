<?php
/**
 * Bootstrap for current project structure.
 */
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/sources/application.php';
require __DIR__.'/vendor/moro/platform-cms/bootstrap.php';
require __DIR__.'/sources/registers.php';
require __DIR__.'/sources/controllers.php';

return php_sapi_name() === 'cli'
	? Application::getInstance()->offsetGet('console')
	: Application::getInstance();
