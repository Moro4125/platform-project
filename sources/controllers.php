<?php
/**
 * File with controllers.
 */
namespace Action;
use \Application;

// ============================================== //
//   Раздел действий по генерации страниц сайта   //
// ============================================== //
Application::getInstance(function (Application $app)
{
	$actionRules = [
		'inner'         => ['/inner/{theme}_{code}.html',    'Tools\\InnerAction'],
		'index'         => ['/index.html',                   'Page\\IndexAction'],
	];

	$assertRules = [
		'id'        => '\\d+',
		'code'      => '[a-z][a-z0-9]*([-_.][a-z0-9]+)*',
		'page'      => '(\\d+/)?',
		'theme'     => '(.+)?',
		'heading_w' => 'holster|pounch|accessories|knife|paracord',
		'heading_e' => 'maxpedition',
		'heading_h' => 'pistol|instructions',
	];

	$convertRules = [
		'id' => 'str_to_int',
	];

	$prefix = '';
	$namespace = __NAMESPACE__;
	$allowPost = false;

	foreach ($actionRules as $route => list($pattern, $class))
	{
		if (is_int($route))
		{
			$prefix = $pattern;
			$namespace = $class;
			$allowPost = true;
			continue;
		}

		$class = $app->offsetGet($route.'.action.class', ($class[0] == '\\') ? $class : $namespace.'\\'.$class);
		$controller = $allowPost
			? $app->match(($pattern[0] == '/') ? $pattern : $prefix.$pattern, $class)->bind($route)
			: $app->get(  ($pattern[0] == '/') ? $pattern : $prefix.$pattern, $class)->bind($route);

		if (preg_match_all('{\\{(.+?)\\}}', $pattern, $matches, PREG_PATTERN_ORDER))
		{
			foreach ($matches[1] as $parameter)
			{
				isset($assertRules[$parameter])  && $controller->assert($parameter,  $assertRules[$parameter]);
				isset($convertRules[$parameter]) && $controller->convert($parameter, $convertRules[$parameter]);
			}
		}
	}
});