<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=shift_jis"><!-- 文字コードおかしくね？ -->
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="NOINDEX,NOFOLLOW">
<title><?php echo $title ?></title>
<meta http-equiv="content-script-type" content="text/javascript">
<script type="text/javascript">
<!--
function getCookie(obj,cookiename){
	var i,str; c = new Array(); p = new Array("",""); str = document.cookie;c = str.split(";");
	for (i = 0; i < c.length; i++) { if (c[i].indexOf(cookiename+"=") >= 0) { p = (c[i].substr(c[i].indexOf("=")+1)).split("<>"); break; }}
	if(cookiename == "SN_UPLOAD"){ obj.pass.value =  unescape(p[0]); }
	else if(cookiename == "SN_DEL"){ obj.delpass.value =  unescape(p[0]);}
	return true;
}
function delnoin(no){
	document.Del.delno.value = no;
	document.Del.del.focus();
}
//-->
</script>
<meta http-equiv="content-style-type" content="text/css">
<?php echo Theme::instance()->asset->css('upload.css'); ?>
</head>
<body onload="getCookie(document.Form,'SN_UPLOAD');getCookie(document.Del,'SN_DEL');">
<div class="main">
<h1>Sn Uploader</h1>
<p class="description">Now testing...</p>
<form method="post" enctype="multipart/form-data" action="./upload.cgi" name="Form"><p class="post">FILE Max 1024KB (*30Files)<br><input type="file" size="40" name="upfile">
DELKey: <input type="password" size="10" name="pass" maxlength="8"><br>
COMMENT<br>
<input type="text" size="45" name="comment">
<input type="hidden" name="jcode" value="漢字">
<input type="submit" value="Upload"><input type="reset" value="Cancel">
</p></form>
<hr>
<p class="pages"><a href="./all.html?1474103089">[ALL]</a> [1] </p>
<hr>
<table summary="upinfo">
<thead>
<tr>
<th></th><th>NAME</th><th>COMMENT</th><th>SIZE</th><th>DATE</th><th>MIME</th><th>ORIG</th>
</tr>
</thead>
<tbody>
<tr><td><script type="text/javascript"><!--
document.write("<a href=\"javascript:delnoin(3)\">D<\/a>");
// --></script></td><td><a href="./src/up0003.png">up0003.png</a></td><td>Core i7</td><td>174KB</td><td>11/01/13(Thu),11:26:25</td><td>image/png</td><td>core_i7.png</td></tr>
<tr><td><script type="text/javascript"><!--
document.write("<a href=\"javascript:delnoin(2)\">D<\/a>");
// --></script></td><td><a href="./src/up0002.png">up0002.png</a></td><td>Core i5</td><td>174KB</td><td>11/01/13(Thu),11:26:14</td><td>image/png</td><td>core_i5.png</td></tr>
<tr><td><script type="text/javascript"><!--
document.write("<a href=\"javascript:delnoin(1)\">D<\/a>");
// --></script></td><td><a href="./src/up0001.png">up0001.png</a></td><td>Core i3</td><td>174KB</td><td>11/01/13(Thu),11:26:01</td><td>image/png</td><td>core_i3.png</td></tr>
</tbody>
</table>
<hr>
<p class="info">Used 523KB<br>
txt,lzh,zip,rar,gca,mpg,mp3,avi,swf,bmp,jpg,gif,png</p>
<form method="post" action="./upload.cgi" name="Del"><p class="delete">
<input type="hidden" name="mode" value="delete">No.<input type="text" size="4" name="delno"> key<input type="password" size="4" name="delpass"> <input type="submit" value="del" name="del">
</p></form>
<p class="credit"><!-- 2005/10/10e CGI.pm --><a href="http://sugachan.dip.jp/download/">Sn Uploader</a> + <a href="http://tnetsixenon.xrea.jp/">non existent</a></p>
</div>
</body>
</html>
