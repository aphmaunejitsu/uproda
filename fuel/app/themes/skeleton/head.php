  <meta charset="utf-8">
  <title><?php echo $title ?></title>
  <meta name="description" content="<?php echo $description ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <?php echo Theme::instance()->asset->css(['normalize.css', 'skeleton.css', 'custom.css']); ?>
	<?php echo Theme::instance()->asset->render('jquery-list-loading'); ?>
  <link rel="icon" href="favicon.ico">
