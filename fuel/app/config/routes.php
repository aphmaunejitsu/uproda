<?php
return array(
	'_root_'  => 'uproda/index',  // The default route
	'_404_'   => 'uproda/404',    // The main 404 route

	'image/:image' => [['get', new Route('image/index')]],
);
