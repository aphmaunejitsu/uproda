#!/usr/bin/perl
######################################################################
#  IMGSIZE  Ver.1.71   (画像のスクリーンサイズ解析＆変更）          ##
#                 -- Last Updated 2003/03/21 --                     ##
######################################################################
#                                                                     
# imgsize.pl - show screen size of image
# Copyright (C) 1998 TANAKA Katsunori <tkatsu@mx2.nisiq.net>
# Copyright (C) 1998-2002 Kenta Ogo  <ogo@ta2.so-net.ne.jp>
# Copyright (C) 2001 蓮井達也（TED） <ted@uranus.interq.or.jp>
#
# (http://www.big.or.jp/~talk/t-club/soft/)
#
# <改変履歴>-03/21/03
#  2003/03/21 JPEGヘッダの探索範囲にリミッタをつけた
#  2002/09/02 Perl4でいきなり500エラーで悩む人が増えたので、my変数をやめた
#  2002/08/21 BMPファイル対応。
#  2002/08/21 変数名を重なりにくくした。my変数を増やした（要Perl5）
#  2001/03/01 TEDさんの協力によりPNGに対応。TEDさんに感謝です
#
# [イメージサイズとは]
# GIF,PNG,JPEG,BMP画像の縦横のピクセル値がわかるPerlライブラリです
# 画像ファイルのヘッダ数バイトを読み、それを解析して
# GIF/JPEG/PNG/BMP/OHTER 判別、及び、縦横ピクセル値情報を出力します
#
# [使用方法]
# perlスクリプト中でimgsize.plをrequireし、サブルーチン
# &imgsize("target_file");を実行する
# 解析に成功し、サイズ値が得られたら1、
# サイズ値が得られなければ0が返ります
#
# [使用例]
# &imgsize("test.gif");
# if($IMGSIZE{'result'}==1){
#     print "<IMG SRC=test.gif height=$IMGSIZE{'height'} width=$IMGSIZE{'width'}>\n;
# }
#	結果は以下の表記で呼び出す事ができます
#	$IMGSIZE{'result'}	 サイズ値取得成功=1、失敗=0
#	$IMGSIZE{'type'}	 画像のタイプ[GIF|PNG|JPEG|BMP|OTHER]
#	$IMGSIZE{'width'} 	 画像の横幅[xx|no_data]
#	$IMGSIZE{'height'} 	 画像の縦幅[xx|no_data]
#	$IMGSIZE{'file_name'} 	 元ファイル名
#	$IMGSIZE{'hw_racio'} 	 縦横比(1:1=100)
#	$IMGSIZE{'zoom'} 	 ズーム比(1:1=100)
#	$IMGSIZE{'max_length'} 	 長辺の長さ
#	$IMGSIZE{'square'} 	 面積(dot)
#	$IMGSIZE{'error_message'}エラーメッセージ（通常は空）
#
#
# [利用規定]
# [1]当コピーライト部、タイトル部以外のプログラム部は、個人で使用する
#    限りにおいて、自由に改造してもらって構いません  また無料で使用で
#    きます  
# [2]ただし著作権は放棄してませんので、改造、非改造を問わず無断での
#    再配布は固く禁止します  （相談すれば可になる場合あり）
# [3]営利目的に使用することを禁止します  
# [4]万一このCGIにより損害や不利益受けたとしても、当方は一切その責任を負う
#    義務を持ちません  あらかじめご了承ください  
# [5]理由の如何を問わず、このタイトル部、及び、コピーライト部は削除、
#    加工することを禁止します  改造した場合は欄外に追記してください  

######################################################################

$imgsize_version='20020902';	#imgsizeのバージョン
$imgsize_lib_flag='1';		#このフラグでサブルーチンの存在確認


sub imgsize{

# imgsize.pl - show screen size of image
# Copyright (C) 1998 TANAKA Katsunori <tkatsu@mx2.nisiq.net>
# Copyright (C) 2001 TED <ted@uranus.interq.or.jp> (PNG support)
#
# I wrote the subroutine jpeg_size, referring to rdjpgcom.c
# contained in the Independent JPEG Group's software.
#
# $Id: imgsize.pl,v 1.5 1998/08/20 13:17:24 tkatsu Exp $

# constants.

    local($IMGSIZE_READ_HEAD) = 10;
    local($GIF_SKIP_HEAD)  = 6;
    local($GIF_SIZE_INFO)  = 4;
    local($JPEG_SKIP_HEAD) = 2;
    local($JPEG_SKIP_LENGTH_AND_BPS) = 3;
    local($JPEG_SIZE_INFO) = 4;
    local($JPEG_LENGTH)    = 2;
    local($PNG_SKIP_HEAD)  = 12;
    local($PNG_SIZE_INFO)  = 12;
    local($BMP_SKIP_HEAD)  = 18;
    local($BMP_SIZE_INFO)  =  8;

# main routine.

	# 変数名を重なりにくいものに変更(2002.09)
	local($imsz_smooze_mode);
	local($imsz_file)	="$_[0]";       #File name
	local($imsz_action)	="$_[1]";	#action
	local($imsz_parameter1)	="$_[2]";	#parameter1
	local($imsz_parameter2)	="$_[3]";	#parameter2
	local($imsz_parameter3)	="$_[4]";	#parameter3

	$imsz_parameter1		=~ s/\%//g;	#remove character "%"
	$imsz_parameter2		=~ s/\%//g;	#remove character "%"
	$imsz_parameter2		=~ s/dot//g;	#remove character "dot"
	$imsz_parameter2		=~ s/pixel//g;	#remove character "pixel"


	local($imsz_width,$imsz_height);
	undef $e_mes;
	undef $p_type;
	undef %IMGSIZE;

    # return status: 1 for success, 0 for failure.
    local($imsz_status) = 1;
    local($imsz_current_status) = 1;
    local($imsz_type);

    $ddd="imgsize skip success!!";
 
  if($imsz_file ne 'dummy'){
    $ddd="imgsize done..";
    unless (open(FILE, $imsz_file)) {
	$e_mes.= "$0: can't open $imsz_file<BR>\n";
	$imsz_current_status = 0;
    }
	binmode(FILE);	# 改造箇所

    &ident_type == 0 && ($imsz_current_status = 0);

    if ($imsz_current_status == 1 && $imsz_type == 1) {
	&gif_size == 0 && ($imsz_current_status = 0);
    } elsif ($imsz_current_status == 1 && $imsz_type == 2) {
	&jpeg_size == 0 && ($imsz_current_status = 0);
    } elsif ($imsz_current_status == 1 && $imsz_type == 3) {
	&png_size == 0 && ($imsz_current_status = 0);
    } elsif ($imsz_current_status == 1 && $imsz_type == 4) {
	&bmp_size == 0 && ($imsz_current_status = 0);
    }

    unless (close FILE) {
	$e_mes.= "$0: can't close $imsz_file<BR>\n";
	$imsz_current_status = 0;
    }

    unless ($imsz_current_status == 0) {
	&output_info;
    }
  
    &check_results ==0 && ($imsz_current_status =0);
    unless ($imsz_current_status == 0){
	&other_parameters;
    }
  }else{
    &input_prefetched_data;
  }

    unless ($imsz_current_status == 0){
	&imgsize_change_size_parameters;
    }

    undef $imsz_parameter1;
    undef $imsz_parameter2;
    undef $imsz_action;
    undef $imsz_height;
    undef $imsz_width;

    $imsz_current_status == 0 && ($imsz_status = 0);

    $IMGSIZE{'result'}=$imsz_status;	# サイズ値取得成功=1、失敗=0
    return $imsz_status;
}

# identify the image format.
sub ident_type {

    # Modified 99.09.01 by ogo
    local($in);
    local($r_HEAD);

    $r_HEAD=read(FILE, $in, $IMGSIZE_READ_HEAD);

#    if($r_HEAD != $IMGSIZE_READ_HEAD) {
#
#		$e_mes.= "$0: can't read $r_HEAD bytes from $imsz_file. <BR>\n";
#		return 0;
#    }

    if ($in=~ /^GIF/i) {
	$imsz_type = 1;
    } elsif ( $in=~ /\xff\xd8/ ) {
	$imsz_type = 2;
    } elsif ( $in=~ /^\x89PNG\x0D\x0A\x1A\x0A/ ) {	# 改造箇所
	# ↑文字列に\r\nが入っているのでUNIX系以外ではbinmodeにする必要あり。
	#   /^\x89PNG/で十分かも知れませんが……(TED)
	$imsz_type = 3;
    } elsif ( $in=~ /BM/i ) {
	$imsz_type = 4;
    } else {
	$imsz_type = 0;
    }
    return 1;
}

# get the screen size.
sub gif_size {
    local($in, $w1, $w2, $h1, $h2);
    seek(FILE, $GIF_SKIP_HEAD, 0);
    unless (read(FILE, $in, $GIF_SIZE_INFO) == $GIF_SIZE_INFO) {
	$e_mes.= "$0: can't read $GIF_SIZE_INFO bytes from $imsz_file<BR>\n";
	return 0;
    }
    ($w1, $w2, $h1, $h2) = unpack("CCCC", $in);
    $imsz_width = $w1 + $w2 * 256;
    $imsz_height = $h1 + $h2 * 256;
    return 1;
}

# get the screen size.
sub jpeg_size {
    local($in, $w1, $w2, $h1, $h2, $l1, $l2, $length,$max_loop_limit);
    seek(FILE, $JPEG_SKIP_HEAD, 0);

    # 2003.03 安全装置を追加
    for($max_loop_limit=0;$max_loop_limit < 100000000 ;$max_loop_limit++){
	$in = getc(FILE); # 1文字読み込み
	if (!$in) {
	    return 0; # getcで読み込みができなくなったら、ループ終了
	} elsif ($in eq "\xff") {
	    $in = getc(FILE);
	    if ($in eq "\xc0" || $in eq "\xc1" || $in eq "\xc2" ||
		$in eq "\xc3" || $in eq "\xc5" || $in eq "\xc6" ||
		$in eq "\xc7" || $in eq "\xc9" || $in eq "\xca" ||
		$in eq "\xcb" || $in eq "\xcd" || $in eq "\xce" ||
		$in eq "\xcf") {
		seek(FILE, $JPEG_SKIP_LENGTH_AND_BPS, 1);
		unless (read(FILE, $in, $JPEG_SIZE_INFO) == $JPEG_SIZE_INFO) {

		    $e_mes.= "$0: can't read $JPEG_SIZE_INFO bytes from $imsz_file<BR>\n";

		    return 0;
		}
		($h1, $h2, $w1, $w2) = unpack("CCCC", $in);
		$imsz_height = $h1 * 256 + $h2;
		$imsz_width = $w1 * 256 + $w2;
		return 1;
	    } elsif ($in eq "\xd9" || $in eq "\xda") {
		return 0;
	    } else {
		unless (read(FILE, $in, $JPEG_LENGTH) == $JPEG_LENGTH) {
		    $e_mes.= "$0: can't read $JPEG_LENGTH bytes from $imsz_file<BR>\n";
		    return 0;
		}
		($l1, $l2) = unpack("CC", $in);
		$length = $l1 * 256 + $l2;
		seek(FILE, $length - 2, 1);
	    }
	}
    }
    return 0;
}

# 改造箇所 / PNG画像のサイズを読み取る追加サブルーチン
# 縦または横サイズが65536を越える画像にも対応
sub png_size {
    local($in, $dummy);
    seek(FILE, $PNG_SKIP_HEAD, 0);
    unless (read(FILE, $in, $PNG_SIZE_INFO) == $PNG_SIZE_INFO) {
	$e_mes.= "$0: can't read $PNG_SIZE_INFO bytes from $imsz_file<BR>\n";
	return 0;
    }
	unless ($in =~ /^IHDR/) {
	return 0;
	}
	($dummy, $imsz_width, $imsz_height) = unpack("a4N2", $in);
	return 1;
}

sub bmp_size {
    local($in, $dummy);
    seek(FILE, $BMP_SKIP_HEAD, 0);
    unless (read(FILE, $in, $BMP_SIZE_INFO) == $BMP_SIZE_INFO) {
	$e_mes.= "$0: can't read $BMP_SIZE_INFO bytes from $imsz_file<BR>\n";
	return 0;
    }
    ($imsz_width, $imsz_height) = unpack("V2", $in);
    return 1;
}

# output result
sub output_info {

	undef %IMGSIZE;
	undef $imgsize_result;

    if ($imsz_type == 1) {
	$imgsize_result="$imsz_file: $imsz_width"."w "."$imsz_height"."h "."GIF\n";
	$IMGSIZE{'type'} 	='GIF';
	$IMGSIZE{'width'} 	="$imsz_width";
	$IMGSIZE{'height'} 	="$imsz_height";
	$IMGSIZE{'file_name'} 	="$imsz_file";
	$IMGSIZE{'zoom'} 	="100";
    } elsif ($imsz_type == 2) {
	$imgsize_result="$imsz_file: $imsz_width"."w "."$imsz_height"."h "."JPEG\n";
	$IMGSIZE{'type'} 	='JPEG';
	$IMGSIZE{'width'} 	="$imsz_width";
	$IMGSIZE{'height'} 	="$imsz_height";
	$IMGSIZE{'file_name'} 	="$imsz_file";
	$IMGSIZE{'zoom'} 	="100";
    } elsif ($imsz_type == 3) {
	$imgsize_result="$imsz_file: $imsz_width"."w "."$imsz_height"."h "."PNG\n";
	$IMGSIZE{'type'} 	='PNG';
	$IMGSIZE{'width'} 	="$imsz_width";
	$IMGSIZE{'height'} 	="$imsz_height";
	$IMGSIZE{'file_name'} 	="$imsz_file";
	$IMGSIZE{'zoom'} 	="100";
    } elsif ($imsz_type == 4) {
	$imgsize_result="$imsz_file: $imsz_width"."w "."$imsz_height"."h "."BMP\n";
	$IMGSIZE{'type'} 	='BMP';
	$IMGSIZE{'width'} 	="$imsz_width";
	$IMGSIZE{'height'} 	="$imsz_height";
	$IMGSIZE{'file_name'} 	="$imsz_file";
	$IMGSIZE{'zoom'} 	="100";
    } else {
	$imgsize_result="$imsz_file: OTHER\n";
	$IMGSIZE{'type'} 	='OTHER';
	$IMGSIZE{'file_name'} 	="$imsz_file";
    }
    $IMGSIZE{'error_message'} 	="$e_mes";
}

#===================================#
#  結果のチェック(0除算発生を防ぐ)
#===================================#

sub check_results{
    local($tmp_status)=1;	#1=ok,0=cannot_use_size_parameter
    unless($IMGSIZE{'width'} > 0 ){
	$tmp_status=0;
	$IMGSIZE{'width'}=1;
    }
    unless($IMGSIZE{'height'} > 0 ){
	$tmp_status=0;
	$IMGSIZE{'height'}=1;
    }
    return $tmp_status;
}

#==================#
#  他の変数の計算
#==================#

sub other_parameters{
	if(($IMGSIZE{'height'} > 0 )&&($IMGSIZE{'width'} > 0 )){
		$IMGSIZE{'hw_racio'} 	=int(100*$IMGSIZE{'height'}/$IMGSIZE{'width'});
	}
	$IMGSIZE{'hw_racio'}=100 unless($IMGSIZE{'hw_racio'} > 0 );
	# 0の時は100にする  (0除算防止)

	if($IMGSIZE{'hw_racio'} >= 100){
		$IMGSIZE{'max_length'}=$IMGSIZE{'height'};
	}else{
		$IMGSIZE{'max_length'}=$IMGSIZE{'width'};
	}

	$IMGSIZE{'square'}=$IMGSIZE{'width'}*$IMGSIZE{'height'};
}

#================================================================#
# イメージサイズ加工ライブラリ
# Copyright (C) 1998,2001 Kenta Ogo <ogo@ta2.so-net.ne.jp>
#
# [イメージサイズ加工ライブラリとは]
# sub imgsizeで得たGIF、PNG、JPEG画像の縦横サイズ情報を元に
# いく種類かの加工パターンを施すライブラリです  
# Webでよく使用する処理をまとめました  
#
#
# [使用方法]
# 本文中でimgsize.plをrequireし、サブルーチン
# &imgsize("引数1","引数2","引数3","引数4","引数5")を実行する;
#
# 引数1はファイル名、引数2は処理種別（アクション）、引数3,4はパラメータです
# 引数5は指定値の最終補正(smoozer処理)をするかどうかの指定に用います。0は
#「未使用」、1は「画質優先」,２は「画質最優先」です。引数5を用いた場合は、
# 表示が比較的きれいになりますが、縦横の指定値は近似値処理になり、指定値と
# 異なった値にセットされますのでご注意ください。
#							
# 引数２		引数３		引数４		引数５		意味	
# 処理種別						スムーザ		
# x_per			倍率％		なし		0,1,2		指定倍率へ変更
# iconize		ベースサイズ	なし		0,1,2		アイコン化
# static_width		横固定サイズ	最大縦		0,1,2		横サイズ固定化
# static_height		縦固定サイズ	最大横		0,1,2		縦サイズ固定化
# limit_by_max_size	縦幅制限値	横幅制限値	0,1,2		極端に大きな画像のみ縮小
#
#	結果は以下の表記で呼び出してください  
#
#	$IMGSIZE{'type'}	 画像のタイプ[GIF|PNG|JPEG|BMP|OTHER]
#	$IMGSIZE{'width'} 	 画像の横幅[オリジナル]
#	$IMGSIZE{'height'} 	 画像の縦幅[オリジナル]
#	$IMGSIZE{'out_width'} 	 画像の横幅[加工後]
#	$IMGSIZE{'out_height'} 	 画像の縦幅[加工後]
#	$IMGSIZE{'out_hw_racio'} 縦横比(1:1=100)
#	$IMGSIZE{'zoom'}	 加工前と加工後の比(1:1=100)
#
#     [例]test.gifのサイズを50%に変更して表示
#     &imgsize(test.gif,x_per,50%,,0)を実行する;
#
#     <IMG SRC=test.gif width=$IMGSIZE{'out_width'} height=$IMGSIZE{'out_height'}>
#
#    $imsz_action	="$_[1]";	#
#    $imsz_parameter1	="$_[2]";
#    $imsz_parameter2	="$_[3]";
#    $imsz_parameter3	="$_[4]";
#
#=====================#
#  メインプログラム
#=====================#

sub imgsize_change_size_parameters{


        # 通常の利用形態の場合
	  # サイズ取得にエラーのある時は0除算による
          # ストップを防ぐため、以下の処理をしない
	if($imsz_current_status == 0){
		$IMGSIZE{'out_result'}=0;
		return 0;
	}

	# ４つ目の引数はスムーザ
	if($imsz_parameter3 ne ""){
		$imsz_smooze_mode="$imsz_parameter3";
	}else{
	# 指定
		if($CIMGSIZE{'smooze_mode'}==2){
			$imsz_smooze_mode=2;
		}elsif($CIMGSIZE{'smooze_mode'}==1){
			$imsz_smooze_mode=1;
		}elsif($CIMGSIZE{'smooze_mode'}==0){
			$imsz_smooze_mode=0;
		}else{
			$imsz_smooze_mode=0;
		}
	}

	&check_icon_files;	# アイコンファイルかどうか判断する
				# アイコンは拡大縮小しても見にくいだけ
				# なので、拡大縮小しない  

	# 以下、アクション名に応じた加工を行う  

	if($imsz_action eq 'x_per'){
		&change_x_per;		# 倍率変更
	}elsif($imsz_action eq 'iconize'){
		&iconize;		# アイコン化
	}elsif(($imsz_action eq 'auto_resize')&&($p_type ne 'icon')){
		&auto;			# オート
	}elsif(($imsz_action eq 'static_width')&&($p_type ne 'icon')){
		&static_width;		# 横幅を指定サイズに揃える
	}elsif(($imsz_action eq 'static_height')&&($p_type ne 'icon')){
		&static_height;		# 縦幅を指定サイズに揃える
	}elsif($imsz_action eq 'limit_by_max_size'){
		&limit_by_max_size;	# 極端に大きな画像のみ縮小
	}else{
		$IMGSIZE{'out_width'} =$IMGSIZE{'width'};
		$IMGSIZE{'out_height'}=$IMGSIZE{'height'};
	}
        # 結果を返す（成功=1;失敗=0）
	if(&check_out_results==0){
		$IMGSIZE{'out_result'}=0;
	}else{
		$IMGSIZE{'out_result'}=1;
	}
	&other_out_parameters;	

	return $IMGSIZE{'out_result'};
}


#=======================#
# 倍率(%)でサイズ変更
#=======================#

sub change_x_per{
# &imgsize("引数1","引数2","引数3");を実行する
# 引数１ 画像ファイル名
# 引数２ x_per
# 引数３ 拡縮倍率(%)

		if($imsz_smooze_mode > 0){
			# IE 用近似計算による画質補正
			$imsz_parameter1=&img_smoozer_for_ie($imsz_parameter1,$imsz_smooze_mode);
		}
		$IMGSIZE{'out_width'} 	=int($imsz_parameter1*$IMGSIZE{'width'} /100);
		$IMGSIZE{'out_height'} 	=int($imsz_parameter1*$IMGSIZE{'height'}/100);
		$IMGSIZE{'zoom'} 	="$imsz_parameter1";
}

#============#
# アイコン化
#============#

sub iconize{
# &imgsize("引数1","引数2","引数3");を実行する
# 引数１ 画像ファイル名
# 引数２ iconize
# 引数３ アイコンサイズ(省略可)

	# アイコンの大きさを変えたい場合は以下の数値を変更
	local($base_size)='6000';#(default)
	local($tmp_x_per)  	="100";		#(default)

	# 横幅に重みをつけて計算  印象をほぼ同サイズにする  	
	if($imsz_parameter1=~ /\d+/){
		$base_size  ="$imsz_parameter1";	
	}		

	local($now_size) =$IMGSIZE{'height'}*$IMGSIZE{'width'}*$IMGSIZE{'width'}*$IMGSIZE{'width'};
	$now_size=1 if($now_size <1); #0除算防止
	local($area_racio)=sqrt(sqrt(10000*$base_size/$now_size));
	local($tmp_x_per)  	=int(100*$area_racio);		
	
	if($imsz_smooze_mode > 0){
		# IE 用近似計算による画質補正
		$tmp_x_per=&img_smoozer_for_ie(int($tmp_x_per),$imsz_smooze_mode);

		$IMGSIZE{'out_height'} 	=int($tmp_x_per*$IMGSIZE{'height'}/100);
		$IMGSIZE{'out_width'} 	=int($tmp_x_per*$IMGSIZE{'width'}/100);
		$IMGSIZE{'zoom'}	=int($tmp_x_per);
	}else{
		$IMGSIZE{'out_height'} 	=int($tmp_x_per*$IMGSIZE{'height'}/100);
		$IMGSIZE{'out_width'} 	=int($tmp_x_per*$IMGSIZE{'width'}/100);
		$IMGSIZE{'zoom'}	=int($tmp_x_per);
	}	
}

#================#
# オートリサイズ
#================#

sub auto{
# アイコンとまったく同じ  違いはデフォルトの面積数値が大きい事  
# &imgsize("引数1","引数2","引数3");を実行する
# 引数１ 画像ファイル名
# 引数２ iconize
# 引数３ アイコンサイズ(省略可)

	# アイコンの大きさを変えたい場合は以下の数値を変更
	local($base_size)  ="600000";#(default)
	local($tmp_x_per)  	="100";		#(default)

	# 横幅に重みをつけて計算  印象をほぼ同サイズにする  	
	if($imsz_parameter1=~ /\d+/){
		$base_size ="$imsz_parameter1";	
	}		


	local($now_size) =$IMGSIZE{'height'}*$IMGSIZE{'width'}*$IMGSIZE{'width'}*$IMGSIZE{'width'};

	$now_size=1 if($now_size <1); #0除算防止

	local($area_racio)=sqrt(sqrt(10000*$base_size/$now_size));
	local($tmp_x_per)  	=int(100*$area_racio);		

	if($imsz_smooze_mode > 0){
		# IE 用近似計算による画質補正
		$tmp_x_per=&img_smoozer_for_ie(int($tmp_x_per),$imsz_smooze_mode);

		$IMGSIZE{'out_height'} 	=int($tmp_x_per*$IMGSIZE{'height'}/100);
		$IMGSIZE{'out_width'} 	=int($tmp_x_per*$IMGSIZE{'width'}/100);
		$IMGSIZE{'zoom'}	=int($tmp_x_per);
	}else{
		$IMGSIZE{'out_height'} 	=int($tmp_x_per*$IMGSIZE{'height'}/100);
		$IMGSIZE{'out_width'} 	=int($tmp_x_per*$IMGSIZE{'width'}/100);
		$IMGSIZE{'zoom'}	=int($tmp_x_per);
	}	

}

#==================#
# 横幅を固定化する  
#==================#

sub static_width{
# 横幅を固定化する  
# &imgsize("引数1","引数2","引数3","引数4")を実行する;
# 引数１ 画像ファイル名
# 引数２ static_width
# 引数３ 横幅
# 引数４ 最大縦限界値（省略可）
#
# 引数３で指定された横幅に固定化  しかし、縦長写真では
# 極端に縦が長くなり画面からはみ出る可能性があるので、引数４で最大限界を
# 指定できる  数値が指定されてない場合は以下のデフォルト値が使用される  

# width
	local($static_width)	="256";		#(default)
	local($tmp_x_per)  	="100";		#(default)

	if($imsz_parameter1=~ /\d+/){
		$static_width  ="$imsz_parameter1";	
	}		

	# ultimate height
	local($max_height)    =int(2*$static_width);#(default)

	if($imsz_parameter2=~ /\d+/){
		$max_height    ="$imsz_parameter2";
	}		

	if($imsz_smooze_mode > 0){	
	   # IE 用近似計算による画質補正
	   $tmp_x_per	 	=int(100*$static_width/$IMGSIZE{'width'});
	   $tmp_x_per=&img_smoozer_for_ie($tmp_x_per,$imsz_smooze_mode);
	   $IMGSIZE{'out_width'} 	=int($IMGSIZE{'width'}*$tmp_x_per/100);
	   $IMGSIZE{'out_height'} 	=int($IMGSIZE{'height'}*$tmp_x_per/100);
	   $IMGSIZE{'zoom'}	=int($tmp_x_per);	
	}else{
	   $IMGSIZE{'out_width'} 	=$static_width;
	   $IMGSIZE{'out_height'} 	=int($IMGSIZE{'hw_racio'}*$static_width/100);
	   $IMGSIZE{'out_height'}=1 if($IMGSIZE{'out_height'} <1); #0除算防止
	   $IMGSIZE{'zoom'}	=int(100*$IMGSIZE{'height'}/$IMGSIZE{'out_height'});	
	}
	$IMGSIZE{'out_height'}	=$max_height if($IMGSIZE{'out_height'}>$max_height);

}


#==================#
#  縦長を固定化
#==================#

sub static_height{
# &imgsize("引数1","引数2","引数3","引数4");を実行する
# 引数１ 画像ファイル名
# 引数２ static_height
# 引数３ 縦幅
# 引数４ 最大横限界値（省略可）
# 縦幅を固定化する  引数３で指定された縦幅に固定化  しかし、横長写真では
# 横幅が極端に長くなり、画面からはみ出る可能性があるので、引数４で
# 最大横限界を指定できる（省略可）  数値が指定されてない場合は以下の
# デフォルト値が使用される  

# height
	local($static_height)  ="540";		#(default)
	local($tmp_x_per)  	="100";		#(default)

	if($imsz_parameter1=~ /\d+/){
		$static_height  ="$imsz_parameter1";	
	}		

	# ultimate width

	local($max_width)    =int(2*$static_height);#(default)

	if($imsz_parameter2=~ /\d+/){
		$max_width    ="$imsz_parameter2";
	}		

	if($imsz_smooze_mode > 0){	
	   # IE 用近似計算による画質補正
	   $tmp_x_per	 	=int(100*$static_height/$IMGSIZE{'height'});
	   $tmp_x_per=&img_smoozer_for_ie($tmp_x_per,$imsz_smooze_mode);
	   $IMGSIZE{'out_width'} 	=int($IMGSIZE{'width'}*$tmp_x_per/100);
	   $IMGSIZE{'out_height'} 	=int($IMGSIZE{'height'}*$tmp_x_per/100);
	   $IMGSIZE{'zoom'}	=int($tmp_x_per);	
	}else{
	   $IMGSIZE{'out_height'} 	=$static_height;
	   $IMGSIZE{'out_width'}   =int(100*$static_height/$IMGSIZE{'hw_racio'});
	   $IMGSIZE{'out_width'}	=$max_width if($IMGSIZE{'out_width'}>$max_width);
	   $IMGSIZE{'zoom'}	=int(100*$IMGSIZE{'width'}/$IMGSIZE{'out_width'});	
	}
	$IMGSIZE{'out_width'}	=$max_width if($IMGSIZE{'out_width'}>$max_width);
}


#==================#
#  大きさ制限
#==================#

sub limit_by_max_size{
# &imgsize("引数1","引数2","引数3","引数4");を実行する
# 引数１ 画像ファイル名
# 引数２ max_limit_size
# 引数３ 縦幅制限値
# 引数４ 横幅制限値
# 制限値を超えた場合のみ、縦横比を維持したままサイズを縮小する
# 引数３は縦最大制限値、引数４は横最大制限値  
# 数値が指定されてない場合は以下のデフォルト値が使用される  
#
#
#
# max height
	local($limit_height)  ="540";		# (default)

	if($imsz_parameter1=~ /\d+/){
		$limit_height  ="$imsz_parameter1";	
	}		

# max width
	local($limit_width)    =780;# (default)

	if($imsz_parameter2=~ /\d+/){
		$limit_width    ="$imsz_parameter2";
	}		

	$IMGSIZE{'height'}=1 if($IMGSIZE{'height'} <1); #0除算防止
	$IMGSIZE{'width'}=1  if($IMGSIZE{'width'} <1);  #0除算防止
	$tmp_height_racio=int(100*$limit_height/$IMGSIZE{'height'});
	$tmp_width_racio =int(100*$limit_width/$IMGSIZE{'width'});

	local($tmp_x_per)  	="100";		#(default)
	
	if($tmp_height_racio < $tmp_width_racio){
	#height is critical
		if($tmp_height_racio < 101){
			#resize by height
			if($imsz_smooze_mode > 0){	
	   		  # IE 用近似計算による画質補正
	   		  $tmp_x_per	 	=int(100*$limit_height/$IMGSIZE{'height'});
	   		  $tmp_x_per=&img_smoozer_for_ie($tmp_x_per,$imsz_smooze_mode);
			  $IMGSIZE{'out_width'} 	=int($IMGSIZE{'width'}*$tmp_x_per/100);
			  $IMGSIZE{'out_height'} 	=int($IMGSIZE{'height'}*$tmp_x_per/100);
			  $IMGSIZE{'zoom'}	=int($tmp_x_per);	
			}else{
			  $IMGSIZE{'out_height'} =$limit_height;
			  $IMGSIZE{'out_width'}  =int(100*$limit_height/$IMGSIZE{'hw_racio'});
			  $IMGSIZE{'out_width'}=1 if($IMGSIZE{'out_width'} <1); #0除算防止
			  $IMGSIZE{'zoom'}	=int(100*$IMGSIZE{'width'}/$IMGSIZE{'out_width'});
			}
	
		}else{
			# no change
			$IMGSIZE{'out_height'} 	=$IMGSIZE{'height'};
			$IMGSIZE{'out_width'}   =$IMGSIZE{'width'};	
			$IMGSIZE{'zoom'}	=100;	
		}
	}else{
	#width is critical
		if($tmp_width_racio < 101){
			#resize by width
			if($imsz_smooze_mode > 0){	
	   		  # IE 用近似計算による画質補正
	   		  $tmp_x_per	 	=int(100*$limit_width/$IMGSIZE{'width'});
	   		  $tmp_x_per=&img_smoozer_for_ie($tmp_x_per,$imsz_smooze_mode);
			  $IMGSIZE{'out_width'} 	=int($IMGSIZE{'width'}*$tmp_x_per/100);
			  $IMGSIZE{'out_height'} 	=int($IMGSIZE{'height'}*$tmp_x_per/100);
			  $IMGSIZE{'zoom'}	=int($tmp_x_per);
			}else{
			   $IMGSIZE{'out_width'} 	=$limit_width;
			   $IMGSIZE{'out_height'}  =int($limit_width*$IMGSIZE{'hw_racio'}/100);
			   $IMGSIZE{'out_height'}=1 if($IMGSIZE{'out_height'} <1); #0除算防止
			   $IMGSIZE{'zoom'}	=int(100*$IMGSIZE{'height'}/$IMGSIZE{'out_height'});
			}
		}else{
			# no change
			$IMGSIZE{'out_height'} 	=$IMGSIZE{'height'};
			$IMGSIZE{'out_width'}   =$IMGSIZE{'width'};
			$IMGSIZE{'zoom'}	=100;
		}
	}
}

#==================#
#  アイコン判別
#==================#

sub check_icon_files{
# アイコンファイルと写真ファイルを判別する  

	# 元画像が横長の場合
	if($IMGSIZE{'hw_racio'} >= 100){
			
		if($IMGSIZE{'height'} < 60){
			$p_type="icon";
		}else{
			$p_type="picture";
		}
	# 元画像が縦長の場合
	}else{
		if($IMGSIZE{'width'} < 60){
			$p_type="icon";
		}else{
			$p_type="picture";
		}
	}

}

#==================#
#  他の変数の計算
#==================#

sub other_out_parameters{
	if(($IMGSIZE{'out_width'} > 0 )&&($IMGSIZE{'out_height'} > 0 )){
		$IMGSIZE{'out_hw_racio'} 	=int(100*$IMGSIZE{'out_height'}/$IMGSIZE{'out_width'});
	}
	$IMGSIZE{'out_hw_racio'}=1 unless($IMGSIZE{'out_hw_racio'} > 0 );
	# 0の時は1にする  (0除算防止)
}

#=============================#
#  サイズ変更ルーチン単独利用
#=============================#

sub input_prefetched_data{
	# 既にサイズ情報がわかっており、サイズ変更ルーチンの単独利用を行いたい場合
	# 第一引数$imsz_fileに"dummy"という名前を入れて渡してください  既知のサイズ情報
	# は以下の$IMG_PARAMETERS{'XXX'}という配列で渡してください  
	if(($IMG_PARAMETERS{'height'} > 2)&&($IMG_PARAMETERS{'width'}>2)){
	
		$IMGSIZE{'type'}	=$IMG_PARAMETERS{'type'};
		$IMGSIZE{'height'}	=$IMG_PARAMETERS{'height'};
		$IMGSIZE{'width'}	=$IMG_PARAMETERS{'width'};
		$IMGSIZE{'hw_racio'}	=$IMG_PARAMETERS{'hw_racio'};
		$IMGSIZE{'zoom'}	=$IMG_PARAMETERS{'zoom'};
	}
}

#=============================#
#  リサイズ画像スムーザー
#=============================#
# 2000/05/01 NEW
# IEでサイズ変更をすると、そのサイズ値によって画質が大きく劣化する場合が
# あります。これを防ぐためのルーチンです。劣化しやすいようなサイズパラメー
# タが指定された場合、劣化しにくい値で最も近い値に自動補正します。
#
sub img_smoozer_for_ie{

	local($org_per,$change_pattern)=@_;

	$IMGSIZE{'sm_org_per'}		=$org_per;
	$IMGSIZE{'sm_change_pattern'}	=$change_pattern;

# very good	25,34,50,96-100
# good		11,14,20,21,33,70,72,77,92

	# 通常モード
	if($change_pattern==1){
		if($org_per <= 10){
			$org_per =$org_per;
		}elsif($org_per <=12 ){
			$org_per =11;
		}elsif($org_per <=17 ){
			$org_per =14;
		}elsif($org_per <=20 ){
			$org_per =20;
		}elsif($org_per <=23 ){
			$org_per =21;
		}elsif($org_per <=28 ){
			$org_per =25;		# very good
		}elsif($org_per <=31 ){
			$org_per =30;
		}elsif($org_per <=33 ){
			$org_per =33;
		}elsif($org_per <=43 ){
			$org_per =34;		# very good
		}elsif($org_per <=63 ){
			$org_per =50;		# very good
		}elsif($org_per <=68 ){
			$org_per =70;
		}elsif($org_per <=75 ){
			$org_per =72;
		}elsif(($org_per <=88 )&&($IMGSIZE{'max_length'} > 650)){
			$org_per =77;
		}elsif($org_per <=95 ){
			$org_per =92;
		}elsif($org_per <=97 ){
			$org_per =96;		# very good
		}elsif($org_per <=100 ){
			$org_per =100;		# very good
		}elsif($org_per <=112 ){
			$org_per =100;
		}
	# 画質優先モード
	}elsif($change_pattern==2){
		if($org_per <= 10){
			$org_per =$org_per;
		}elsif($org_per <=12 ){
			$org_per =11;
		}elsif($org_per <=17 ){
			$org_per =14;
		}elsif($org_per <=20 ){
			$org_per =20;
		}elsif($org_per <=23 ){
			$org_per =21;
		}elsif($org_per <=28 ){
			$org_per =25;		# very good
		}elsif($org_per <=31 ){
			$org_per =30;
		}elsif($org_per <=33 ){
			$org_per =33;
		}elsif($org_per <=39 ){
			$org_per =34;		# very good
		}elsif($org_per <=63 ){
			$org_per =50;		# very good
		}elsif(($org_per <=68 )&&($IMGSIZE{'max_length'} > 650)){
			$org_per =50;
		}elsif(($org_per <=75 )&&($IMGSIZE{'max_length'} > 650)){
			$org_per =72;
		}elsif(($org_per <=86 )&&($IMGSIZE{'max_length'} > 650)){
			$org_per =77;
		}elsif($org_per <=100 ){
			$org_per =100;
		}elsif($org_per <=124 ){
			$org_per =100;		# very good
		}
	}
	$IMGSIZE{'sm_out_per'}	=$org_per;
	return($org_per) ;	
}

#==============================#
# 結果のチェック(0除算を防ぐ)
#==============================#
#
sub check_out_results{

	local($p_error);
	$p_error=1;		#1=ok,0=cannot_use_size_parameter

	unless($IMGSIZE{'out_width'} > 0 ){
		$p_error=0;
		$IMGSIZE{'out_width'}=1;
	}
	unless($IMGSIZE{'out_height'} > 0 ){
		$p_error=0;
		$IMGSIZE{'out_height'}=1;
	}
	unless($IMGSIZE{'zoom'} > 0 ){
		$p_error=0;
		$IMGSIZE{'zoom'}=1;
	}
	return	$p_error;
}

1;
