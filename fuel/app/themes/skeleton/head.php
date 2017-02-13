  <meta charset="utf-8">
  <title><?php echo $title ?></title>
  <meta name="description" content="<?php echo $description ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <?php echo Theme::instance()->asset->js(['sticky-header.js', 'jquery.magnific-popup.min.js']); ?>
  <?php echo Theme::instance()->asset->css(['magnific-popup.css', 'normalize.css', 'skeleton.css', 'custom.css']); ?>
  <?php echo Theme::instance()->asset->render('jquery-list-loading'); ?>
  <script>
  $(function(){
	  $('.uproda-header').stickMe({stickyAlready: true, topOffset:100, shadow:true});
	  $('.open-popup-form').magnificPopup({
		  type:'inline',
		  midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
	  });
  });
  </script>
  <link rel="icon" href="favicon.ico">
