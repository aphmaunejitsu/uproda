<?php
return array(
	'_root_'  => 'uproda/index',  // The default route
	'_404_'   => 'error/404',     // The main 404 route
	'_500_'   => 'error/500',     // the main 500 route

	'image/:image' => [['get', new Route('image/index')]],
);
