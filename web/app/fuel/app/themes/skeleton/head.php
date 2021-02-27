  <meta charset="utf-8">
  <title><?php echo $title ?></title>
  <meta name="description" content="<?php echo $description ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-cache">
  <meta http-equiv="Expires" content="Thu, 01 Dec 1994 16:00:00 GMT">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css" integrity="sha384-QokYePQSOwpBDuhlHOsX0ymF6R/vLk/UQVz3WHa6wygxI5oGTmDTv8wahFOSspdm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/fontawesome.css" integrity="sha384-vd1e11sR28tEK9YANUtpIOdjGW14pS87bUBuOIoBILVWLFnS+MCX9T6MMf0VdPGq" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <?php echo Theme::instance()->asset->js(['sticky-header.js', 'jquery.magnific-popup.min.js', 'upload.js']); ?>
  <?php echo Theme::instance()->asset->render('jquery-list-loading'); ?>
  <?php echo Theme::instance()->asset->render('clipboard'); ?>
  <?php echo Theme::instance()->asset->render('settings'); ?>
  <?php echo Theme::instance()->asset->css(['style.css']); ?>
  <link rel="icon" href="favicon.ico">

<?php if (isset($_SERVER['GOOGLE_TAG_MANAGER']) && $_SERVER['GOOGLE_TAG_MANAGER']) : ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $_SERVER['GOOGLE_TAG_MANAGER']; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $_SERVER['GOOGLE_TAG_MANAGER']; ?>');
</script>
<?php endif; ?>
