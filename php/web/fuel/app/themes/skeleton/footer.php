  <div class="container">
		<div class="row">
			<div class="u-pull-right">
				<a class='button' href="https://github.com/aphmaunejitsu/uproda">aphmau</a>
				<a class='button' href="/settings">Settings</a>
			</div>
		</div>
  </div>
	<div style="display:none">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <?php echo Theme::instance()->asset->js(['sticky-header.js', 'jquery.magnific-popup.min.js', 'upload.js']); ?>
  <?php echo Theme::instance()->asset->render('jquery-list-loading'); ?>
  <?php echo Theme::instance()->asset->render('clipboard'); ?>
  <?php echo Theme::instance()->asset->render('settings'); ?>
	</div>
