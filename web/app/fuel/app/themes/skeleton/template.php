<!DOCTYPE html>
<html lang="ja">
<head>
  <?php echo $partials['head'];?>
<style>
.adcash {
	display: flex;
	justify-content: center;
	width: 100%;
	padding-top: 0.5rem;
	padding-bottom: 0.5rem;
}
</style>
</head>
<body>
	<header class='warecoli'>
	<?php echo $partials['header'] ?>
	</header>
	<?php if (isset($_SERVER['ADCASH_BANNER1']) && $_SERVER['ADCASH_BANNER1']) : 
	echo '<div class="adcash">';
	echo '<script data-cfasync="false" type="text/javascript" src="' . $_SERVER['ADCASH_BANNER1'] . '"></script>'; 
	echo '</div>';
	endif; ?>
	<div id="root">
		<div id="content">
		<?php echo $partials['content'] ?>
		</div>
	</div>
	<?php if (isset($_SERVER['ADCASH_BANNER1']) && $_SERVER['ADCASH_BANNER1']) : 
	echo '<div class="adcash">';
	echo '<script data-cfasync="false" type="text/javascript" src="' . $_SERVER['ADCASH_BANNER1'] . '"></script>'; 
	echo '</div>';
	endif; ?>
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
