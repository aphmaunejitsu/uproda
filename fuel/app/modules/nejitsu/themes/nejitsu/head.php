    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php echo \Theme::instance()->asset->css('bootstrap.min.css'); ?>
		<?php echo \Theme::instance()->asset->css('styles.css'); ?>
		<?php echo \Theme::instance()->asset->css('custom.css'); ?>
		<?php echo \Theme::instance()->asset->render('toggle-switch-css'); ?>
