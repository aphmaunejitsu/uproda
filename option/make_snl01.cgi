#!/usr/bin/perl
#============================================#
# 各携帯対応用の画像作成 (R7 NEW)
#============================================#
# 2014.11.23 最新のimagemagickで動くように修正（新ジオシティーズプラス対応）
# i10Lがim.cgiのサムネイル1番だったので、エラーになっている。対処必要。
# 2013.02.05 PNGの大きなファイル時に、サムネイル大ができなかったバグを修正
# 2011.06.07 処理高速化のため、待受け画像作成機能を廃止
# 2010.10.12 アニメ/CG専用の縮小ロジックを追加
# 2010.10.12 縦横比でDIETを判断するロジックを廃止した(実用的でないため)
# 2010.10.08 デジカメ高画素化に伴い、ファイルの制限を1900から11000に拡大
# 2010.10.08 パターンマッチの修正
# 2010.10.08 ループカウンターオーバのバグを修正
# 2008.06.03 WVGA液晶時代に合わせて仕様変更
# 2005.09.24 大きなオリジナルファイルの場合、自動でダイエットできるようにした
# 2005.09.18 SO505専用コード削除
# 2005.09.18 L-modeシェア低迷により専用ファイル作成を中止した
# 2002.09.04 update
#
# 各携帯で見られるように縮小画像を作る
#
sub make_snl_file{

	local($convert_option);		# 変換オプション
	local($snl_future_bit);		# 携帯用名の将来拡張ビット
	local($snl_ext);		# 携帯用の実際の拡張子

	local($ttmp_file_size);		# ファイルのサイズ（バイト）
	undef @SNL_FILE_STAT;		# ファイル属性を保持する配列

	local($ishot_iL_size);		# ishot-Lファイルのサイズ（バイト）
	local($ttmp_hw_type)="yokonaga";# 写真の縦長、横長種別
	local($ttmp_tenchi_mode)="12";	# 写真の天地
	local($ttmp_conv_log)="<BR>変換ログ開始<BR>";	# 変換ログ

	local($ttmp_white_paper_flag)=0;	# eBook(白黒)フラグ
	local($ttmp_white_paper_xy)=640;	# eBook(白黒)解像度

	# オリジナル画像をダイエットしたものに置き換える
	if($diet_org_img eq ""){
	  $diet_org_img=1;
	}

	# Diet後の縦横制限サイズ
	if($MICRO_DIET{'VHLIMIT'} eq ""){
	  $MICRO_DIET{'VHLIMIT'}="1024x768";
	}

	if($MICRO_DIET{'SIZE'} eq ""){
	  $MICRO_DIET{'SIZE'}="200"; # このサイズ以上のものをダイエット後登録する(標準400KB)
	}
	
	# imgboardとim.cgiの違いを吸収
	if($img_dir eq ""){
		$img_dir=$PM{'img_dir'}; # パスの違いを吸収
	}
	local($tcontent_length)	=$ENV{'CONTENT_LENGTH'}; # imgboardとim.cgiの違いを吸収
	$tcontent_length	=$web_get_file_size if($web_get_file_size>0);     

	# SNLの一時PPMファイルを保存する(1=保存する,0=保存しない)
	$store_snl_ppm_flag=0;

	# Webパーツの場合はサムネイルを作らない 2002.12
	if($FORM{'amode'} eq "post_webparts"){
		return;
	}

	# 画像データ以外はサムネイルを作らない 2003.05
	if($new_fname!~ /\.(jpe?g|gif|png|bmp)$/i){
		return;
	}

	# 過CPU負荷防止(21MB) #2002.12 2010.10 2013.01update
	# この設定でも200～800MBぐらいの一時メモリを使います。
	# 個人でメモリ2GB以上のVPSクラウドとかを借りてない限り、
	# リミッタを絶対拡大しないでください。
	if($tcontent_length > (21000*1024)){
#2002.12
#&error(" 容量オーバ CONTENT_LENGTH-$tcontent_length");
		return;
	}

	# 過CPU負荷防止(長編Anime-GIF対策) #2003.05
	if(($tcontent_length > (300*1024))&&($new_fname =~ /gif/i)){
#		&error(" 長編アニメＧＩＦ。安全装置作動 CONTENT_LENGTH-$tcontent_length");
		return;
	}

	# 元画像の素性を知る #2003.06
	if((-e "$img_dir/$new_fname")&&($imgsize_lib_flag== 1 )){	
		&imgsize("$img_dir/$new_fname");
		if(($IMGSIZE{'result'} ==1)&&($img_data_exists==1)){

			$ORGIMGSIZE{'type'}	="$IMGSIZE{'type'}";
			$ORGIMGSIZE{'width'}	="$IMGSIZE{'width'}";
			$ORGIMGSIZE{'height'}	="$IMGSIZE{'height'}";
			$ORGIMGSIZE{'hw_racio'}	="$IMGSIZE{'hw_racio'}";
			$ORGIMGSIZE{'square'}	= $IMGSIZE{'height'} * $IMGSIZE{'width'};

			$NOWIMGSIZE{'type'}	="$IMGSIZE{'type'}";
			$NOWIMGSIZE{'width'}	="$IMGSIZE{'width'}";
			$NOWIMGSIZE{'height'}	="$IMGSIZE{'height'}";
			$NOWIMGSIZE{'hw_racio'}	="$IMGSIZE{'hw_racio'}";
			$NOWIMGSIZE{'square'}	= $IMGSIZE{'height'} * $IMGSIZE{'width'};
		}
		undef %IMGSIZE;
	}

	# 縦長、横長の判定
	if($ORGIMGSIZE{'hw_racio'} > 100){
		$ttmp_hw_type="tatenaga";
	}elsif($ORGIMGSIZE{'hw_racio'} == 100){
		$ttmp_hw_type="tatenaga";
	}else{
		$ttmp_hw_type="yokonaga";
	}
	

	# eBook(白黒)検出フラグ
	if(($tcontent_length > (300*1024))&&($new_fname =~ /\.png$/i)){
	 if(($ORGIMGSIZE{'width'} >= 2560)||($ORGIMGSIZE{'height'} >= 2560)){
		 if($tcontent_length < (800*1024)){
		 	$ttmp_white_paper_xy=2560;
		 	$ttmp_white_paper_flag=1;
		 }
	 }elsif(($ORGIMGSIZE{'width'} >= 2048)||($ORGIMGSIZE{'height'} >= 2048)){
		 if($tcontent_length < (700*1024)){
		 	$ttmp_white_paper_xy=2048;
		 	$ttmp_white_paper_flag=1;
		 }
	 }elsif(($ORGIMGSIZE{'width'} >= 1280)||($ORGIMGSIZE{'height'} >= 1280)){
		 if($tcontent_length < (400*1024)){
		 	$ttmp_white_paper_xy=1280;
		 	$ttmp_white_paper_flag=1;
		 }
	 }elsif(($ORGIMGSIZE{'width'} >= 1024)||($ORGIMGSIZE{'height'} >= 1024)){
		 if($tcontent_length < (300*1024)){
		 	$ttmp_white_paper_xy=1024;
		 	$ttmp_white_paper_flag=1;
		 }
	 }
	}

	
	if($ORGIMGSIZE{'hw_racio'} > 100){
		$ttmp_hw_type="tatenaga";
	}elsif($ORGIMGSIZE{'hw_racio'} == 100){
		$ttmp_hw_type="tatenaga";
	}else{
		$ttmp_hw_type="yokonaga";
	}
	
	# 30KBよりサイズが大きい場合は、画質重視のために、高サンプリング中間ファイルを作る。
	# 30KBよりサイズが大きい場合は、画質重視のために、VGAリサイズファイルを作る。
	if(($tcontent_length > (30*1024))&&($new_fname =~ /\.(jpe?g|png|bmp)$/i)){ # 2013.02 change

		# 携帯用のファイル名を作る（ここに入れたものだけが作成される）

# 2009.12 minaoshi
# jpg-pcvga,pc-wvga,,jpg-iL,jpg-iS, imgboard in use.



		@SNL_DATA=('jpg-pcvga','jpg-iL','jpg-iS','jpg-ps1');

		$new_snl_orig{'Low'}   = "snl"."$unq_id"."Low"."0"."\.ppm";	# 最後の0は将来拡張用
		$new_snl_orig{'High'}  = "snl"."$unq_id"."High"."0"."\.ppm";	# 最後の0は将来拡張用



		$ttmp_conv_log.=" ORG $tcontent_length Byte ハイサンプリングモードが選択されました<BR>";
	}else{
		# 携帯用のファイル名を作る（ここに入れたものだけが作成される）

		# 10～30KBの場合
		if($tcontent_length > (10*1024)){
		  @SNL_DATA=('jpg-iL','jpg-iS','jpg-ps1');
		# ishotSや携帯の小さい画像で拡大して汚くなるのを防ぐ
		# check code TODO 2011.06
		
		}else{
		  @SNL_DATA=('jpg-iL','jpg-iS','jpg-ps1');
		}
		$new_snl_orig{'Low'}   = "snl"."$unq_id"."Low"."0"."\.ppm";	# 最後の0は将来拡張用
		$ttmp_conv_log.=" $tcontent_length Byte ローサンプリングが選択されました<BR>";
	}


	# 携帯用の作った形式を記憶する
	undef @SNL_MADE_DATA;

	# サンプリング画素(大きくすると2乗で負荷が上がるので注意)
	$SMPL_SIZE{'High'}="480x640";
	$SMPL_SIZE{'Low'} ="293x293";


# 2009.12 minaoshi
# jpg-iS,jpg-pcvga,jpg-iL,pc-wvga imgboard in use.

	# 安全サイズ
	# 携帯でメモリをオーバしないように
	# 基本サイズをやや小さ目に定義する
	$SNL_SIZE{'jpg-ps1'}	="56x56"; # 2005.9 JPEG Packet Saver1

	# 特殊用途
	# PCサムネイルでも使うやつ(天地逆転不可)
	$SNL_SIZE{'jpg-pcvga'}	="480x480";	# VGAサイズ # 2008.06 change

	# 純粋に携帯用(天地逆転ＯＫ)
# 2005.09 change NetFrontの実情に合わせた
	$SNL_SIZE{'jpg-iL'}	="232x240";	# i-shotインターネットサイズ(L)
	$SNL_SIZE{'jpg-iS'}	="120x120";	# i-shotインターネットサイズ(S)


	# 初期チェック
	unless(-e "$PM{'conv_prog'}"){
		&error(" エラー。指定された場所 $PM{'conv_prog'} に画像変換ソ\フ\ト\がありません ");
		return;
	}
	unless($new_fname=~ /\.(jpe?g|gif|png|bmp)$/i){
		return;
	}

	$new_snl_fname   = "snl"."$unq_id";

	# 携帯用＆メール通知用に携帯用画像を作成する

	if($ttmp_hw_type eq "yokonaga"){
		  $ty_option = " -rotate 90";
		  $ttmp_tenchi_mode= "3";
		  $ttmp_conv_log.=" 3時方向が上<BR>";
		  $NOWIMGSIZE{'width'}	="$ORGIMGSIZE{'height'}";
		  $NOWIMGSIZE{'height'}	="$ORGIMGSIZE{'width'}";
		  $NOWIMGSIZE{'hw_racio'}=int(100*$ORGIMGSIZE{'width'}/$ORGIMGSIZE{'height'}) if($ORGIMGSIZE{'height'}>0);

# 2005.09 change
		  $ty_option = "";
		  $ttmp_tenchi_mode= "12";
		  $ttmp_conv_log.=" 12時方向が上<BR>";
		  $NOWIMGSIZE{'width'}	="$ORGIMGSIZE{'width'}";
		  $NOWIMGSIZE{'height'}	="$ORGIMGSIZE{'height'}";
		  $NOWIMGSIZE{'hw_racio'}=int(100*$ORGIMGSIZE{'height'}/$ORGIMGSIZE{'width'}) if($ORGIMGSIZE{'width'}>0);

	# 縦長画像を検出
	}else{
		$ty_option = "";
		# 横撮り宣言されている場合
		  $ttmp_tenchi_mode= "12";
		  $ttmp_conv_log.=" 12時方向が上<BR>";
	}


	# 携帯用＆メール通知用に携帯用画像を作成する

	# 最初に中間ファイルを作る(High Lowの２種類)
	foreach(keys %new_snl_orig){
	 $ty_option="" if($_ eq "High"); # Highは回転しない

#2014.11 TMP ジオシティーズ新サーバ相性問題調査
#$er_return = `$PM{'conv_prog'} \"$img_dir/$new_fname\"$ty_option -resize $SMPL_SIZE{$_} +profile \"\*\" $img_dir/$new_snl_orig{$_}`;
#&error(" $PM{'conv_prog'} \"$img_dir/$new_fname\"$ty_option -resize $SMPL_SIZE{$_} +profile \"\*\"  $img_dir/$new_snl_orig{$_} <BR>-管$er_return<BR>\n");

	 open  (COMMAND,"| $PM{'conv_prog'} -resize $SMPL_SIZE{$_} -strip \"$img_dir/$new_fname\"$ty_option $img_dir/$new_snl_orig{$_}") || &error(" 管理者設定にエラーがあります<BR>画像変換プログラム$PM{'conv_prog'}が見つかりません。画像変換プログラムのパスを再確認してください。<BR>\n");
	 close (COMMAND);


	 @SNL_FILE_STAT=stat("$img_dir/$new_snl_orig{$_}");	# 属性を調査
	 $ttmp_file_size=$SNL_FILE_STAT[7];		# ファイルサイズを取得

	$ttmp_conv_log.="サンプリング conv実行 $PM{'conv_prog'} -resize $SMPL_SIZE{$_} -strip \"$img_dir/$new_fname\"$ty_option $img_dir/$new_snl_orig{$_} <BR>$ttmp_file_size BYTE<BR>";


	 # R7で採用するSNLデータのフォーマットは、
	 # 拡張子-将来拡張用番号-バイト/拡張子-将来拡張用番号-バイト/...とする。
	 push(@SNL_MADE_DATA,"ppm-$_-$ttmp_file_size");

	}

	  foreach $snl_type(@SNL_DATA){


	    # GIFはi-mode用256色パレットでディザリングする

	    # 注：GIFにおいてLZWアルゴリズムは必須ではない。
	    # ImageMagickではデフォルトではLZW=offであるので、
	    # このスクリプトでは、この問題を扱わないものとする。
	    # また、この問題を扱うスイッチ機能もつけない。
	    # なお、ライブラリにおいて、LZWをconfigureして意図的に
	    # ONにする場合は、configureした人間 自己責任であることを
	    # 認識してから、使うこと。

	    if($snl_type =~ /gif/i){
		if(-e "i-palette.gif"){
		  $convert_option = "-map i-palette.gif";
		}
	    }
	    # JPEGはEXIF等の余計なデータを落とし、できるだけダイエットする
	    if($snl_type =~ /jpe?g/){
		# 2014 update
		  $convert_option = " -strip";
	    }

	    # 天地モードが12時以外になっていて、かつPC用のサムネイルを作る場合は回転する
	    if(($snl_type =~ /pc/)&&($snl_type !~ /pcvga/)){
		if($ttmp_tenchi_mode == 9){
		  $convert_option .= " -rotate 90";
		}elsif($ttmp_tenchi_mode == 3){
		  $convert_option .= " -rotate -90";
		}
	    }

	    # iLはシャープ補正する
	    if($snl_type =~ /iL/){
#		  $convert_option .= " -sharpen 90";
	    }

	    # vga系は高サンプル画像を使う。それ以外は低サンプル画像を使う
	    if(($snl_type =~ /vga/i)&&($new_snl_orig{High} ne "")){
		  $convert_sample_type  = "High";
		  $convert_option 	= "";
	    }else{
		  $convert_sample_type = "Low";
	    }


	    # 携帯用タイプから将来拡張名ビットと拡張子を分離して使う
	    ($snl_ext,$snl_future_bit)=split(/\-/,$snl_type);

	    $loop_count=0;
	    $tmp_size_ok=0;
	    # 2005.05.05 OK
	    $t_quality=75;

	    if($SNL_FSIZE{$snl_type}){
	     $ttmp_conv_log.="  $snl_type-変換処理開始-希望のサイズのものができるまでループ開始<BR>";
	    }else{
	     $ttmp_conv_log.="  $snl_type-変換処理開始(一発型)<BR>";
	    }

	    # 希望のサイズのものができるまでループする
	    while($tmp_size_ok!=1){

	      # 2005.09 新規
	      # とにかく小さいやつを作る
	      if($snl_type =~ /ps1/){
			  $t_quality=25;# 
			  $convert_option .= " -quality $t_quality";
			  $mes_option .= "-$loop_count回目- -quality $t_quality";
			  $ttmp_conv_log.="-$loop_count回目-  -quality $t_quality<BR>";
	      }

	      $loop_count++;

		$ttmp_conv_log.=" conv実行 元size=$SMPL_SIZE{$convert_sample_type} -resize $SNL_SIZE{$snl_type} $convert_option$crop_option <BR>";

		$ttmp_conv_log.=" 実行 $PM{'conv_prog'} -resize $SNL_SIZE{$snl_type}$convert_option$crop_option  \"$img_dir/$new_snl_orig{$convert_sample_type}\"  $img_dir/$new_snl_fname$snl_future_bit\.$snl_ext <BR>";

	      open  (COMMAND,"| $PM{'conv_prog'} -resize $SNL_SIZE{$snl_type}$convert_option$crop_option  \"$img_dir/$new_snl_orig{$convert_sample_type}\"  $img_dir/$new_snl_fname$snl_future_bit\.$snl_ext") || &error(" 管理者設定にエラーがあります<BR>画像変換プログラム$PM{'conv_prog'}が見つかりません。画像変換プログラムのパスを再確認してください。<BR>\n");
	      close (COMMAND);

	      @SNL_FILE_STAT=stat("$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext");
	      $ttmp_file_size=$SNL_FILE_STAT[7];	# ファイルサイズを取得

	      $ttmp_conv_log.=" 変換結果 $snl_type -$loop_count- サイズ $ttmp_file_size Byte<BR>";

	      # GIFアニメの時にサムネイルサイズが取れなくなる問題を修正
	      if(($ttmp_file_size eq "")&& ($ext=~ /gif/i) ){
		if( -e "$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.0"){
		   @SNL_FILE_STAT=stat("$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.0");
		   $ttmp_file_size=$SNL_FILE_STAT[7];	# ファイルサイズを取得
		}
	      }

	      if($SNL_FSIZE{$snl_type} eq ""){
		$tmp_size_ok=1;
	      }
	      if($ttmp_file_size <= $SNL_FSIZE{$snl_type}){ # 2005.09修正
		$tmp_size_ok=1;
	      }
	      if($loop_count >= 4){ # 安全装置
		&error("loop_count $loop_count が多すぎます。$mes_option 設定を見直してください $snl_type-$ttmp_conv_log");
		$tmp_size_ok=1;
	      }

	      if(($tmp_size_ok==1)&&($SNL_FSIZE{$snl_type})){
		    $ttmp_conv_log.="  $snl_type-ループ終了<BR>";
	      }
	    }

	   # animation GIF等の複数型静止画に対する対策
	   if($ext=~ /gif/i){
		if( -e "$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.0"){
			rename("$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.0","$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext");
			local($tmp_exit_flag)=0;
			local($tmp_add_ext)=1;
			while($tmp_exit_flag==0){
			  if(-e "$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.$tmp_add_ext"){
				unlink("$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext\.$tmp_add_ext");
				$tmp_add_ext++;
			  }else{
				$tmp_exit_flag=1;
			  }
			}
		}
	   
	   }



	   if(($snl_type =~ /gif/i)||($snl_type =~ /jpe?g/i)||($snl_type =~ /bmp/i)||($snl_type =~ /png/i)){
	    if((-e "$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext")&&($imgsize_lib_flag== 1 )){	
		&imgsize("$img_dir/$new_snl_fname$snl_future_bit\.$snl_ext");
		if(($IMGSIZE{'result'} ==1)&&($img_data_exists==1)){
		#	$IMGSIZE{'name'}で渡す;
		}else{
			undef %IMGSIZE;
		}
	    }
	   }

	    # 携帯用ファイルを作った形式をリストに記憶
	    push(@SNL_MADE_DATA, "$snl_ext-$snl_future_bit-$ttmp_file_size-$IMGSIZE{'width'}-$IMGSIZE{'height'}-$IMGSIZE{'hw_racio'}");

	    if($snl_type eq "jpg-iL"){
		$ishot_iL_size=$ttmp_file_size;
	    }
	    undef $convert_option; # clear
	  }

	# オリジナルの画像をダイエットする(30KB以下には設定できないので注意すること)
	# 事前チェック
	if($diet_org_img == 1){

	  $ttmp_conv_log.="<BR> オリジナルの画像をダイエットします。減量指定 $MICRO_DIET{'SIZE'} KB / ANIME_BBS_MODE $MICRO_DIET{'ANIME_BBS_MODE'}<BR>";
	
	  if($MICRO_DIET{'SIZE'} < 100){
		&error("設定エラー＄MICRO_DIET{'SIZE'}は100KB未満に設定できません。設定値を変更してください ");
	  }
	  
	  # 実写とアニメで最適値が違うので、これを判別するためのフラグ
	  $maybe_photo_flag=0;
	  
	  if(($tcontent_length > ($MICRO_DIET{'SIZE'}*1024))&&($new_fname =~ /\.(jpe?g|png|bmp)$/i)){ # 2013.02.05 change
		local($about_hw)=int($IMGSIZE{'hw_racio'}/10);
		if(($about_hw == 13)||($about_hw == 17)){
			$ttmp_conv_log.=" hw 13 or 17 ＄maybe_photo_flag=1 <BR>";
			$maybe_photo_flag=1;
		}elsif(($about_hw == 7)||($about_hw == 5)){
			$ttmp_conv_log.=" hw 7 or 5 ＄maybe_photo_flag=1 <BR>";
			$maybe_photo_flag=1;
		}else{
			$ttmp_conv_log.=" hw 13,17,7,5以外でした。 ＄maybe_photo_flag=0 <BR>";
			$maybe_photo_flag=0;
		}
		$ttmp_conv_log.=" dietします <BR>";
		$diet_org_img = 1;
	  }else{
		$ttmp_conv_log.=" dietしません <BR>";
		$diet_org_img = 0;
	  }

	  # Debug用
	  if($FORM{'name'} =~ /no_diet/i){
				$ttmp_conv_log.=" no_diet 指定があったので、dietしません <BR>";
				$diet_org_img = 0;
	  }
	}

	if($diet_org_img == 1){


	# 元がデジカメ写真で数Mあると、XGAリサイズでも500KB以上と大きくなり
	# 過ぎる現象に対処 #2010.10add

	# 実写写真の場合
	if($maybe_photo_flag == 1){
	 if($tcontent_length > (1800*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (1400*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (550*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (400*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 85";
	 }elsif($tcontent_length > (300*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (250*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 75";
	 }elsif($tcontent_length > (200*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 70";
	 }elsif($tcontent_length > (150*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 60";
	 }elsif($tcontent_length > (100*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 50";
	 }else{
		$MICRO_DIET{'QUALITY'}="";
	 }

	# アニメ専用掲示板の場合
	# 超高画質
	}elsif($MICRO_DIET{'ANIME_BBS_MODE'} == 2 ){
	 if($tcontent_length > (1500*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
		$MICRO_DIET{'VHLIMIT'}="1920x1080";
	 }elsif($tcontent_length > (1000*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
		$MICRO_DIET{'VHLIMIT'}="1024x960";
	 }elsif($tcontent_length > (700*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
	 }elsif($tcontent_length > (550*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
	 }elsif($tcontent_length > (400*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
	 }elsif($tcontent_length > (300*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 96";
	 }elsif($tcontent_length > (250*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (200*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	# アニメは200KB以下では逆に大きくなってしまうことも
	# あるので、200以下は設定させないほうがいいだろう。
	 }elsif($tcontent_length > (150*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 85";
	 }elsif($tcontent_length > (100*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }else{
		$MICRO_DIET{'QUALITY'}="";
	 }
	# アニメ専用掲示板の場合
	}elsif($MICRO_DIET{'ANIME_BBS_MODE'} == 1 ){
	 if($tcontent_length > (1500*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
		$MICRO_DIET{'VHLIMIT'}="1024x960";
	 }elsif($tcontent_length > (1000*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
		$MICRO_DIET{'VHLIMIT'}="1024x960";
	 }elsif($tcontent_length > (700*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
	 }elsif($tcontent_length > (550*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 95";
	 }elsif($tcontent_length > (400*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (300*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (250*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (200*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	# アニメは200KB以下では逆に大きくなってしまうことも
	# あるので、200以下は設定させないほうがいいだろう。
	 }elsif($tcontent_length > (150*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (100*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 75";
	 }else{
		$MICRO_DIET{'QUALITY'}="";
	 }	 
	# 実写写真の場合
	}elsif($maybe_photo_flag == 1){
	 if($tcontent_length > (1800*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (1400*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }elsif($tcontent_length > (250*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 75";
	 }elsif($tcontent_length > (100*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 65";
	 }else{
		$MICRO_DIET{'QUALITY'}="";
	 }
	# アニメやキャプチャの場合
	}else{
	 if($tcontent_length > (1800*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (500*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 90";
	 }elsif($tcontent_length > (250*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 93";
	 }elsif($tcontent_length > (150*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 93";
	 }elsif($tcontent_length > (100*1024)){
		$MICRO_DIET{'QUALITY'}=" -quality 80";
	 }else{
		$MICRO_DIET{'QUALITY'}="";
	 }
	}

	$ttmp_conv_log.=" dietのQualityは $MICRO_DIET{'QUALITY'}に決定されました  <BR>";


	# Debug用
	if($FORM{'name'} =~ /resize_quality(\d+)/i){
	 if(($1 > 10)&&($1 < 101)){
		$ttmp_conv_log.=" resize_quality指定があったので$MICRO_DIET{'QUALITY'}から $1 へ上書きします  <BR>";
		$MICRO_DIET{'QUALITY'}=" -quality $1";
	 }
	}

	$ttmp_new_fname="";
			
	# スマホやタブレットの巨大PNG(画面キャプチャ)を検出
	if(($tcontent_length > (350*1024))&&($new_fname =~ /\.(png|bmp)$/i)){

		$ttmp_conv_log.=" スマホやタブレットの巨大PNG(画面キャプチャ)を検出 retina_flag  <BR>";

		$MICRO_DIET{'VHLIMIT'}="1280x1280";
	 	$MICRO_DIET{'QUALITY'}=" -quality 60";

		# 基本 元画像の解像度を尊重する
		
		# 電子ブック的なもの（画素の割に元サイズが小さい）は,解像度を優先させるためにretina保存する。
		# あるいは隠し機能として名前欄にretinaという文字が書いてあると、その場合もretina保存とする

		if(($ORGIMGSIZE{'width'} >= 2560)||($ORGIMGSIZE{'height'} >= 2560)){
		 if(($FORM{'name'} =~ /retina/i)||($ttmp_white_paper_flag==1)){
			$MICRO_DIET{'VHLIMIT'}="2560x2560";
		 	$MICRO_DIET{'QUALITY'}=" -quality 45";
		 	$MICRO_DIET{'QUALITY'}=" -quality 35" if($ttmp_white_paper_flag==1); # eBook検出フラグ
		 }
		}elsif(($ORGIMGSIZE{'width'} >= 2048)||($ORGIMGSIZE{'height'} >= 2048)){
		 if(($FORM{'name'} =~ /retina/i)||($ttmp_white_paper_flag==1)){
			$MICRO_DIET{'VHLIMIT'}="2048x2048";
		 	$MICRO_DIET{'QUALITY'}=" -quality 45";
		 	$MICRO_DIET{'QUALITY'}=" -quality 35" if($ttmp_white_paper_flag==1); # eBook検出フラグ
		 }
	 	}elsif(($ORGIMGSIZE{'width'} >= 1280)||($ORGIMGSIZE{'height'} >= 1280)){
			$MICRO_DIET{'VHLIMIT'}="1280x1280";
		 	$MICRO_DIET{'QUALITY'}=" -quality 60";
	 	}elsif(($ORGIMGSIZE{'width'} >= 1156)||($ORGIMGSIZE{'height'} >= 1156)){
			$MICRO_DIET{'VHLIMIT'}="1156x1156";
		 	$MICRO_DIET{'QUALITY'}=" -quality 70";
	 	}elsif(($ORGIMGSIZE{'width'} >= 1024)||($ORGIMGSIZE{'height'} >= 1024)){
			$MICRO_DIET{'VHLIMIT'}="1024x1024";
		 	$MICRO_DIET{'QUALITY'}=" -quality 80";
	 	}elsif(($ORGIMGSIZE{'width'} >=  960)||($ORGIMGSIZE{'height'} >=  960)){
			$MICRO_DIET{'VHLIMIT'}="960x960";
		 	$MICRO_DIET{'QUALITY'}=" -quality 80";
	 	}elsif(($ORGIMGSIZE{'width'} >=  800)||($ORGIMGSIZE{'height'} >=  800)){
			$MICRO_DIET{'VHLIMIT'}="800x800";
		 	$MICRO_DIET{'QUALITY'}=" -quality 80";
	 	}elsif(($ORGIMGSIZE{'width'} >=  640)||($ORGIMGSIZE{'height'} >=  640)){
			$MICRO_DIET{'VHLIMIT'}="640x640";
		 	$MICRO_DIET{'QUALITY'}=" -quality 80";
	 	}else{
			$MICRO_DIET{'VHLIMIT'}="640x640";
		 	$MICRO_DIET{'QUALITY'}=" -quality 80";
	 	}

		$ttmp_new_fname="$new_fname";
		$ttmp_new_fname=~ s/\.png$/\.jpg/i;

		$ttmp_conv_log.=" サイズ縮小のため、PNGをJPEGに置き換えました サイズ制限 $MICRO_DIET{'VHLIMIT'} $MICRO_DIET{'QUALITY'} eBook白黒flag $ttmp_white_paper_flag xy_max $ttmp_white_paper_xy<BR>";

		$ttmp_conv_log.=" 実行$PM{'conv_prog'} -resize $MICRO_DIET{'VHLIMIT'}$MICRO_DIET{'QUALITY'} \"$img_dir/$new_fname\" $img_dir/$ttmp_new_fname <BR>";

	  open  (COMMAND,"| $PM{'conv_prog'} -resize $MICRO_DIET{'VHLIMIT'}$MICRO_DIET{'QUALITY'} \"$img_dir/$new_fname\" $img_dir/$ttmp_new_fname") || &error(" 管理者設定にエラーがあります<BR>画像変換プログラム$PM{'conv_prog'}が見つかりません。画像変換プログラムのパスを再確認してください。<BR>\n");
	  close (COMMAND);

		unlink("$img_dir/$new_fname");
		$new_fname="$ttmp_new_fname";

	}else{

	$ttmp_conv_log.=" 実行$PM{'conv_prog'} -resize $MICRO_DIET{'VHLIMIT'}$MICRO_DIET{'QUALITY'} -strip \"$img_dir/$new_fname\" $img_dir/$new_fname <BR>";
	
	# JPEG(通常はこっちの処理を通る)
	  open  (COMMAND,"| $PM{'conv_prog'} -resize $MICRO_DIET{'VHLIMIT'}$MICRO_DIET{'QUALITY'} -strip \"$img_dir/$new_fname\" $img_dir/$new_fname") || &error(" 管理者設定にエラーがあります<BR>画像変換プログラム$PM{'conv_prog'}が見つかりません。画像変換プログラムのパスを再確認してください。<BR>\n");
	  close (COMMAND);

	}


	  # 原画のサイズを変更
          @SNL_FILE_STAT=stat("$img_dir/$new_fname");
		$tcontent_length_diet=$SNL_FILE_STAT[7];	# ファイルサイズ
		$ttmp_conv_log.=" $tcontent_length_diet へdietされました  <BR>";

 	}

	  if($store_snl_ppm_flag != 1){
	   foreach(keys %new_snl_orig){
	    if(($new_snl_orig{$_} ne "")&&(-e "$img_dir/$new_snl_orig{$_}")){
	     unlink("$img_dir/$new_snl_orig{$_}");
	     # 消したので、リストから抜く
	     shift(@SNL_MADE_DATA);
	    }
	   }
	  }

	$snl_location="$img_dir/$new_snl_fname";

	# SNLとして存在するデータのリストを作る
	foreach (@SNL_MADE_DATA){
		# エラー時にゴミを消す必要があるため、グローバル変数にする
		$existing_snl_type_list.="$_"."\/";
	}

	
undef %IMGSIZE; # クリア

	# Debug用
	if($FORM{'name'} =~ /snl_conv_log/i){
		&error(" 変換ログ確認 $ttmp_conv_log");
	}
}
#
1;

