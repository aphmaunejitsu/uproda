<!DOCTYPE html>
<html lang="ja">
<head>
  <?php echo $partials['head'];?>
</head>
<body>
	<header class='warecoli'>
	<?php echo $partials['header'] ?>
	</header>
    <?php if (isset($_SERVER['ADCASH_BANNER1']) && $_SERVER['ADCASH_BANNER1']) : ?>
	<div class="adcash">
    <script data-cfasync="false" type="text/javascript" src="<?php $_SERVER['ADCASH_BANNER1'] ?>"></script>
	</div>
    <?php endif; ?>
	<div id="root">
		<div id="content">
		<?php echo $partials['content'] ?>
		</div>
	</div>
    <?php if (isset($_SERVER['ADCASH_BANNER1']) && $_SERVER['ADCASH_BANNER1']) : ?>
	<div class="adcash">
    <script data-cfasync="false" type="text/javascript" src="<?php $_SERVER['ADCASH_BANNER1'] ?>"></script>
	</div>
    <?php endif; ?>
	<footer id="footer">
	<?php echo $partials['footer'] ?>
	<?php echo $partials['form'] ?>
	</footer>
</body>
</html>
<!--
　　　　　／/
　　　　／　.人 　　　　　あ、ぽこたんインできないお！
　　　 / 　（＿_） パカ
　　　/ ∩(＿___）　　 　　　　このスレッドは１０００を超えたお。。。
　　 /　.|（ ・∀・）＿ 　 　　　 もう書けないので、新しいスレッドにインするお！
　　/／ |　　　ヽ／
　　"‾‾‾"∪
-->
