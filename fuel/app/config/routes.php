<?php
return array(
	'_root_'                  => 'uproda/index',  // The default route
	'_400_'                   => 'error/400',     // The main 404 route
	'_403_'                   => 'error/403',     // The main 404 route
	'_404_'                   => 'error/404',     // The main 404 route
	'_500_'                   => 'error/500',     // the main 500 route

	'(:num)'                  => [['get', new Route('uproda/index/$1')]],
	#'detail/thumbnail/(:any)' => [['get', new Route('detail/thumbnail/$1')]],
	#'detail/(:any)'           => [['get', new Route('detail/index/$1')]],
	'image/list/(:num)'       => 'image/list/$1',
	'image/(:any)'            => [['get', new Route('image/index/$1')]],
);
