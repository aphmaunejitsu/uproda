<!DOCTYPE html>
<html lang="ja">
<head>
  <?php echo $partials['head'];?>
</head>
<body>
	<header class='warecoli'>
	<?php echo $partials['header'] ?>
	</header>
    <div id='top-ad' class="dmm">
        <?php if (isset($_SERVER['DMM_BANNER1']) && ($_SERVER['DMM_BANNER1'])):  ?>
        <?php echo $_SERVER['DMM_BANNER1']; ?>
        <?php endif; ?>
        <?php if (isset($_SERVER['DMM_BANNER2']) && ($_SERVER['DMM_BANNER2'])):  ?>
        <?php echo $_SERVER['DMM_BANNER2']; ?>
        <?php endif; ?>
    </div>
	<div id="root">
		<div id="content">
		<?php echo $partials['content'] ?>
		</div>
	</div>
    <div id="footer-ad" class='dmm'>
        <?php if (isset($_SERVER['DMM_BANNER3']) && ($_SERVER['DMM_BANNER3'])):  ?>
        <?php echo $_SERVER['DMM_BANNER3']; ?>
        <?php endif; ?>
        <?php if (isset($_SERVER['DMM_BANNER4']) && ($_SERVER['DMM_BANNER4'])):  ?>
        <?php echo $_SERVER['DMM_BANNER4']; ?>
        <?php endif; ?>
    </div>
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
