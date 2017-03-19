<?php
/**
 * File for providers and services initialization.
 */
Application::getInstance(function (Application $app)
{
	// Security rules.
	$adminPrefix = (!defined('INDEX_PAGE') || INDEX_PAGE !== 'admin')
		? '^/admin'
		: '^.*';

	$app['security.access_rules'] = array_merge([
		[$adminPrefix.'/panel/shop/orders?', 'ROLE_RS_ORDERS'],
		[$adminPrefix.'/panel/shop',         'ROLE_USER'],
	], $app['security.access_rules']);

	// Twig templates.
	$app->update(
		'twig.loader.filesystem',
		null,
		function (\Twig_Loader_Filesystem $twigLoaderFilesystem) use ($app) {
			$twigLoaderFilesystem->addPath(dirname(__DIR__).DIRECTORY_SEPARATOR.'views');
			$twigLoaderFilesystem->addPath(dirname(__DIR__).DIRECTORY_SEPARATOR.'views', 'Application');

			return $twigLoaderFilesystem;
		}
	);
});
