#!/usr/bin/perl
#
# ==== デバック用パラメータ変更しないこと
# (デバック時3 ,通常は1にすること)
$debug_mode=1;
#
# ==== デバック用パラメータここまで
#
#
#     画像アップロード掲示板 携帯アクセスCGI(Beta23)
#
#       im.cgi 2010(imgboard 201x Base)
#
#	 Copyright(C)1998-2014 imgboard.com
#	 Origin Program		to-ru@big.or.jp
#	 Updated by 		imgboard.comスタッフ talk@big.or.jp
#
#		 Support site URL http://imgboard.com
#
# (注意)当スクリプトはiモード用の掲示板Freeの画像アップロード掲示板、
# imgboard.cgiを運営されている掲示板管理者やそのお友達のユーザ方々が、
# iモードからも、コメント投稿、閲覧、管理をできるように作った機能追加スクリ
# プトです。imgboard.cgiと同じディレクトリに入れて使います。当スクリプト単
# 体では動作しませんのでご注意ください。
#
#  <改変履歴>-12/17/2014/01
#  2014/12/17 XSS（クロススクリプティング）脆弱性対策で修正対処版をリリース
#  2014/11/30 メール経由の投稿部分を削除
#  2014/11/10 中国発の偽ブランド品、コピー商品系のSPAM対策を強化
#  2014/11/10 古い不要コードの整理。着メロやOffice関係ファイル対応部の削除
#  2012/09/06 iOS6最適化(iPhoneの標準safariからのアップロードに対応)
#  2012/05/01 SPAMフィルタの設定を更新
#  2012/04/18 (CGI設置注意!!)スクリプトの改行コードをCR+LFからLFに変更
#  2012/04/18 (CGI設置注意!!)perlのパスを/usr/local/bin/perlから/usr/bin/perlに変更
#  2012/04/18 XSS（クロススクリプティング）対策を強化
#  2012/04/18 自動住所リンク修正
#  2011/12/15 XSS（クロススクリプティング）対策で修正
#  2010/09/30 docomo携帯対応部を2010.09最新情報に更新
#  2010/08/18 外部ファイル、スパム単語リスト(spamword.cgi)の読み込みに対応
#  2010/04/09 ワード検索メニューにおけるクロスサイトスクリプティングの可能性に対し対策をした
#  2010/02/17 トリップ機能(なりすまし防止）をテスト実装
#  2010/02/15 SPAM対策にワインタイムトークン機能を追加
#  2010/01/23 android用のCGIコードを追加
#  2009/12/18 中国語圏からのSPAM対策を強化
#  2008/07/16 youTubeをiPod/iPhoneで見た場合に、記事が出ないバグを修正
#  2008/06/02 906iリリースに伴いアップロード上限リミッタを2MBに拡大
#  2007/05/18 SoftBank対応部分が不完全たった点を修正
#  2006/10/21 youTubeタグ対応

#  2004/11/27 添付の最大サイズをFOMA901シリーズに合わせて500KBとした
#  2004/08/28 blatj以外にsendまねーるに対応した
#  2004/06/12 テキストの入力欄が従来300文字固定だったが、機種の能力に応じて拡大するようにした
#  2001/09/20 高速化＆FOMA用のコードを追加（動作は未確認）
#  2000/09/28 imgboard1.22R5以降のログ形式に対応
#  2000/04/05 iモード対応版をお遊びで作ったが、(1)条件分岐が増え、スクリプトが
#  複雑になり汚くなった(2)パケット課金なのでコメントアウトを減らさないとお
#  金がもったいないらしい,等状況から別スクリプトとしてリリースすることにした。
#
# <利用規定>
#  1.(著作権について)
#   1.1当CGIの著作権及び使用許諾権は、imgboard.com（以下 当方）が占有してお
#      ります。
#  2.(使用許諾)
#      ・このCGIは,当利用規定すべてに従っていただく場合に限り,個人,法人にか
#        かわらず,自由にカスタマイズし、無償で利用していただく事ができます。
#      ・なお、ひとつでも満たせない項目があった場合は,基本的に、その利用を許諾
#        しません。よくご確認ください。
#      ・項目をすべて満たした場合も、当方がその使用を不適当だと認めた場合,その
#        使用を中止させていただく場合があります．あらかじめご了承ください．
#
#  2.1 使用許諾条件
#    A.当スクリプトの利用規定部、著作権表示部、当タイトル部の改変をしていない
#      こと
#    B.改造および、カスタマイズしたスクリプトの無断再配布をしないこと。
#      特に、条件A,Bを満たさない場合は,その利用を一切禁止します。
#
#  3.(制限事項について)
#   3.1 改造、非改造を問わず、当方に無断で再配布することを固く禁止します。
#   3.2 日本以外の国籍のユーザを対象にした掲示板での利用を禁止します。
#   3.3 当著作権表示ならびに,掲示板下部の著作権表示とリンクを改変及び,削除
#       することは固く禁止します。
#
#  4.(非制限事項について)
#   4.1 改造は御自由にしていただいて構いません    （ただし3.3に注意して下さい）
#   4.2 商用利用は御自由にしていただいて構いません（ただし3.3に注意して下さい）
#   4.3 個人の利用、および法人の利用を許可します。（ただし3.3に注意して下さい）
#
#  5.(免責事項)
#   5.1 掲示板の管理責任は,100%その掲示板の設置者にあるものとします。当サイトは
#       その管理責任を一切負いません。
#   5.2 万一このCGIにより損害や不利益受けたとしても、当方は一切その責任を負う
#       義務を持ちません．あらかじめご了承ください．
#   5.3 当CGIに不具合、機能不足、バグなどがあった場合も、当方はその修正の義務を
#       負わないものとします。
#
#  6.(その他)
#    当利用規定は予告なく改変、追記される場合があります。あらかじめご了承ください．
###############################################################################
# 基本構成（初期設定はこの構成を前提に解説します）
#
# (当スクリプトはもともとimgboard.cgiを運営されている掲示板管理者やそのお友達
# の方が、imodeから投稿、閲覧、管理をできるように作った追加スクリプトです。単
# 体では動作しませんのでご注意ください)
#
# public_html（ホームページディレクトリ）
# |
# |-- cgi-bin（任意のディレクトリ705）
#   |
#   |--img-box(757 または 707)(画像保存用ディレクトリ）
#   |
#   |-- jcode_sj.pl  (755 または 705)(ライト版の日本語ライブラリ)
#   |-- imgboard.cgi (755 または 705)(imgboard本体)
#   |
#   |-- im.cgi (755 または 705)(iモードからのアクセス用本体) <===当スクリプト
#   |
#   |-- imgsize.pl   (755 または 705)(画像サイズ解析ライブラリ)
#   |-- file.dat     (666 または 606)(記事データ保存用)
#   |-- fileback.dat (666 または 606)(上記ファイルのバックアップ)
#   |-- icon.dat     (666 または 606)(WebPartsデータ保存用)
#
# ・( )内は属性（パーミッション）です。最初、括弧内の左の数字で試し,
#  動くかどうか確認して下さい。確認がとれたら、３文字の数字の真ん中を0に変更し,
#  動作するかチェックして下さい。どちらでも正常動作できるならば、できるだけ
#  右の方の値を使ってください。
#  (一般にプロバイダにおいては、真ん中の数字を0にすると、セキュリティ的により
#  厳しくなり、他人からファイルを書換えられたりする危険性が減って安全度が上がり
#  ます。ただし中にはCGIが動かなくなるプロバイダもありますので、この場合は
#  左の値をご使用ください）
#
#
###############################################################################

#=======================================================================#
#  初期設定
#=======================================================================#

#  先頭に#のある行は読み込まれません．

#==================================#
#        <必須設定項目>            #
#==================================#
#
$PM{'admin_passwd'} = '4139';		# 管理人による記事削除時のパスワード
#					# (変更してください)
#
$PM{'title'} 	= "画像掲示板(携帯用URL)";	
#  ↑「お気に入り or ブックマーク」保存時のタイトルになります。
#
#  ■<終了時戻り先ＵＲＬ>
#
#  掲示板の「HOME」を押したときに、下記ＵＲＬへ戻ります。
#  (URLを下記デフォルトURLから変更しますと、ページにリンクが自動的に出現)
#
$PM{'back_url'} ='http://あなたのプロバイダ/あなたのディレクトリ/index.html';
#
#  ■<imgboard本体の名前>
#
#  パソコンユーザが携帯アクセスのURLにアクセスした場合、それを自動検出し、
#  「PC、スマホの方はこちらへ」とimgboard本体のURLを紹介するようになっています。
#  そのURLを作るために、imgboard.cgi本体を下記で指定してください。なお本体
#  のCGI名をデフォルトから変更してない場合は・・特に設定の必要はありません
#
$PM{'cgi_hontai_name'}	= 'imgboard2015.cgi';	# imgboard本体の名前
#
#  ■<サーバ保存メッセージ数>	
#    ↑imgboardのサーバ保存メッセージ数の２－３倍にしてください
#
#  この件数を超えると、古いものから削除されます.記事と画像は同時に消えます。
#  デフォルトは600．
#
#  imodeと共用する場合は、テキスト投稿の比率が上がりますので、保存メッセージ
#  数を2-3倍に増やした方がバランスいいでしょう。
#
$PM{'max_message'} 		= 600;
#
#  ■ <時差>	# imgboardと設定を合わせてください
#
#  海外サイトに設置した場合、投稿時刻が現地時刻になってしまいます。
#  これを日本時刻に修正する場合には、以下の項目で時差を設定してください。
#  (設定例) 時差を15時間にする場合 $PM{'gisa'}=15;という風に設定してください。
#
$PM{'gisa'}=0;		# 時差(h)
#
#  ■<投稿ファイルの格納ディレクトリ>
#
#  カメラ付きの携帯から投稿されたファイルを保存しておく場所です。
#  imgboardの$img_dirと設定を合わせて下さい。
#  なお、よくわからない場合はデフォルトのまま設定を変更しないで
#  そのままにしておいてください。
#
$PM{'img_dir'} = './img-box';		# デフォルト位置
#
#  ■<投稿ファイルに付けるURL前半部>
#
#  アップロードしたファイルの頭につけるURL前半部を指定してください。
#  (掲示板の画像ファイルのURL)=(ここで指定したURL前半部)+(ファイル名)に
#  なります。たとえば、アップロードしたファイルをブラウザで見るときのURLが
#  http://www.big.jp/~talk/imgboard/img-box/img200101281010.jpgになるなら
#  http://www.big.or.jp/~talk/imgboard/img-boxを前半部に指定してください。
#  (なお、最後に/は付けないでください)
#
$PM{'img_url'} ='http://あなたのプロバイダ/あなたのディレクトリ/img-box';
#
#  <掲示データ保存ファイル名>	# imgboardと設定を合わせてください
#
#  テキストデータの保存用ファイルの名前です．
#  imgboard.cgiと同じ場所に入れる場合はこのパス指定のまま.
$PM{'file'}= './file.dat';
#
#
#  <アクセスカウンタデータ保存ファイル名> (R7 NEW)
#
#  アクセスカウンタのデータを保存するためのファイルの名前です．
#  imgboard.cgiと同じ場所に入れる場合はこのパス指定のまま.
$PM{'count_data_file'}	= './count.dat';
#
#  <アクセスログのファイル名> (R7 NEW)
#
#  アクセス元のブラウザの種類とホスト情報を保存するためのファイルの名前です．
#  imgboard.cgiと同じ場所に入れる場合はこのパス指定のまま.
$PM{'access_log_file'}	= './access_log.dat';
#
#  <日本語コード変換ライブラリ>
#
#  imgboard.cgiと同じ場所に入れる場合は、このパス指定のまま.
#  注:jcode_sj.plはjcode.plの機能限定スリム版です。SJISへの変換機能のみ。
$PM{'jcode_name'}= 'jcode_sj.pl';
#
#  <画像プロパティ認識ライブラリ>
#
#  imgboard.cgiと同じ場所に入れる場合はこのパス指定のまま.
$imgsize_prog="imgsize.pl";
#
#  <画像リサイズ＆形式変換用ライブラリ>  
#
#  画像の自動リサイズ＆変換をする場合は、1にしてください。
#  (ImageMagickプログラムがサーバにインストールされている必要が
#   あります。新ＦＡＱ掲示板を、ご参照ください)
#  (1=画像リサイズ＆変換機能あり(推奨),0=なし)
$PM{'use_img_convert'} 		= 1;
#
#  (Unix/Linuxサーバの方)
#  通常は/usr/local/bin/convertか、/usr/bin/convertを指定してください。
#  ない場合は、Imagemagickがユーザに開放されているかどうか確認してください。、
#
$PM{'conv_prog_for_linux_server'}="/usr/bin/convert";	# UNIX/Linux上のWebサーバ用
#
#  (Windowsサーバの方)
#  imgboardの置いてあるフォルダの下にcomというフォルダを作り、
#  その中にImageMagickの実行プログラム一式を置いてください。
$PM{'conv_prog_for_win_server'}	="./com/convert.exe";		# Win32上のWebサーバ用
#
#  (外部設定ファイルによるカスタム版リサイズ設定の取り込み)(通常は設定不要)
#  より高画質にしたい、アニメに特化した設定にしたいなど、
#  カスタムした「リサイズ・ルール設定」を読み込みたい場合は、
#  以下でファイル名を指定してください。
#  なお、imgboard.cgi本体は再配布不可ですが、外部設定ファイルは再配布可能です
$PM{'set_make_snl_cgi_name'}	="./make_snl01.cgi";
#
#  <掲示板SPAM対策> 2006.03 new
#
#  掲示板SPAMによる自動書き込み
#  (0=制限しない,1=制限する（デフォルト）)
$limit_bbs_spam_flag=1;	
#
#  上で"1"にした場合、隠しキーワードを決めてください。
#  掲示板SPAMは英語圏が多いので、彼らの苦手な日本語が
#  良いでしょう。日本語の場合４文字までにしてください。
#  なお、特定の文字はエラーがでると思いますので、
#  うまく動かない場合は文字を変更してください。
#  カタカナはNGです。漢字推奨。いろいろ試して問題のない
#  文字列を探してください。
#
$spam_keyword="天安門事件号ぬ";	
#
#  禁止単語によるSPAM制限 (SPAM_WORD) 2006.05 New
#
#  URLリンクやメールアドレスがあり、かつ、特定の単語を本文に含む記事のみ
#  投稿を失敗させます。NGワード指定では、広告でない記事がSPAMとして
#  誤認識されて会話に不都合が出る場合、こちらを使ってみてください。
#
#  (1=制限する(推奨),0=制限しない)
$PM{'no_upload_by_spam_word'}=1;
#
# 注：先頭に#のある行は無効です。
#
@SPAM_WORD=(" 飢えた女性 ", " 女の子検索 ", " 超高収入 ", " ヤリ放題 "
," 割り切 ", " パラダイス ", " 副収入 ", "  ", " お小遣い "
," 完全無料 "," 推薦枠 "," 逆援 "," 女性会員 "," 好みの女性 "," 極秘情報 "
," 妊娠契約 "," 素人女性 "," 女性登録者 "," 登録無料 "
," 逆指名 "," サイトだよ "," 見放題 "
# アダルト系
#," セックス "," 無修正 "," 調教 "," セフレ ", " 人気ＡＶ "
#," 女性が "," エッチ "," ゲット "," ご指名 "," 援交 ", " ヤリマン "
#," 若い女性 "," 童貞 "," 出会い "," 人妻 "
#," 旦那 "," サイトだよ "," 援助 "," 若妻 "
#
# 海外
# アダルト系
#," fuck "," porn "
#," fetish "," pics "," adult "," teen "," stripper "
#
# ブランドコピーSPAM
," クス専売 "," ハイレプ "," copy33 "," brand188 "," スーパーコピー "
," louis "," vuitton "," taschen "," fossil  "," ローレックス "," ロレックス "
," cartier "," カルティエ "," 高級腕時計 "," S級  "," N級 "
#," check "," thank "," More "," free "
#," online "," site "," visit "
# 勧誘系
," links "," insurance "," cheap "," buy "
," Molto "," cheap "," Airfare "," Furniture "," Ashley "
," Casino "," Foxwoods "," Brighton "," Horseshoe "," Gambling "," Avalon "
," impresionado "," Sunglass "," Ringtones "," Loans "," Cingular "
," Insurance "," Lottery "," Highlander "," Cruise "
," merchand "," stock "," investment "," diabet "
# TODO
," [url= "," [URL= "
," viagra "," hydro "," store "," valium "
," generic "," drug "," prozac "," travel "," agency "
," medication "," Jewelry "," campaign "," advertisement "," Footwear "," C:\\ "
," mortgage "," gym "," mexico "," insurance "
," discount "," escort "," camsex "," livecam "
# Other SPAM
," yumenokuni.net "," pikavip "
," au-au-a.net "," candypop.jp "," yourfilejk.com "
," bagshop2008.com "," yahoo-sale.net "
# 熊本デリヘル系 by http://whois.domaintools.com/b-blooming.com
," bigbaito.com "," deli-rakuten.com "
," firstlips.com "," forfun.jp "," hey-sey.com "," nn7.biz "
# 精力剤系
," internut "," seiryokuzai "," khonsys "
," diet-live "," vigrx "," hirugouhan "," kanpo.com "
," 精力剤 "," 精力減退 "," 不感症 "," 媚薬 "," 激安サンプル "," 中国漢方 "
," 便宝 "," 催淫 "," バイアグラ "," シアリス "," ダイエットサプリ "
," 勃起不全 "," リドスプレー "
# Foreign spam word
," serwis "," miejsca "," serwery "," Internecie "
# 中国語含む広告をはじく
," 湜 "," 浯 "," 胛 "," 萵 "," 趺 "," 瑙  "," 濵  "," 小妹  "," 跪  "," 頌  "
," 模擬器 "," 軟件 "," 鰀 "," 肛裨 "," 褊 "," 舮 "," 竟 "," 碌  "," 轢  "
," 颪 "," 恷詰 "," 爾芦 "," 原奉 "," 滯 "," 圦 "," 錚 "
," 哈 "," 斌 "," 椶 "," 發 "," 秘塞 "," 蠅泙 "," 欄瓶 "
," 瑪 "," 鈔 "," 韲 "," 瑩 "," 糒 "," 黑 "," 淲 "
," 鴾 "," 汳 "," 沃 "," 鱚"," 穉 "," 諷"," 糂 "," 冢 "," 厠 "," 誾 "
# SPAMが多い国へのリンクを含む投稿をSPAMとする
# ロシア(ru) 中国(cn)  韓国(kr) 香港(hk) 台湾(tw)
# アルゼンチン(ar)、ブラジル(br)、イギリス(uk)
," .ru/ "," .cn/ "," .kr/ "," .fi/ "," .hk/ "," .tw/ "
," .ar/ "," .br/ "," .uk/ "
," 偽善者 "," 捏造 "," 無料配布中 "
," 素人娘 "," 大放出 ");
#
# IPアドレス指定型 SPAMフィルタ機能の追加について(2010.02 )
#
# ドメイン名を５０以上(中には１００以上）持つ業者も多いですが、
# リンク先の実IPアドレスは1、あるいは数個以下の固定IPである
# ケースがほとんどです。
# 従って、ドメイン名をリストに追加して一つ一つ排除する方法より、
# リンク先の固定IPを調べ、禁止リストに追加して、SPAMを排除した方が、
# 50～100倍効率が良いです。
# ドメイン名からIPアドレスを調べるには、ネットに接続した状態で、
#  MS-DOSコマンドラインで「ping ホスト名」を入力すれば、結果として表示されます。
# そのIPアドレスを@SPAM_HOSTS_IPに追記してください。
#
@SPAM_HOSTS_IP=("74.207.24?.","174.122.10?.","66.71.24?.","66.71.25?.","221.231.138.","18.243.22.64"
,"210.173.241.","209.160.32.22?","58.1.229.8?","202.172.28.15?"
,"69.64.147.","203.135.19?.?","198.143.162.");
#
# 2006.06 URLリンク列挙型SPAM対策
#
#  SPAMワードがひっかからなくても、本文中にURLリンクが4つ以上ある場合は
#  書き込めないようにします。
#  (1=制限する(推奨),0=制限しない)
$PM{'spam_url_link_limit_4'}=1;
#
# 2007.05 外国からのSPAM
#
#  英語のみの投稿は書き込めないようにします。
#  外国からのSPAM防止に有効です。
#  (1=制限する(推奨),0=制限しない)
$PM{'spam_limit_non_japanese'}=1;
#
# 2007.06 SPAMらしきメールアドレス
#
#  メール欄のメールアドレスがSPAMに多い国のドメインの場合
#  投稿を書き込めないようにします。
#  外国からのSPAM防止に有効です。
#  (1=制限する(推奨),0=制限しない)

$PM{'no_upload_by_spam_country_mail'}=1;
#
# SPAMが多い国へのメールアドレスを自称する投稿をSPAMと判定する
# ロシア(ru) 中国(cn)  韓国(kr) 香港(hk) 台湾(tw)
# アルゼンチン(ar)、ブラジル(br)、イギリス(uk)
@SPAM_MAIL_COUNTRY=(".ru",".cn",".kr",".fi"
,".hk",".tw",".ar",".br");
#
#  以上の様々な対策を指定しても効果がない場合、
#  まずは、投稿パスワード制にすることを考えてください。
#  SPAMはロボットで何万もの掲示板に自動投稿してきますので、
#  パスワードを類推されにくい位置に記述しておけば、解読される
#  可能性は低いです。
#
#  さらに、以上の様々な対策を指定しても効果がない場合、
#  あらゆるSPAMをとにかく問答無用で排除したい場合は
#  以下のフラグを使ってください。（通常は指定しないこと）
#
#  URLリンクやメールアドレスのある書き込みを、問答無用ですべて廃棄
#  (0=廃棄しない（デフォルト）,1=廃棄する)
$filter_bbs_spam=0;
#
#
#  <友達へ教えるURL>
#  「友達へ教える」で教えたいURLを書いてください
$PM{'cgi_url'}	="http://www.aaa.bbb.com/~myname/im.cgi";
#
# ============以下はオプションです。必要に応じてカスタマイズ================#
#
#
#================================#
#   <掲示板機能 基本オプション>    #
#================================#
#  ■<1ページに表示するメッセージ数>
#
#  デフォルト7
#  １ページに表示するメッセージの数です。imodeの場合は5以下を推奨
$PM{'message_per_page'} 		= 7;
#
#  ■ <携帯で表示する場合の、１記事あたりの最大文字数>
#
#  表示時の1記事の長さの最大表示文字数を設定してください。
#  携帯からアクセスした時に限り、この長さ以上の場合は、記事の後半が
#  表示上、カットされて表示されます。1ページ当たりの文字数がオーバしな
#  いように、上記パラメータと当パラメータで適宜調整してください)
#
$PM{'kiji_disp_limit_imode'}=600;
#
$PM{'kiji_disp_limit_foma'}=3000; # FOMA以降の場合
#
#  ■<返信機能>(R6 NEW)
#
#  返信機能を使うことができます。
#  (1=返信機能あり(推奨),0=返信機能なし)
$PM{'use_rep'} 		= 1;
#
# 返信でアップロードを許諾する場合は1にする(2005.04.16NEW)
$PM{'allow_res_upload'}=0;
#
#  古い記事に最近ついた返信を見過ごさないように、返信がついた記事の
#  スレッドを自動的に先頭へ持って行くことができるようになりました。
#  (1=先頭へ持って行く(デフォルト),0=持っていかない)
$PM{'res_go_up'} = 1;
#
#  古い記事に返信しても先頭へ持っていかないように
#  ユーザが投稿時に選ぶようにもできます。
#  （いわゆる「sage」機能です）
#  (1=sage有効(デフォルト),0=sage無効)
$PM{'use_sage'} = 1;
#
#  ■ <自動 全角カナ→半角カナ変換>
#
#  imode表示時の画面は狭く、少しでも多くの文字が表示された方がいい
#  という事情があるようなので、自動的に全角カナを半角カナに変換してか
#  ら表示する機能を追加しました。
#
#  1=変換してから表示(推奨),0=なにもしない
$PM{'hankaku_filter'}=1;
#
#  ■ <PCからの投稿を許可>
#
#  PCから、携帯アクセス経由で投稿する行為を許可するかどうかを設定してください。
#
#  1=許可しない(推奨),0=許可する(デモ用)
$PM{'no_upload_from_pc'}=0;
#
#  ■ <PCからの閲覧を許可> 2002.04 new
#
#  PCから、携帯アクセス経由で閲覧する行為を許可するかどうかを設定してください。
#
#  1=許可しない,0=許可する(推奨)
$PM{'no_view_from_pc'}=0;
#
#  ■ <フォーム入力項目のデータ有無チェック>
#
#  フォームの各入力項目の記入について、必須にするかどうかを指定できます。
#  必須にした入力項目が空の場合、記事は登録はされません。
#
#  1=必須,0=省略を許可
$PM{'form_check_name'}	=1;	# 名前 （デフォルト1）
$PM{'form_check_email'}	=0;	# email（デフォルト1）
$PM{'form_check_subject'}=0;	# 題名 （デフォルト0）
$PM{'form_check_body'}	=0;	# 本文 （デフォルト0）
#$PM{'form_check_img'}	=0;	# 添付画像（デフォルト0）
$PM{'form_check_rmkey'}		=0;	# 削除キー（デフォルト0）# ←現在未使用
#
#  以下は入力項目を増やす機能(imgboardでは6つまで入力項目を増やせます)を使っ
#  て、項目を増やした場合用 (増やしていないユーザは設定しても関係ありません)。
#
$PM{'form_check_optA'}	=0;	# 追加項目optA	（デフォルト0）# URL
$PM{'form_check_optB'}	=0;	# 追加項目optB	（デフォルト0）
$PM{'form_check_optC'}	=0;	# 追加項目optC	（デフォルト0）
$PM{'form_check_optD'}	=0;	# 追加項目optD	（デフォルト0）
$PM{'form_check_optE'}	=0;	# 追加項目optE	（デフォルト0）
$PM{'form_check_optF'}	=0;	# 追加項目optF	（デフォルト0）
#
#================================#
#   <掲示板機能 基本オプション>    #
#================================#
#
#  ■<転送許可画像サイズ上限>
#
#  Docomoの906i/706i以降機やSofbank3G機からJPEG画像,3GP動画等を
#  アップロードする場合の転送リミッタを設定してください。
#
#  なお、添付ファイルありの掲示板を運営する場合は、
#  記事数を減らしてサーバレンタルエリアが容量オーバにならないように
#  気を付けて下さい。
#  デフォルト6000ＫＢ
#
#  サイズ制限(画像以外)
#  デフォルト6000ＫＢ(80000KB以上にはしないこと。)
$PM{'max_upload_size'} 	= 6000;	# 単位KB TODO
#
#  ■<オリジナル画像縮小保存> RC7 new
# サムネイルだけでなく、オリジナル画像もダイエットした画像に
# 置き換えてしまう機能です。総蓄積容量が気になる場合や、
# 700万画素以上のデジカメ画像等で撮影したサイズの大きなJPEGの
# 投稿前リサイズが面倒な場合にお使いください。
#  (1=置き換え,0=置き換えない(標準))
#
# 以下の二つのパラメータはimgboard本体と合わせてください
#  大きな画像をDIETした縮小版へ置き換える(1=置き換え（推奨）,0=置き換えない)
$diet_org_img	=1;
# このサイズ以上のものをダイエットする(標準200KB)
# (100KB未満は指定できません)
$MICRO_DIET{'SIZE'}="200";
#
# アニメ専用圧縮アルゴリズム(2010 new)
# 実写写真でなく、アニメやイラスト、CG画像のみ
# 扱う掲示板の場合は、以下で1を指定してください。
# DIET時に、従来より圧縮率を抑え、ほぼ原画に近い
# 高品質で保存されます。
#
# なお、このモードでは通常時約２倍のCPU処理、作業メモリが必要です。
# Core2Duo 2GHz以上/メモリ1GB以上のサーバでのみご利用ください。
# AtomやPentiumMレベルのサーバでは無理です。
# また、変換待ち時間が倍になりますので、投稿時にwebブラウザの
# Timeoutエラーが発生しやすくなります。
# Timeoutになるケースが多い場合はapacheのhttp.confの
# Timeout値を増やして200以上にしてみてください
#
# (写真系で1にすると圧縮がほとんど効かなくなります。
# 絶対に指定しないでください)
#  (1=アニメ専用,0=それ以外(標準))
$MICRO_DIET{'ANIME_BBS_MODE'}="0";
#
#  ■<各種マルチメディアデータ（約２０種類）のアップロード>
#
#  デフォルトではGIF/JPEG/PNG/3GP動画/ezmovie/
#  /写メール(JPEG)/WMV/MP4/MP3/WMA/PDF
#  の投稿を受け付け、それ以外のデータは自動リジェクトします。
# その他の各種マルチメディアデータ
# （テキスト,HTML、iメロディなど事前登録された約２０種類）の投稿も
#  許可したい場合は以下のフラグを1にしてください。
#
#  (1=許可する,0=許可しない(推奨))
$PM{'allow_other_multimedia_data'}	= '0';	
#
#  ■ <自動URLリンク>
#
#  記事中にURL,メールアドレス等が含まれる場合、自動的にリンクにします。
#  (1=自動リンク(推奨),0=自動リンクしない)
$PM{'auto_url_link'}=1;
#
#
#
#  ■ <新着表示>
#
#  最新投稿3記事に------(new)------を添えて表示します
#  (1=表示する(デフォルト),0=表示しない)
$PM{'disp_new_notice'} = 1;
#
#  ■ <アクセスカウンタの設置>
#
#  簡易アクセスカウンタを左上に表示します
#  (1=テキスト表示,2=GIF表示,0=表示しない)
$PM{'use_count'} = 1;
#
# カウンタの桁数
$COUNTER_FIG{'total'}		=6; # 総計
$COUNTER_FIG{'today'}		=4; # 今日
$COUNTER_FIG{'yesterday'}	=4; # 昨日
#
# テキストカウンタの色
$PM{'counter_text_color'} = '#776655';
#
# 数字GIF画像(１文字分)の縦横サイズ
$PM{'counter_fig_width'} = ''; # 横
$PM{'counter_fig_height'} = ''; # 縦
#
# 同一IPチェック
#  (1=チェックする,0=チェックしない(デフォルト))
$PM{'counter_check_same_ip'} = 0;
#
#  IPアドレスやHOST名のパターンでカウントやアクセスログを
#  残さない人を指定することもできます。
#  (例: ppp-1234.koube.so-net.ne.jpの人は以下のようにする)
@NO_COUNT_DOMAIN=('kobe.so-net.ne.jp','192.168','127.0.0.1','localhost','','');
#
@DEF_NO_COUNT_DOMAIN=('myhost.jp','mycompany.jp','myschool.jp');
#
#==================================#
#     <セキュリティ オプション>    #
#==================================#
#
#  <会員パスワド>
#
#  いたずら投稿を防ぐため、投稿時に４けたの数字キーをチェックし、
#  それが正しい場合だけ登録するようにできます。
#
#  (1=使用,0=使用しない)
$PM{'use_post_password'}=0; #
#
# (注)imodeは相手を特定する手段がまったくないため、連続投稿イタズラに対し
# て対抗する手段がありません。画像付きの記事が流れてしまう事態を防ぐために
# 会員パスワドをできるだけ使用してください。
# 使用しない場合は
# １．保存記事数を数倍に増やす。
# ２．ＰＣからの投稿を禁止する
# 設定にしてください。
#
#  会員パスワド(4桁以上の数字)
$PM{'post_passwd'}="1234";
#
#  <閲覧者用パスワード>
#
#  閲覧時にパスワードをチェックし、正しい場合だけ閲覧できるするようにで
#  きます．掲示板の完全公開運営に不安がある場合は、この機能を用いて会員
#  制にすることができます．なお携帯の場合はクッキーがないので、毎回入力が
#  必要です。
#  (1=使用,0=使用しない)
$PM{'use_view_password'}=0;
#
#  閲覧会員パスワード
$PM{'view_passwd'}="1234";
#
#  <タグ使用許可>
#
#  コメント中にタグを許可するかどうかを指定できます。許可すればユーザ表現の
#  自由度は上がりますが、タグの閉め忘れ等によりトラブルが発生する可能性が
#  あります。なお、タグを許可する指定にしても、掲示板に対するイタズラ予防のため
#  ActiveX,Javascript等や、危険性のあるタグ、いたずらによく使われるタグ
# （約22種類）は自動フィルタされ、無効化されますので、あらかじめご了承くださ
#  い。（詳細はsub form_checkを参照）  
#  デフォルトはタグ使用可です。(1)
#
#  (1=使用可能,0=使用不可)
$PM{'use_html_tag_in_comment'}=1;
#
#  <IMGタグ許可・非許可>
#
#  imode版では設定する必要ありません
#  この設定値は変更しないでください。
#
#  (1=許可,0=非許可(強く推奨))
$PM{'use_img_tag_in_comment'}=0;
# 
#  <各種掲示板荒し対策> 
#
#  （レベル１）ホスト名による制限 (BLACK_LIST)
#
#  携帯のホスト名は動的に変わるため、当機能は外しました。
#
$PM{'no_upload_by_no_RH_user'}=0;	# imode 使用時は変更しないこと
#
#
#  レベル２）禁止単語による制限 (BLACK_WORD)
#
#  特定の単語を本文に含む記事の投稿を失敗させます。前述の手段を用いても"荒し"
#  や "宣伝広告の嵐" がどうしても収まらない場合、あるいは、ホスト名を頻繁に変
#  えるユーザからしつこいイタズラを受けている場合に、最終手段として使ってみて
#  ください。
#  (1=制限する,0=制限しない(推奨))
$PM{'no_upload_by_black_word'}=0;	
#
#   マッチした場合のエラーメッセージ（変更可）
#   （排除されたことが相手にわからないように、できるだけ、
#    無意味なものにしてください）
$PM{'error_message_to_black_word'}="CGI error code 2244 NBW";	
#
@BLACK_WORD=(" しねしね "," 死ね "," 制裁 "
," ユダヤ "," ごみ以下 "
," 雑魚 "," 童貞 "
," 女性登録者 "," 調教 "
," porn "," ウンコ "
," 偽善者 "," わらい "," 捏造 "," adult "," teen "," stripper "
," fetish "," pics "," peachs "
," 素人娘 "," ビデオを大放出 ");
#
#  <連続投稿回数制限> 
#
#  イタズラを防ぐために、同一ユーザからの連続投稿回数を掲示板側
#  で制限できます。
#  (1=制限する（デフォルト）,0=制限しない)
$PM{'limit_upload_times_flag'}=1;	
#  
#  上で"1"にした場合、どれだけのサンプリング期間の間に最大何回までアップ
#  ロード許可するかを決めてください。（オーバすると投稿エラーになります）
#
# サンプリング期間 (day,1hour,10min,2min,1minを選択可。デフォルトは2min)
$PM{'upload_limit_type'}="2min";	
# 回数。デフォルトは5回
$PM{'upload_limit_times'}="5";
#
#
#  <トリップ機能による、なりすまし防止> 2010.02new
#
# 名前#任意のワードでトリップを表示できるようにしてみました。
# 他人のなりすましで、掲示板が荒れる場合は、これをお使いください。
#
#  (1=トリップ機能を使えるようにする(推奨),0=しない)
$PM{'use_trip_flag'}=1;	
#
#  <自動バックアップ> 
#
#  定期的に記事を自動バックアップする機能がつきました。前回バックアップファ
#  イルを作成した日から間隔日以上空いて、新規登録があると、そのタイミングで
#  バックアップファイルを更新します。なお、バックアップは記事が５件以上ある
#  場合にのみ動作します。
#
#  自動定期バックアップを使用#
#  (1=使用する（デフォルト）,0=使用しない)
$PM{'make_backup_file'}	= '1';
#
#  バックアップする間隔(日)
$PM{'backup_day_interval'}  = '7';		
#
# バックアップファイル名(セキュリティ上の理由より、適宜変更して使うことを推奨)
$PM{'backup_file_name'} = 'fileback.dat';
#
#  <管理者自動メール> sendmail
#
#  新規記事が登録されると、下記メールアドレスにメールで通知します。
#  この機能を使用する場合は、以下の三つの情報をすべて確実に指定してください。
#  これらの情報を間違えると、サーバ管理者へ迷惑をかけるので、必ず管理者に確認
#  してから慎重に設定を行ってください。なお、この機能が使えるのはプロバイダ
#  がUNIX系のユーザのみです。(Mac,Win不可）設定がよくわからない場合は使用し
#  ないでください。
# 
$PM{'use_email'} =0;	# (1=yes,0=no)デフォルトは0
#
#  メールプログラムのパス（プロバイダの管理者に聞く）
#$PM{'mail_prog'} = '/usr/sbin/sendmail';
$PM{'mail_prog'} = '/usr/lib/sendmail';
#
#  管理者のメールアドレス（あなたのメールアドレス）
#  複数の宛先にメールを送りたい場合は新FAQページ参照
$PM{'recipient'} = 'yourname@your_provider.ne.jp';
#
#  メール本文に掲示板へのショートカットURLリンクを作るため、
#  CGIの入っているディレクトリのURLを指定して下さい(最後の/は不要)。
#
$PM{'cgi_link_url'}='http://yourprovider/yourname/imgboard';
#
#  メール本文の長さのリミッターを設定してください。
#
#  (この長さ以上の場合は、内容がカットされます)
$PM{'mail_body_limit'}=300;
#
#  メール通知除外
#  特定の条件が一致した場合、管理者へ通知メールを送らないようにします。
#  (携帯へ常時通知している人で、パケ代節約のために、自分の投稿等は通知を送り
#  たくない時に使ってください)
$use_no_email_sitei =0;	# (1=yes,0=no)デフォルトは0
#
# $use_no_email_siteiを1にした場合、通知を除外するe-mailアドレスを
# 指定してください。(メールアドレスの一部でも可)
@NOMAIL_LIST=('yourmail@docomo.co.jp','','');
#
#  普段はe-mail欄を記入せずに投稿している管理者の場合、PCの
#  IPアドレスやHOST名のパターンで除外者を指定することもできます。
#  (例: ppp-1234.koube.so-net.ne.jpの人は以下のようにする)
@NOMAIL_DOMAIN=('koube.so-net.ne.jp','','');
#
#  <ちょっとおやすみ> oyasumi
#
#  旅行に出かける等、しばらく掲示板をお休みしたい時は0にしてください。
#
$PM{'bbs_open'}=3;          #(1=yes,0=no)デフォルトは3
#
# 0=完全ダウン
# 1=管理メニューのみ動く
# 2=さらに閲覧が可能(ReadOnly)
# 3=さらに書込みも可能(読み書き可能=通常のモード)
#
#  おやすみ時のメッセージ(適宜変更)
#
$PM{'oyasumi_message'}=qq|
管理者旅行中のため、しばらくお休みします。<BR>
またのお越しをお待ちしております。
|;
#
#  <CPU負荷が常時100%になる高アクセスサイト対策> R7 NEW
#
#  一日数万から数十万アクセスあるサイトで、Perl言語を用いた
#  CGIによるCPU負荷が常時100%になってしまう人は、掲示板の
#  内容をキャッシュさせたHTMLファイル(index.html)を作り、
#  このindex.htmlを掲示板のURLとして周知することにより、
#  CGIよるCPU負荷上昇を抑えることができます。
#  なお、設定がややこしいこと、クッキーが効かなくなること等
#  のデメリットがありますので、普通の人は使わないでください。
#  (使い方詳細は新FAQページを参照)
#
$PM{'make_bbs_html_top'}=0;# (1=yes,0=no)デフォルトは0
#
# Yesにした場合は必ずPerlのパスを正しく設定してください。
$PM{'perl_prog_for_win_server'}="perl.exe";			# Win32上のWebサーバ用
$PM{'perl_prog_for_linux_server'}="/usr/bin/perl";	# UNIX 上のWebサーバ用
# 
#
# その他変数の初期化
$PM{'use_crypt'}	= '1';		# 暗号化を用いる
$PM{'read_config'}	= '1';		# configを読む
$PM{'flock'}		= '1';		# flockを使う
$PM{'auto_make_access_log_file'}=1;
$PM{'auto_make_count_file'}=1;
$use_ext_blacklist=0;			# 外部ブラックリストのロード
$PM{'auto_nicovideo_find'}=1;	# nicovideo
$allow_nicovideo_in_res=1;		# 返信にnicovideo
#
#
#=========================================#
#     <ＨＴＭＬ詳細設定項目オプション>    #
#=========================================#
#
#==========================#
# フォーム入力部のデザイン
#==========================#
#
#
# < 必要/省略可の自動表示のフォント色とサイズ >
#
#  "フォーム入力項目のデータ有無チェック"での設定に従い、
#  必要/省略可の自動表示を、フォーム欄の脇に自動表示することができます
#
$PM{'auto_disp_omit_frag'}	="1";		# 自動表示する(yes=1,no=0)
#
#
#  ◆ <ＨＴＭＬ抜粋>
#
#  ユーザサイドでＨＴＭＬを変更しやすいように,スクリプト中のＨＴＭＬ定義部分を
#  以下に抜き出し列挙してあります．それぞれ,print<<HTML_END;行の次の行から
#  HTML_END記号の前行までは、通常ＨＴＭＬとして編集可能なので,ワードパット(Win系)
#  Jedit(Mac系)等のエディタでご自由に書き換えて、カスタマイズしてください．た
#  だし先頭に$がついているもの($im_body_bgcolor等)は変数なので、消す場合は十分注
#  意してください．なお、当スクリプトはSJISコードを用いているため,「表示,申す,
#  機能」等の特定の文字が化けてしまう現象があります。この手の漢字や文字を使用
#  して文字化けが発生した場合は、文字化けした文字の前後に\マークを入れて区切っ
#  れば解決できます。この場合、\はPerlでは文字区切り記号として働き、Web上には
#  表示されません。
#
#=====================================#
#     <ＨＴＭＬ--画面最上部>          #
#=====================================#
#
#  ＨＴＭＬヘッダ,ボディ指定．タイトル等画面最上部のＨＴＭＬです
#
#  print<<HTML_END;の次行から"HTML_END"のある行までは、通常のＨＴＭＬ
#  として編集可能です． 
sub top_html{

 	# ■事前処理(変更しないこと)
	local($mes_p1)="";
	$PM{'title'}="" if(($keitai_flag eq "J-PHONE")&&($jstation_flag < 3));
	$mes_p1=qq|<BR>*PC、スマホの方は<a href="$PM{'cgi_hontai_name'}" target=_blank>こちら</a>へ<BR><BR>| if($keitai_flag eq "pc");
	$mes_p1="" if($FORM{'mode'} ne "");
	#カラーiモードの白飛びを防ぐ
	$PM{'im_body_text'}="#000000" if($PM{'im_body_text'} eq "");
	$PM{'im_body_bgcolor'}="#FFFFFF" if($PM{'im_body_bgcolor'} eq "");

# $ACCESS_COUNTER{total},$ACCESS_COUNTER{today},$ACCESS_COUNTER{yesterday} 
# が使用可能です。

 	# ■ＨＴＭＬ部(カスタマイズ可能)
print<<HTML_END;
<HTML>
<HEAD><TITLE>$PM{'title'}</TITLE>$top_html_header</HEAD>
<BODY BGCOLOR="$PM{'im_body_bgcolor'}" BACKGROUND="$PM{'body_background'}" TEXT="$PM{'im_body_text'}" LINK="#6060FF" VLINK="#4040FF">
画像Upload掲示板<BR>
<CENTER>
(2015携帯ｱｸｾｽ) <BR>
-$ACCESS_COUNTER{total}-<BR>
</CENTER>

$mes_p1

HTML_END
}
#
#=====================================#
#     <ＨＴＭＬ--画面最上部ボタン>    #
#=====================================#
#
#  画面最上部のボタンです
#
#  print<<HTML_END;の次行から"HTML_END"のある行までは、通常のＨＴＭＬ
#  として編集可能です． 
sub top_button_html{

 	# ■事前処理(変更しないこと)
	# 初期設定を変更してない場合、終了ボタンは出さない。
	if($PM{'back_url'} eq 'http://あなたのプロバイダ/あなたのディレクトリ/index.html'){
		$cm_out_exit_h='<!--';
		$cm_out_exit_f='-->';
	}

 	# ■ＨＴＭＬ部(カスタマイズ可能)
print<<HTML_END;
$cm_out_exit_h<a href="$PM{'back_url'}">HOME</a>$cm_out_exit_f
| <a href="$cgi_name?mode=disp_attach_confirm&page=$FORM{'page'}&viewpass=$FORM{'viewpass'}">投稿</a>
| <a href="$cgi_name?mode=search_menu&page=$FORM{'page'}&viewpass=$FORM{'viewpass'}">検索</a>
|<a href="$cgi_name?mode=disp_admin_menu&page=$FORM{'page'}&viewpass=$FORM{'viewpass'}">管理</a>|<BR>
 <a href="$cgi_name?mode=disp_up_help&page=$FORM{'page'}&viewpass=$FORM{'viewpass'}">*注意事項</a><BR>
HTML_END
}
#
#
#=====================================#
#     <ＨＴＭＬ--画面中央の説明>      #
#=====================================#
#
#  真ん中の説明部分のＨＴＭＬです．
#
sub middle_A_html{
# タグ使用上の注意が自動で入ります
print<<HTML_END;
$HR
HTML_END
}

sub middle_B_html{
print<<HTML_END;
<!--掲示板中央部の説明部分B-->
<center>
最大保存$PM{'max_message'}件<BR>
$KEITAI_ENV{'MACHINE_TYPE'}
</center>
HTML_END
}
#
#=====================================#
#     <ＨＴＭＬ--投稿記事部分>        #
#=====================================#
#
#
sub kiji_base_html{

    # ■事前処理(変更しないこと)
    local($mail_link)="";
    local($tel_link) ="";
    local($keitai_env_link) ="";
    local($tmp_parent_seq_no) ="";
    local($tmp_1,$tmp_2,$tmp_3) ="";
    local($tmpp_1,$tmpp_2,$tmpp_3) ="";
    local($rec_size1,$rec_size2) ="";
    local($resize_link) ="";
    local($tttmp_imgtitle) =""; # 2009.12 整理
    &make_url_link;

    #  日付データをimode用に短くする
    if($LDATA{'date'}=~ /\[(\d+)\/(\d+)\/(\d+)\,(\d+)\:(\d+)\:(\d+)\]/){
	$LDATA{'date'}="$2\/$3"."_"."$4\:$5";
    }

   #  メールアドレスがある場合だけリンクする

       if(($LDATA{'email'} eq "")||($LDATA{'email'}=~ /no_email/i)||($LDATA{'email'} eq "none")){
	   $mail_link="$LDATA{'name'}";
       }else{
	  # 匿名メール機能 2003.04
	  if($LDATA{'email'}=~ /^tkml\-/){
	   $mail_link="$LDATA{'name'}";
 	  }else{
	   $mail_link="<A HREF=\"mailto:$LDATA{'email'}\">$LDATA{'name'}</A>";
	  }
       }

	if($OPTDATA{'optKeitaiFlag'} ne ""){
#	  $OPTDATA{'optKeitaiServiceCompany'}
#	  $OPTDATA{'optKeitaiHttpVersion'}
#	  $OPTDATA{'optKeitaiMachineType'}
#	  $OPTDATA{'optKeitaiOtherParam'}
#	  $OPTDATA{'optKeitaiMelodyType'}

	  # 2003.12 vodafone対策
	   if($OPTDATA{'optKeitaiFlag'}=~ /J\-PHONE/i){
		$OPTDATA{'optKeitaiFlag'}="SoftBank";
	   }

	  # 2009.12 au対策
	   if($OPTDATA{'optKeitaiMachineType'}=~ /^au/i){
		$OPTDATA{'optKeitaiFlag'}="au";
	   }

	  $keitai_env_link=qq|<BR>$OPTDATA{'optKeitaiFlag'}：$OPTDATA{'optKeitaiMachineType'}|;
	}

	# ワード検索時の時はどれのレスかを説明する

	if(($FORM{'mode'} eq "search_menu")&&($LDATA{'blood_name'} ne "")){
		$tmp_parent_seq_no=$BLOOD2SEQNO{"$LDATA{'blood_name'}"};
		if($child_kiji_flag == '1' ){	# 子の場合
		  $disp_seq_no="$disp_seq_no".")(←$tmp_parent_seq_noへのレス";
		}else{
		  undef $tmp_parent_seq_no;
		}
	}

	# タイトル名の処理
    if($LDATA{'imgtitle'} !~ /^img(\d+)/){
		$tttmp_imgtitle="$LDATA{'imgtitle'}";
	}

	# リサイズ画像用の追加部分
	($tmp_1,$tmp_2,$tmp_3)		=split(/\-/,$best_fit_type);	
	($tmpp_1,$tmpp_2,$tmpp_3)	=split(/\-/,$second_fit_type);	
	if($IMG_PARAMETERS{'exist_snl_type'} ne ""){
		@SNL_TYPE=split(/\//,$IMG_PARAMETERS{'exist_snl_type'});
		foreach $snl_type(@SNL_TYPE){
		  if($snl_type =~ /$tmp_1\-$tmp_2\-/i){
			@SNL_STAT=split(/\-/,$snl_type);
		  }
		  if($snl_type =~ /$tmpp_1\-$tmpp_2\-/i){
			@SNL_STAT2=split(/\-/,$snl_type);
		  }
		}
		# サイズ表示を作る
		$tmp_3=$SNL_STAT[2];
		$tmpp_3=$SNL_STAT2[2];
		$tmp_3=int($tmp_3/100);
		$tmpp_3=int($tmpp_3/100);
		$tmp_3=($tmp_3/10);
		$rec_size1="$tmp_3";
		$tmpp_3=($tmpp_3/10);
		$rec_size2="$tmpp_3";
		$tmp_3="("."$tmp_3"."KB)";
		$tmpp_3="("."$tmpp_3"."KB)";

		# 2003.04.30修正
		$resize_link="<a href=$IMG_PARAMETERS{'snl_location'}$tmp_2.$tmp_1>ｻﾑ$tmp_3</a><BR>" if($best_fit_type ne "");
		# 2002.09.20 改善
		# モトモト扱えるもので、かつおすすめ縮小画像の方が
		# 大きくなってしまった場合は、変なので縮小画像を出さない。
		if(($can_handle_flag==1)&&($IMG_PARAMETERS{'size'} < $rec_size1)){
		  $resize_link="";
		}

		unless(($can_handle_flag==1)&&($IMG_PARAMETERS{'size'} < $rec_size2)){
		  $resize_link.="<a href=$IMG_PARAMETERS{'snl_location'}$tmpp_2.$tmpp_1>ｻﾑ$tmpp_3</a><BR>" if($second_fit_type ne "");
		}

	}


# ■ＨＴＭＬ部(カスタマイズ可能)
print<<HTML_END;
($disp_seq_no)$LDATA{'date'}<BR>
<FONT COLOR="#FF0000">$LDATA{'subject'}</FONT>
($mail_link$tel_link)$tmp_url_link $disp_re<BR>
<BR>
$LDATA{'body'}<BR>
<BR>
HTML_END

    # 添付データがあった場合はprint<<HTML_END;からHTML_ENDまでのHTMLが出力さ
    # れる 
    if($LDATA{'img_location'} ne ""){
 
	 # 扱える場合はリンク表示、扱えないものは非リンク表示
	 if($can_handle_flag == 1 ){	# 扱える場合はリンク表示

        #2009.12.07 iPod/iPhone/Androidでフルスクリーン化
	    if(($HTTP_USER_AGENT=~ /iPhone|iPod|iPad|android/i)&&($LDATA{'img_location'}=~ /jpe?g|gif|png|bmp$/i)){

print<<HTML_END;
<a href="$cgi_name?bbsaction=disp_fullscr&timg_location=$LDATA{'img_location'}&timg_w=$IMG_PARAMETERS{'width'}&timg_h=$IMG_PARAMETERS{'height'}&timg_dsize=$IMG_PARAMETERS{'dsize'}&timg_type=#$IMG_PARAMETERS{'type'}">$data_type $tttmp_imgtitle</A>
-$IMG_PARAMETERS{'dsize'}
<BR>
$resize_link
HTML_END

        #2009.12.07 iPod/iPhone/Androidでフルスクリーン化
	    }elsif(($au_3G_flag >= 1 )&&($LDATA{'img_location'}=~ /\.3g2$|\.swf$|\.mp4$|\.m4a$/i)){
#TODO
#	    }elsif(($au_3G_flag >= 0 )&&($LDATA{'img_location'}=~ /\.3g2$|\.swf$|\.mp4$|\.m4a$/i)){

&output_au_object_tag_html("$LDATA{'img_location'}","$tttmp_imgtitle");

# http://www.au.kddi.com/ezfactory/tec/spec/wap_tag5.html
# http://www.au.kddi.com/ezfactory/tec/dlcgi/download_1.html
#================================#
# au用のダウロードリンクをHTMLで出す 2010.01 new
#================================# 
sub output_au_object_tag_html{

	local($ttmp_au_img_location)	=$_[0];
	local($ttmp_au_imgtitle)	=$_[1];	

	local($ttmp_au_mime_type)		='video/3gpp2';	
	local($ttmp_au_copyright)		='no';	
	local($ttmp_au_disposition)		='devmpzz';	

	$ttmp_au_copyright='yes';	

	if($ttmp_au_img_location=~ /\.3gp?p$/i){
		$ttmp_au_mime_type		='video/3gpp';
	}elsif($ttmp_au_img_location=~ /\.3gp?p?2$/i){
		$ttmp_au_mime_type		='video/3gpp2';
#		$ttmp_au_mime_type		='audio/3gpp2';
	}elsif($ttmp_au_img_location=~ /\.m4a$/i){
		$ttmp_au_mime_type		='video/3gpp2';
#		$ttmp_au_mime_type		='audio/3gpp2';
	}elsif($ttmp_au_img_location=~ /\.swf$/i){
		$ttmp_au_mime_type	='application/x-shockwave-flash';
		$ttmp_au_disposition	='devfl8r';# 着アニメFlash
		$ttmp_au_disposition	='devfl7z';# Flash（それ以外）
	}elsif($ttmp_au_img_location=~ /\.mp4$/i){
		$ttmp_au_mime_type		='video/mp4';
	}else{
		return;
	}

	if(-e "$ttmp_au_img_location"){
		@AU_FILE_STAT=stat("$ttmp_au_img_location");
	    $ttmp_au_file_size=$AU_FILE_STAT[7];	# ファイルサイズを取得
	}else{
&error("auサイズ取得失敗 $ttmp_au_img_location");
		return;
	}
	
print<<HTML_END;
<object data="$ttmp_au_img_location" type="$ttmp_au_mime_type" copyright="$ttmp_au_copyright" standby="au専用DLリンク">
<param name="disposition" value="$ttmp_au_disposition" valuetype="data" />
<param name="size" value="$ttmp_au_file_size" valuetype="data" />
<param name="title" value="$ttmp_au_imgtitle" valuetype="data" />
</object>
HTML_END
}

print<<HTML_END;
<A HREF="$LDATA{'img_location'}">$data_type： $tttmp_imgtitle
</A>-$IMG_PARAMETERS{'dsize'}
<BR>
$resize_link
HTML_END

	  	}else{

print<<HTML_END;
<A HREF="$LDATA{'img_location'}">$data_type： $tttmp_imgtitle
</A>-$IMG_PARAMETERS{'dsize'}
<BR>
$resize_link
HTML_END

	  	}

	 }else{				# 扱えないものは非リンク表示

print<<HTML_END;
$data_type： $LDATA{'imgtitle'}-$IMG_PARAMETERS{'dsize'}
<BR>
$resize_link
HTML_END

	 }
    }

 print<<HTML_END;
$keitai_env_link
HTML_END
#print "$HR\n";
}
#
#  URLリンクが記入されていない場合はリンクを表示しないようにする
#
sub make_url_link{
 if($OPTDATA{'optA'} ne ""){
	$tmp_url_link=qq|(<a href="$OPTDATA{'optA'}" target=_blank>HP:</a>)<BR>|;
 }else{
	undef $tmp_url_link;
 }
}
#========================================================#
#     <ＨＴＭＬ--入力フォーム部(imode,Jフォン)>          #
#========================================================#
#
#  記事入力フォーム部のＨＴＭＬ．入力項目を増やしたり、減らしたりしたい
#  場合はここを変更してください。だだし、変更によりＣＧＩがうまく動かな
#  くなる可能性がありますので,ここは変更する時は十分注意してください．
#  なお、URL等の項目を追加したいなど、よくある希望に対しては、外部設定
#  ファイルというカスタマイズした設定ファイルを使うことにより、より容易
#  に実現できますので、自分でカスタマイズするよりも、それを使った方が楽
#  でしょう。なお、同ファイルはサポートサイトの方で配布しています。  
#
#
# imode用フォームメニュー
sub form_imode_html{

	# 事前処理
	&auto_omit_disp;
	$COOKIE{'body'} =~ s/<BR>/\n/g;		#<BR>をLFに
	$COOKIE{'optF'} =~ s/<BR>/\n/g;		#<BR>をLFに
	local($mes_p1) ="";
	local($mes_p2) ="";
	local($mes_p3) ="";
	local($mes_p4) ="";
	local($mes_p5) ="";
	local($mes_p9) ="";
	local($mes_p10) ="";
	local($cm_out_img_h) ="";
	local($cm_out_img_f) ="";
	local($back_page) ="1";

	# 2004.06.20 携帯で長いテキストを打つ人が増えてきたため、長くした
	local($textarea_maxlength) ="420";

	# TEXTAREAの投稿文字数を調整
	if($KEITAI_ENV{'OTHER_PARAM'} eq "FOMA"){
		$textarea_maxlength ="2500";
	# Softbank 2009.12 update	
	}elsif($jstation_flag >= 5){
		$textarea_maxlength ="2500";
	}elsif($keitai_flag eq "pc"){
		# PCからのいたずら対策
		$textarea_maxlength ="430";
	}else{
	  # 2004.06.20 add
	  if(($KEITAI_ENV{'CACHE_SIZE'} >= 20)||($ishot_flag == 1)){
		$textarea_maxlength ="2500";
	  # J-Phoneパケット機
	  }elsif(($KEITAI_ENV{'CACHE_SIZE'} >= 12)||($jstation_flag >= 3)){
		$textarea_maxlength ="900";
	  # au 3G機対策
	  }elsif(($KEITAI_ENV{'CACHE_SIZE'} >= 10)||($ishot_flag == 1)||($au_3G_flag >= 1)){
		$textarea_maxlength ="900";
	  }else{
	  }
	}

	# cookieに本文を保存したくない人のために、オプションを追加
	$COOKIE{'body'}="";
	

	# 親があるときは返信
	if($FORM{'parent'} ne ""){
		if($PM{'use_rep'} == 1 ){
		  $mes_p1 =qq| 記事NO. $FORM{'parent'}に返信します |;
		  $mes_p4 ="disp_rep_form";
		  $mes_p10 =qq|<INPUT TYPE="checkbox" NAME="sage" VALUE="1">sage<BR>|if($PM{'use_sage'} == 1);
		  $back_page ="$FORM{'page'}";
		}
	}

	# 画像アップロードの部品を出す

	# 親記事の場合と子記事にアップロードが許可された設定の場合は
	# アップロード用の部品を出す

	if(($FORM{'parent'} eq "")||($PM{'allow_res_upload'} eq "1")){
	 if($FORM{'up'} eq "file_tag"){

	  if($http_upload_ok_flag == 1){
		$mes_p2 =qq|↓画像添付<BR><INPUT TYPE="FILE" NAME="img" VALUE="">$DISP_OMIT{'img'}<BR><BR>|;
		$mes_p3 =qq|ENCTYPE="multipart/form-data"|;
	  }

	 }elsif($FORM{'up'} eq "text_only"){

	 }else{

	 }
	}

	if($PM{'use_post_password'} == 1 ){ 
		$mes_p9 ='disp_member_check';
	}else{
		$mes_p9 ='disp_attach_confirm';
	}

#以下の行から"HTML_END"のある行までは通常のＨＴＭＬとして編集可能です．

 if($FORM{'eURL'} eq ""){
print<<HTML_END;
$HR
<center>
[<a href="$cgi_name?mode=$mes_p9&page=$FORM{'page'}&up=$FORM{'up'}&blood=$FORM{'blood'}&parent=$FORM{'parent'}&sqid=$FORM{'sqid'}&eURL=$FORM{'eURL'}" accesskey=0>戻る</a>]
</center>
HTML_END
 }

print<<HTML_END;
$mes_p1

<FORM ACTION="$cgi_name" METHOD="POST" $mes_p3>	
<INPUT TYPE="HIDDEN" NAME="bbsaction" VALUE="post">
<INPUT TYPE="HIDDEN" NAME="page" VALUE="$back_page">
<INPUT TYPE="HIDDEN" NAME="up" VALUE="$FORM{'up'}">
<INPUT TYPE="hidden" NAME="blood" VALUE="$FORM{'blood'}">
<INPUT TYPE="hidden" NAME="parent" VALUE="$FORM{'parent'}">
<INPUT TYPE="HIDDEN" NAME="prebbsaction" VALUE="$mes_p4">
<INPUT TYPE="HIDDEN" NAME="viewmode" VALUE="$COOKIE{'viewmode'}">
<INPUT TYPE="HIDDEN" NAME="optB" VALUE="">
<INPUT TYPE="HIDDEN" NAME="optC" VALUE="">
$cm_out_pw_h
<INPUT TYPE="hidden" NAME="entrypass" SIZE=4 VALUE="$FORM{'entrypass'}">
$cm_out_pw_f
<INPUT TYPE="hidden" NAME="memberID" VALUE="$FORM{'memberID'}">
<INPUT TYPE="hidden" NAME="rmkey" VALUE="$FORM{'memberID'}">
$POSTADDP
*は必須入力<BR>
ﾈｰﾑ<BR>
<INPUT TYPE="TEXT" NAME="name" SIZE=15 VALUE="$COOKIE{'name'}" MAXLENGTH="22" istyle="1" MODE=hiragana>$DISP_OMIT{'name'}<BR>
ﾒｰﾙ<BR>
<INPUT TYPE="TEXT" NAME="email" VALUE="$COOKIE{'email'}" SIZE=15 MAXLENGTH="55" istyle="3" MODE=alphabet>$DISP_OMIT{'email'}<BR>

題名<BR>
<INPUT TYPE="TEXT" NAME="subject" VALUE="$COOKIE{'subject'}" SIZE=15 MAXLENGTH="22" istyle="1" MODE=hiragana>$DISP_OMIT{'subject'}<BR>


$mes_p2
$mes_p5
本文$DISP_OMIT{'body'}<BR>
<TEXTAREA NAME="body" COLS="14" ROWS="6" wrap="on" MAXLENGTH="$textarea_maxlength" istyle="1" MODE=hiragana>$COOKIE{'body'}</TEXTAREA>
(URLは自動的にﾘﾝｸされます)<BR>

$mes_p10
<INPUT TYPE="SUBMIT" VALUE="送信">
</FORM>

HTML_END
}
#
#=====================================================#
#     <ＨＴＭＬ--投稿時の注意>                        #
#=====================================================#
#
#  カスタムメニュー(自由定義)用のＨＴＭＬです．
#
sub output_up_help_HTML{
print<<HTML_END;
<html>
<head>
<title>携帯-注意</title>
$top_html_header
</head>
<body bgcolor="PINK">
★__注意＆ご説明<br>
<br>
※ 著作権上問題があるものはアップロード（埋込表\示\含む）、および、ダウンロードしないでください 。<br>
<br>
・掲示板のお題に合わせた画像の投稿をお願い致します。<br>
・無修正画像・児童ポルノ等、法律で禁止されている画像の貼\り付けは禁止です。<br> 
・またグロ画像など見る方に不快を与える画像も発見次第即刻削除します。<br>
・宣伝の書き込みも一切禁止です。<br>
</body>
</html>
HTML_END
}
#
#=====================================================#
#     <ＨＴＭＬ--USER定義01(お好きにカスタマイズ)>    #
#=====================================================#
#
#  カスタムメニュー(自由定義)用のＨＴＭＬです．
#
sub output_user_01_HTML{
print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(2015携帯ｱｸｾｽ)<BR>
<BR>
<a href="http://xn--t8j6c7d124mi51a.jp">お気に入り.jp</a><BR>
携帯おすすめリンク<BR>
by お気に入り.jp<BR>
</CENTER>
$HR
(検索)<BR>
<a href="http://www.google.co.jp/custom">Google</a><BR>
<a href="http://ff2ch.syoboi.jp/"> 2ch検索 </a><BR>
<a href="http://xn--t8j734jntm.com"> お天気.com </a><BR>
<a href="http://mobile.gnavi.co.jp/"> ぐるなび </a><BR>
<a href="http://ktai.st/~toriyama/muryou/"> 無料ｻｰﾋﾞｽ検索 </a><BR>
<a href="http://www2.kget.jp/mobconduct/"> 歌詞GET </a><BR>
<BR>
(移動・ｼﾞｵﾒﾃﾞｨｱ)<BR>
<a href="http://www.jorudan.co.jp/jm/"> 乗換案内 </a><BR>
<a href="http://m.tabelog.com/"> 食べログ </a><BR>
<a href="http://www.16t.jp/m//"> 携帯版 ﾀｸｼｰｺｰﾙ </a><BR>

<BR>
(株式・買い物)<BR>
<a href="http://amazon.jp"> amazonﾓﾊﾞｲﾙ </a><BR>
<a href="http://www.japannetbank.co.jp/service/mobile/index.html"> JNB銀行 </a><BR>
<a href="http://m.kakaku.com/pc/"> 価格.com PC </a><BR>
<a href="http://xn--1sq65hw3win8a.com"> 買取価格.com(各オク価格比較も)  </a><BR>

(ｺﾐｭﾆﾃｨ)<BR>
<a href="http://m.mixi.jp/"> mixiﾓﾊﾞｲﾙ </a><BR>
<a href="http://m.youtube.jp/"> YouTube ﾓﾊﾞｲﾙ </a><BR>
<a href="http://m.cookpad.com"> ｸｯｸﾊﾟｯﾄﾞ </a><BR>
<a href="http://yahoo-mbga.jp/"> Yahoo!ﾓﾊﾞｹﾞｰ </a><BR>
<a href="http://m.gree.jp/"> GREE </a><BR>
<a href="http://hangame.jp/"> ﾊﾝｹﾞｰﾑ </a><BR>
<a href="http://ip.tosp.co.jp/"> 魔\法\ i </a><BR>
<a href="http://m.ameba.jp/"> アメーバ </a><BR>
<a href="http://blog.fc2.com/"> FC2ブログ </a><BR>
<a href="http://m.nicovideo.jp"> ニコ動モバイル </a><BR>
<a href="http://pr.cgiboy.com/"> 前略プロフ </a><BR>
<a href="http://peps.jp/"> @ peps!(ホムペ) </a><BR>
<BR>
(他)<BR>
<a href="http://c.2ch.net/"> 2ch </a><BR>
<a href="http://iaozora.net"> 携帯 青空文庫 </a><BR>
<a href="http://www.cmoa.jp"> コミックi/シーモア </a><BR>
<a href="http://mechacomi.jp"> めちゃコミ </a><BR>
<a href="http://www.k-manga.jp"> ケータイまんが王国 </a><BR>
<a href="http://erogamescape.com"> おかずに使えるエロゲー批評空間 </a><BR>
<a href="http://cinema.intercritique.com/"> CinemaScape－映画批評空間－ </a><BR>
<a href="http://www.sjk.co.jp"> 通勤ブラウザ </a><BR>
<BR>
$HR
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR

このページはリンクフリーです<BR>
</BODY>
</HTML>
HTML_END
}
#
#=====================================================#
#     <ＨＴＭＬ--USER定義02(お好きにカスタマイズ)>    #
#=====================================================#
#
#  カスタムメニュー(自由定義)用のＨＴＭＬです．
#
sub output_user_02_HTML{
print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(携帯ｱｸｾｽ)<BR>
<BR>
</CENTER>

ご自由にカスタマイズして、お使いください<BR>
*ユーザ定義デモ<BR>

$HR
<BR>

$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR
</BODY>
</HTML>
HTML_END
}
#
#=====================================================#
#     <ＨＴＭＬ--USER定義03(お好きにカスタマイズ)>    #
#=====================================================#
#
#  カスタムメニュー(自由定義)用のＨＴＭＬです．
#
sub output_user_03_HTML{

    	local($mail_link_pc)="";
 
	if($PM{'cgi_url'} eq "http://www.aaa.bbb.com/~myname/im.cgi"){
	   # デフォルトのままなら、自動作成して補完する
	   $PM{'cgi_url'}="http\:\/\/"."$ENV{'SERVER_NAME'}"."$ENV{'SCRIPT_NAME'}";
	}

	if($keitai_flag eq "J-PHONE"){
		$mail_link_pc="<a href=\"mailto:-\@-\" mailbody=\"$PM{'cgi_url'}\">メール</A><BR><BR>";
	# PC
	}else{
		$mail_link_pc="<A HREF=\"mailto:-\@-?subject=掲示板のｱﾄﾞﾚｽ&body=$PM{'cgi_url'}\">メール</A><BR><BR>";
	}

print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(携帯ｱｸｾｽ)<BR>
<BR>
友達に教える<BR>
</CENTER>
<BR>
板のｱﾄﾞﾚｽを周知<BR>
<BR>
$mail_link_pc

$HR
<BR>

$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR
</BODY>
</HTML>
HTML_END
}
#
#================================#
#     <ＨＴＭＬ--下部>           #
#================================#
#
#  フリーのCGIサイト等で掲示板下部へのテキスト広告を義務付けられている
#  場合は、ここにHTMLソースを書いてください。挿入ポイントは削除ボタン
#  の直上になります。(最初のお名前オークションの広告は消さないこと)
#  ページをめくっていくと広告が1,2,3・・順に出ます。なくなると先頭の
#  広告に戻りグルグル回ります。増やしても大丈夫ですので、広告の数を
#  増やしたい人は4,5.6とバナーを御自由に作って下さい。(ただし一つ目は
#  変更不可)
#
# バナー１つ目（変更不可）
$B_BANNER{'1'}=qq|
<A HREF="http://www.big.or.jp/~talk/welcome/welcome_imr7.cgi">[(広)画像リサイズ・ガラ携.写メUp対応imgboard2015]</a>
|;

# バナー２つ目（変更不可）
$B_BANNER{'2'}=qq|
<A HREF="http://www.big.or.jp/~talk/welcome/welcome_kabu.cgi">[(広)特報-ﾘﾊﾞｳﾝﾄﾞ株！]</a>
|;
#
# バナー３つ目（自由に変更して結構です）
$B_BANNER{'3'}=qq|
|;
#
# バナー４つ目（自由に変更して結構です）
$B_BANNER{'4'}=qq|
|;
#
#
#===================================================#
#     <ＨＴＭＬ--画像フルスクリーン表示部>          #
#===================================================#
# 2008.06 new
#
#  iPod Touch/iPhone/Androidで画像だけを呼び出した場合、小さく表示されてしまうため、
#  フルスクリーンに出すために新設したＨＴＭＬ部です。
#
#  print<<HTML_END;の次行から"HTML_END"のある行までは、通常のＨＴＭＬ
#  として編集可能です． 
sub disp_fullscreen_html{

    local($tmp_title)=$_[0];# 引数1として取得
	local($tmpp_img_location)=$_[1];# 引数2として取得
	    local($tmpp_img_width)=$_[2];# 引数3として取得
		local($tmpp_img_height)=$_[3];# 引数4として取得
		local($tmpp_img_dsize)=$_[4];# 引数4として取得
		local($tmpp_img_type)=$_[5];# 引数4として取得

		    print<<HTML_END;
<HTML lang="ja">
<HEAD><TITLE>imgboard iPod/iPad/iPhone/Android AUTOFIT</TITLE>
$top_html_header
</HEAD>
<BODY BGCOLOR="#444444" TEXT="$PM{'body_text'}">
<img src="$tmpp_img_location"  width="100%" hspace=0 vspace=0 border="1" alt="$tmp_title" bordercolor="#FFFFFF"  align="left">
<BR CLEAR="LEFT">
<FORM>
<INPUT TYPE="button" VALUE=" もどる " onClick="history.back()">
$tmpp_img_type - w $tmpp_img_width/h $tmpp_img_height - $tmpp_img_dsize

</FORM>
<NOSCRIPT>
<a href="$ENV{'HTTP_REFERER'}">もどる</a>
</NOSCRIPT>



</BODY>
</HTML>
HTML_END
}
#
#
#------------ＨＴＭＬ抜粋ここまで------------#
# cfg_end



    #=================================================================#
    #     以上でユーザカスタマイズ部分である初期設定は終わりです.     #
    #     以下はプログラムになります．                                #
    #=================================================================#




#=======================================================================#
# メインルーチン
#=======================================================================#

&read_config;				# 記事から設定をロード

&init_valiables;			# 初期化

&check_open;				# 開店確認

&check_browser_type;			# ブラウザチェック

&read_input;				# フォームの内容とクッキーを読み込む

if($FORM{'bbsaction'} eq 'post'){		# モードが投稿モードの場合

	&check_entrypass;			# 会員チェック
	&protect_from_BBS_cracker;		# 荒し対策	
	&read_cookie;				# クッキーを読込む	
	&limit_upload_times;			# 連続投稿回数チェック	
	&make_memberID;				# rmkey&iクッキー番作成	
	&post_data;				# 投稿処理	
	&set_cookies;				# クッキーをセット
	&count_bbs if($PM{'use_count'} == 1);	# アクセスカウンタ(ReadOnly)
	&send_mail;				# 管理者へメール	

	&make_top_html_for_high_load_svr ;	# 超高負荷サーバ用にHTMLファイルを出す

		# パラメータクリア用ＨＴＭＬ
		&jump_html(" 登録完了 <BR>");
		exit;					# 終了

# 2008.06.26 for ipod/iPad/iPhone/android
}elsif($FORM{'bbsaction'} eq 'disp_fullscr'){# フルスクリーン表示の場合
	&output_Content_type;
    &disp_fullscreen_html($FORM{'timg_location'},$FORM{'timg_location'},$FORM{'timg_w'},$FORM{'timg_h'},$FORM{'timg_dsize'},$FORM{'timg_type'});
	exit;

}elsif($FORM{'bbsaction'} eq 'remove'){	# モードが削除モードの場合

	if($PM{'admin_passwd'} eq "4689"){
	  &error(" エラー。管理パスワードがデフォルトのままであり、未設定です。セキュリティ対策のため、削除\機\能\は利用できません。管理パスワードを変更してください。 ");
	}

	if($FORM{'passwd'} eq $PM{'admin_passwd'}){
		$remove_mode="admin";		# 削除モード
		&remove_data;			# 削除処理
		&make_top_html_for_high_load_svr ;# 超高負荷サーバ用にHTMLファイルを出す
		&jump_html(" 削除完了 ");	# パラメータクリア用ＨＴＭＬ
		exit;				# 終了
	}elsif(($FORM{'passwd'} eq $PM{'guest_passwd'})&&($PM{'use_guest_passwd'} ==1)){
	        &error(" エラー。ゲストパスワード機能はiモードからは利用できません ");
		$remove_mode="guest";		# 削除モード
		&remove_data;			# 削除処理
		&make_top_html_for_high_load_svr ;# 超高負荷サーバ用にHTMLファイルを出す
		&jump_html(" 削除完了 ");	# パラメータクリア用ＨＴＭＬ
		exit;				# 終了
	}elsif($PM{'use_guest_passwd'} =='-1'){
		$remove_mode="rmkey";		# 削除モード
		&remove_data;			# 削除処理
		&make_top_html_for_high_load_svr ;# 超高負荷サーバ用にHTMLファイルを出す
                &jump_html(" 削除完了 ");       # パラメータクリア用ＨＴＭＬ
		exit;				# 終了
	}else{
		&error("パスワードが違います．削除を中止しました．");
	}


}elsif($FORM{'bbsaction'} eq 'pf_change'){# モードがプロファイル変更の場合
	&set_cookies;				# クッキーをセット
        &jump_html(" 変更完了 ");       	# パラメータクリア用ＨＴＭＬ
	exit;					# 終了

}elsif($FORM{'bbsaction'} eq 'page_change'){# モードがページ変更の場合
	&read_cookie;				# クッキーを読込む

}elsif($FORM{'bbsaction'} eq 'disp_form_only'){# フォームウィンド表示の場合
	&output_Content_type; 
	&top_html;
	&top_button_html;
	&output_form_html;			# 入力フォームを表示
	print "</BODY></HTML>\n";
	exit;
}

# アクションが何も指定されていない時は、表示となる
# 各モードにより表示画面の種類を分岐させて表示させる

  if($FORM{'mode'} eq "disp_admin_menu"){
	# 管理メニュー表示
	&output_Content_type;
	&output_admin_menu_HTML;
	exit;
  }elsif($FORM{'mode'} eq "disp_input_menu"){
	# 入力フォームを表示
	&protect_from_BBS_cracker if($PM{'no_disp_for_cracker'}==1);	# 荒し対策
	&read_cookie;				# クッキーを読込む
	&check_entrypass;			# 会員チェック
	&output_Content_type; 
	&top_html;
	&output_form_html;	# 入力フォームを表示
	print "</BODY></HTML>\n";
	exit;
# R7new
  }elsif($FORM{'mode'} eq "disp_attach_confirm"){
	# 画像を投稿するか確認する。
	&check_upload_from_pc; 
	&output_Content_type;
	&top_html;


	if(($http_upload_ok_flag == 1)||($file_attach_mail == 1)){
		&output_attach_confirm_HTML;
	}elsif($ishot_flag > 0){
		&output_attach_confirm_HTML;
	}else{

	  if($PM{'use_post_password'} == 1 ){ 
		$FORM{'mode'}="disp_member_check";# modeを上書き
		&output_member_check_HTML;
	  }else{
		&output_attach_confirm_HTML;
	  }

	}
	exit;
  }elsif($FORM{'mode'} eq "disp_member_check"){
	# メンバー確認フォームを表示
	&check_upload_from_pc; 
	&output_Content_type;
	&top_html;
	&output_member_check_HTML;
	exit;
  }elsif($FORM{'mode'} eq "search_menu"){
	# ワード検索メニュー表示
	&output_Content_type;

	# 2010.04 ワード検索メニューにおける、
	# クロスサイトスクリプティング対策を追加
	&form_check;

	&output_search_menu_HTML;
	if($FORM{'SearchWords'} ne ""){
	    &protect_from_BBS_cracker if($PM{'no_disp_for_cracker'}==1);# 荒し対策
	    &protect_from_NON_member if($PM{'use_view_password'}==1);	# 会員限定
    	    &output_html;			# 掲示板を表示
 	    exit;				# 終了
	}else{
		print " 検索ワードが何もありませんでした．入力してください \n";
	}
	&output_search_menu_HTML2;
	exit;
  }elsif($FORM{'mode'} eq "show_howto"){
	# 使い方を表示
	&output_Content_type;
	&output_show_howto_HTML;
	exit;
  }elsif($FORM{'mode'} eq "whats_imgboard"){
	# imgboardとは
	&output_Content_type;
	&output_whats_imgboard_HTML;
	exit;
  # 2007.06 追加
  }elsif($FORM{'mode'} eq "disp_up_help"){
	# uploadの説明
	&output_Content_type;
	&output_up_help_HTML;
	exit;
  }elsif($FORM{'mode'} eq "user_01"){
	# 自由定義（お好きにカスタマイズしてください）
	&output_Content_type;
	&output_user_01_HTML;
	exit;
  }elsif($FORM{'mode'} eq "user_02"){
	# 自由定義（お好きにカスタマイズしてください）
	&output_Content_type;
	&output_user_02_HTML;
	exit;
  }elsif($FORM{'mode'} eq "user_03"){
	# 自由定義（お好きにカスタマイズしてください）
	&output_Content_type;
	&output_user_03_HTML;
	exit;
  }elsif($FORM{'mode'} eq "check_env"){
	# 環境チェック（デバック用）
	&output_Content_type;
	&output_check_env_HTML;
	exit;
  }else{
    # モードが指定されてない場合,掲示板を表示
    &protect_from_BBS_cracker if($PM{'no_disp_for_cracker'}==1);	# 荒し対策
    &protect_from_NON_member if($PM{'use_view_password'}==1);		# 会員限定
    &count_bbs if($PM{'use_count'} == 1);				# アクセスカウンタ
    &output_Content_type; 
    &top_html;
    &top_button_html;
    &output_html;						# 掲示板を表示
    exit;
  }

#=======================================================================#
# サブルーチン
#=======================================================================#

#======================#
# Content-typeの出力
#======================#
sub output_Content_type{

    if($au_3G_flag >= 1){
    # 2004.06.13 add au3G対策
	print "Cache-Control: no-cache\n";
	print "Content-type: text/html\n\n";
    # 2008.06.20 iPod/iPad/iPhone/android対策 update 2009.12.07
    }elsif($HTTP_USER_AGENT=~ /iPhone|iPod|iPad|safari/i){
	print "Content-type: text/html; charset=Shift_JIS\n\n";
    }else{
	print "Content-type: text/html\n\n";
    }

}
#
#================#
# 初期化
#================#

sub init_valiables{

	# 記事データフォーマット
	@IM122R6DATA=('subject','name','email','date','body','img_location','imgtitle','seq_no','blood_name','rmkey','unq_id','permit','other');
	$ext_config_ver		="100";
	$real_page_num		="1";	# 真のページ数
	$HTTP_USER_AGENT	=$ENV{'HTTP_USER_AGENT'};
	$HTTP_REFERER		=$ENV{'HTTP_REFERER'};
	$HTTP_REFERER 		=~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
	$form_method		="POST";
	$HR=qq|<HR>|;

	$REMOTE_HOST		=$ENV{'REMOTE_HOST'};
	$SERVER_NAME		=$ENV{'SERVER_NAME'};


	if(int($])<5){
		&error(" 設定エラー。スクリプトの１行目でパスの指定されたPerlのバージョン $] は古すぎます。imgboard1.22R5以降ではjcode.plのバージョンの関係でPerl5以上が必要です。Perl5以上のパスを探してそれに変更するか、Perl4でも動くR5\(for Perl4\)とjcode_sj.plを特報倶楽部にて入手してください。 ");
	}

	&check_RH;		# Apache1.3.x対策
	&check_ISP;		# プロバイダをチェックして、アドバイスを出す

	$cgi_name=&get_script_name;
	#========= 以下はマイナーオプションです ( 0=no,1=yes)==========#

	# ゲストパスワード機能
	# 投稿者自身が記事を削除できる機能です。詳細はサポート掲示板を参照してください。
	#
	$PM{'use_guest_passwd'}	=0;		# ゲストパスワード機能を使用
	# （-1.削除キー方式にする,0.使用しない、1.ゲストパスワードを使用する）
	$PM{'guest_passwd'}		='guest';	# 記事削除時の ゲストパスワード(変更してください)
	# IPが完全一致しなくても、同じサブネットからのアクセスは同一人物とみなし、削除を許可する
	$PM{'gp_allow_subnet'}	=1;

	# その他
	$PM{'no_disp_RH_in_HTML_sorce'}=1;	# HTMLソースにリモホを表示しない
	$PM{'no_disp_for_cracker'}=0;		# BlackList者に掲示板を見せない
	$PM{'force_www_server_os_to'}='';		# 未使用パラメータ(指定しないこと) 

	# スパムリスト関連の追加設定
	$use_ext_spamlist	=1;	# 外部にspamlist.cgi,spamword.cgi
					# があれば,そのリストをロードする

	# 外部の設定ファイルのロード(1.21以降)
	# カスタマイズしたHTMLや設定したパラメータ等を外部からロードします。
	# バージョンアップによる引越しやカスタマイズが楽になります。

	# なお、開発元でimgboardの設定やHTMLをアレンジをした外部設定ファイルの
	# 配布も予定していますので、改造が面倒・・・という方は、これをご利用して
	# 頂くと手間が省けて良いでしょう。
	# （一例）メルアド省略＆URL項目付きVerに変更できる外部設定ファイル

	$PM{'load_ext_config'}	=0;		# 外部設定ファイルを使う(1=yes,0=no)
	$PM{'ext_config_name'}	="set_imode01.cgi";	# 設定ファイル名（拡張子は必ず cgiに）

	# 外部設定ファイルに書いたパラメータは上書きされます。

#------------------ 以下はプログラム -----------------------------	
	undef $call_from_imgboard_flag;
	$call_from_imgboard_flag=1;

	require "$imgsize_prog" if(-e "$imgsize_prog");

	if(($PM{'load_ext_config'} == 1)&&(-e "$PM{'ext_config_name'}")){
		require "$PM{'ext_config_name'}";
	}

&make_uniq_onetime_id;

#======================#
# SPAM対策用トークン
#======================#
# 2010.02 SPAM対策のため、掲示板固有のワンタイムIDを作成する
# 2010.04 softbankのスマートフォン対策でロジック変更

sub make_uniq_onetime_id{

	local($ttmp_uniq_char)="";

	# perl5のDigestやSHA256モジュールを使えないプロバイダが多いので、
	# 独自ロジックで類推しにくいHASH代用計算をすることにした

	if(-e "$PM{'img_dir'}/index.html"){ # 2010.04imgboard本体と変数名が違うのを修正
	 @UNQ_FILE_STAT=stat("$PM{'img_dir'}/index.html");	# 属性を調査
	 $tttmp_pick_file_size		=substr($UNQ_FILE_STAT[7],-1,1);		# ファイルサイズを取得
	 $tttmp_pick_file_lastupdate=substr($UNQ_FILE_STAT[9],-1,1);
	}else{
	 $tttmp_pick_file_size		=3;
	 $tttmp_pick_file_lastupdate=4;
	}

#	$tmp_token_time= substr(time,-7,2); #27.7時間単位

	$tmp_alphabet_sn	="$ENV{'SCRIPT_FILENAME'}";
	$tmp_alphabet_sn	=~ s/[^a-zA-Z0-9]//g;

	$tmp_alphabet_sa	="$ENV{'SERVER_ADMIN'}";
	$tmp_alphabet_sa	=~ s/[^a-zA-Z0-9]//g;

	$tmp_alphabet_saddr ="$ENV{'SERVER_ADDR'}";
	$tmp_alphabet_saddr =~ s/[^a-zA-Z0-9]//g;
	
#$PM{'admin_passwd'} = 'biko';	

	# HTC disire対策でロジック変更(１文字長くしておく)
	$tttmp_pick_sa_base=substr($tmp_alphabet_saddr,-3,1)."$tttmp_pick_file_lastupdate".substr($PM{'admin_passwd'},-1,1).substr($tmp_alphabet_sn,5,1).substr($tmp_alphabet_sa,5,1)."$tttmp_pick_file_lastupdate"."$tttmp_pick_file_lastupdate";

#&error("$tttmp_pick_sa_base  $tmp_alphabet_sn $tmp_alphabet_sa s $tttmp_pick_file_lastupdate");

#	$tttmp_salt="$tttmp_pick_file_lastupdate"."$PM{'admin_passwd'}";# salt作成
	$tttmp_pick_sa		=  substr(time,-7,2)."$tttmp_pick_sa_base";
	$tttmp_pick_sa_old	= (substr(time,-7,2)-1)."$tttmp_pick_sa_base";

# token動作確認test用(100秒単位で無効に)
#	$tttmp_pick_sa		=  substr(time,-4,2)."$tttmp_pick_sa_base";
#	$tttmp_pick_sa_old	= (substr(time,-4,2)-1)."$tttmp_pick_sa_base";

	
	$uniq_token		=crypt($tttmp_pick_sa		,$tttmp_pick_sa);# HASH代用文字列作成
	$uniq_token_old	=crypt($tttmp_pick_sa_old	,$tttmp_pick_sa_old);# HASH代用文字列作成 27.7時間単位で一つ前

	# saltを切り落とす
	$uniq_token		=substr($uniq_token,2,11);
	$uniq_token_old	=substr($uniq_token_old,2,11);

	# URLと相性の悪い文字を除く
	$uniq_token 	=~ s/[^a-zA-Z0-9]//g;
	$uniq_token_old =~ s/[^a-zA-Z0-9]//g;

#&error("$uniq_token");	
#	$ttmp_uniq_char="$uniq_token - $uniq_token_old - salt $tttmp_salt - sa $tttmp_pick_sa - saold $tttmp_pick_sa_old - $ENV{'SERVER_ADDR'} "."$ENV{'REMOTE_ADDR'} "."$ENV{'SERVER_ADMIN'}"." $tttmp_pick_file_size $tttmp_pick_file_lastupdate";
#&error("ttmp_uniq_char $ttmp_uniq_char");

}


	# 2006.03 SPAM対策
	if($spam_keyword ne ""){
		$POSTADDP="$POSTADDP\n"."<INPUT TYPE=\"HIDDEN\" NAME=\"sf\" VALUE=\"$spam_keyword\">\n<INPUT TYPE=\"HIDDEN\" NAME=\"onetime_token\" VALUE=\"$uniq_token\">";
	}

	if(($PM{'jcode_name'} ne '')&&(-e "$PM{'jcode_name'}")){
	    require "$PM{'jcode_name'}";
	}else{
	    &error(" CGI設定エラー検出！<BR>日本語ライブラリ$PM{'jcode_name'}が指定された場所に見つかりませんでした。パスの設定を見直して下さい ");
	}

	# 2003.11 コマンドラインの引数を取得する
	$total_agvg=@ARGV;
	if(@ARGV > 0){
	  for($i=1;$i<=$total_agvg;$i++){
		$ARGV{$i}=shift @ARGV;
	  }
	}

	# OSの種別を判別(同時にパラメータの自動切換えもする)
	$www_server_os =&check_www_server_os;
	if($www_server_os=~ /win/i){
		$MYCGI_ENV{'WIN_SERVER'}=1;

		  $PM{'cgi_hontai_name'}	= 'imgboard2015.cgi';	# imgboard本体の名前
		
		# Perlのパスを自動選択
		$PM{'perl_prog'}="$PM{'perl_prog_for_win_server'}";
		# sendmailのパスを自動選択
		$mail_prog   = "$PM{'mail_prog_for_win_server'}";
		# imagemagickのパスを自動選択
		$PM{'conv_prog'}="$PM{'conv_prog_for_win_server'}";

	}else{
		$MYCGI_ENV{'WIN_SERVER'}=0;

		# Perlのパスを自動選択
		$PM{'perl_prog'}="$PM{'perl_prog_for_linux_server'}";
		$mail_prog   = "$PM{'mail_prog_for_linux_server'}";
		# imagemagickのパスを自動選択
		$PM{'conv_prog'}="$PM{'conv_prog_for_linux_server'}";

		if(-e '/usr/bin/convert'){
			# 最近のパス
 		  	$PM{'conv_prog'}='/usr/bin/convert';
 		}elsif(-e '/usr/local/bin/convert'){
			# 古いパス
			$PM{'conv_prog'}='/usr/local/bin/convert';
		}
		
	}

		if($PM{'make_bbs_html_top'}==1){
		 if(($ENV{'HTTP_REFERER'}=~ /2ch/i)||($ENV{'HTTP_REFERER'}=~ /read\.cgi/i)||($ENV{'HTTP_REFERER'}=~ /ime\./i)){
		  	&error(" 現在HTMLキャッシュ排出モードになっています。CGIへの直接のアクセスはできません。 ");
		 }
		}

		$PM{'img_dir'} = './img-box';		# デフォルト位置


		if($PM{'max_message'} > 1500){
		  &error(" 保存記事数オーバ。保存記事数を減らしてください。機\能\制限があります。 ");
		}

		# オリジナル画像をダイエットする場合
		if($diet_org_img == 1 ){
		 # 比較的大きなものも許可する
		 if($PM{'max_upload_size'} > 11000){
		  # ↑変更時は過CPU負荷防止(sub make_snl_fileも変更すること)
		  &error(" Upload画像サイズ制限オーバ。画像縮小時にサーバ上でメモリが大量に必要になるため、Upload画像サイズ制限を10000KBより小さくしてください。機\能\制限があります。 ");
		 }		
		# オリジナル画像をダイエットしない場合
		}else{
		 # 比較的制限を厳しくする
		 if($PM{'max_upload_size'} > 2450){
		  # ↑変更時は過CPU負荷防止(sub make_snl_fileも変更すること)
		  &error(" Upload画像サイズ制限オーバ。Upload画像サイズ制限を1500KB以下に小さくしてください。機\能\制限があります。 ");
		 }		
		}		

}

#================#
# 開店確認
#================#

sub check_open{

    if($PM{'bbs_open'} ==0){
	&error("$PM{'oyasumi_message'}");
    }

}

#=====================#
# 入力データを読む
#=====================#
# 2000.12
# 携帯専用をベースにJ-PHONEから画像アップロードに対応させたもの
# PC/Macからのアップロード関連のコードは削除してあります。
#
sub read_input{

	# ■変数の初期化
	local($name);
	undef $img_data_exists;
	undef @NEWFNAMES;
	undef $jcode_eval_check_flag;

	# ■データの取得＆転送データのサイズをチェック
   	$ENV{'REQUEST_METHOD'} =~ tr/a-z/A-Z/;
#TODO
#	if($PM{'max_upload_size'} > 2200){$PM{'max_upload_size'}='2200';}# 変更禁止
	if($PM{'max_upload_size'} > 110000){$PM{'max_upload_size'}='110000';}# 変更禁止
	$max_content_length	=($PM{'max_upload_size'} + 1)*1000;
	$max_content_limit	="$PM{'max_upload_size'}";

	if($ENV{'REQUEST_METHOD'} eq "POST"){

		# OSの種別を判別
		$www_server_os =&check_www_server_os;

		if($www_server_os=~ /win/i){
			binmode(STDIN);
		}

		if($ENV{'CONTENT_LENGTH'} > 210000000){
			&error(" デ\ータ容量が大きすぎます。画像や動画のサイズは$max_content_limit KB以下にしてから、投稿してください。なお、写メの場合、撮影画質をFINEからノ\ーマルに変更して再撮影すると大幅にサイズが小さくなります。動画の場合は、撮影時間を短くするとサイズが小さくなります。ecode=pre ");
			exit;
		}

		# 2000/02/02 変更
		read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});

		if($ENV{'CONTENT_LENGTH'} > $max_content_length){
			&error(" デ\ータ容量が大きすぎます。画像や動画のサイズは$max_content_limit KB以下にしてから、投稿してください。なお、写メの場合、撮影画質をFINEからノ\ーマルに変更して再撮影すると大幅にサイズが小さくなります。動画の場合は、撮影時間を短くするとサイズが小さくなります。ecode=after ");
			exit;
		}

	}elsif($ENV{'REQUEST_METHOD'} eq 'GET'){
		$buffer = $ENV{'QUERY_STRING'};

		# XSS対策 2014.12.15 GETでこの手のパラメータを渡すことはないので、厳しくしておく
		# 遷移上、これらを GET渡しでURLエンコードで渡すことはないから、入っていたらXSSトライとみなす
					# 改行CR,LF, ; ,= , & , ', (, ) ,",!
		$buffer =~ s/(%0D|%0A|%3b|%3d|%26|%27|%28|%29|%22|%21)/RemovebyImgboardSecurityCheck_XSS/ig;

		$buffer =~ s/(<|%3C)/RemovebyImgboardSecurityCheck_3C/ig;
		$buffer =~ s/(>|%3E)/RemovebyImgboardSecurityCheck_3E/ig;

	}else{
		return 0; 
	}

	# ■ファイル名に使う、日付関連のパラメータを作る

	# パラメータをチェック
	if(($PM{'gisa'}=~ /^(\d+)$/)&&($PM{'gisa'} != 0)){
		$PM{'gisa'}=$PM{'gisa'};
	}else{
		$PM{'gisa'}=0;
	}

	($sec,$min,$hour,$mday,$mon,$year,$wday,$yday) = localtime(time + $PM{'gisa'}*60*60);

	$year += 1900;				# 2000年対策

	$month = $mon + 1;
	if ($month < 10) { $month = "0$month"; }
	if ($mday  < 10) { $mday  = "0$mday";  }
	if ($sec   < 10) { $sec =  "0$sec";    }
	if ($min   < 10) { $min =  "0$min";    }
	if ($hour  < 10) { $hour = "0$hour";   }
	if ($yday  < 400){ $yyday= 385+"$yday";}
	$unq_id="$year"."$month"."$mday"."$hour"."$min"."$sec";

	# -----準備完了-------------------------------------------------------

	# ■以下フォームのデコード処理


	# ■マルチパートじゃない時のフォーム処理
	# (通常はこれです)

	if($ENV{'CONTENT_TYPE'} !~ /multipart\/form-data/){

	   @pairs = split(/&/,$buffer);

	   foreach $pair(@pairs){
	     ($name,$value) = split(/=/,$pair);
	     $value =~ tr/+/ /;
	     $value =~ s/%([0-9a-fA-F][0-9a-fA-F])/pack("C", hex($1))/ego;
	     # sjisに変換 (imgboard1.22 Rev.3)
	     # jcode_sj.pl関連の設定ミスをトラップして検出
	     # (一度成功すればスキップして高速化)
	     if($jcode_eval_check_flag != '1'){
	       eval "&jcode'convert(*value, 'sjis','sjis','z');";
	       if($@ eq ""){
	 	 $jcode_eval_check_flag=1;
		 # 成功
	       }else{
		 # 失敗
		 #&error(" CGI設定エラー 何らかの理由で日本語ライブラリ「 $PM{'jcode_name'} 」の読み込みに失敗しました。<BR> jcode_sj.pl等の名前が正しく指定されていないか、あるいは指定パス「 $PM{'jcode_name'} 」に該当ファイルが存在しないか、あるいはパーミッションが正しくないものと思われます ");
	       }
	     }else{
		&jcode'convert(*value, 'sjis','sjis','z');
	     }

    	# 2011.12.15 XSS対策
		$value =~ s/(%3C|<)(\s*)script//ig;		# scriptタグ禁止
		$value =~ s/(%3C|<)(\s*)EMBED//ig;		# EMBEDタグ禁止
		$value =~ s/(%3C|<)(\s*)OBJECT//ig;		# OBJECTタグ禁止
		$value =~ s/(%3C|<)(\s*)iframe//ig;		# SCRIPTタグ禁止

		$value =~ s/(%3C|<)(\s*)/&lt;/ig;		# タグ禁止
		$value =~ s/(%3E|>)(\s*)/&gt;/ig;		# タグ禁止

	     $FORM{$name} = $value;
    	   }# end of foreach

	}else{
	# ■マルチパート時のフォーム処理
	# (J-PHONEのパケット機、ドコモのiショットサービス機以降)

#	  &error(" $cgi_name はimode専用であり、これ経由での画像デ\ータのアップロードはできません。画像アップロード機能を使うためにはパソコンでアクセスし、<a href=\"$PM{'cgi_hontai_name'}\">imgboard.cgi</a>本体にアクセスしてアップしてください ");

	  # □セキュリティチェック	
	  # □METHODのチェック	
	  &error(" multipart\/form-dataを使うときは METHODをPOSTにしてください。 ") if($ENV{'REQUEST_METHOD'} ne "POST");


	  # □携帯以外の画像投稿をリジェクトする
	  if($keitai_flag eq "J-PHONE"){
		if($jstation_flag >= 4){
		  # J-SKYパケット機 対応
		}else{
		  &error(" SoftBank(J-PHONE)で、画像を直接ｱｯﾌﾟﾛｰﾄﾞできるのは、51ｼﾘｰｽﾞ以降のパケット対応機\です。それ以外の機\種ではｱｯﾌﾟﾛｰﾄﾞできません ");
		}
	  }elsif($keitai_flag eq "imode"){
		if($http_upload_ok_flag == 1){
		  # 906/706i以降の動画・画像2MBアップロード機
		}else{
		  &error(" ドコモで、画像を直接ｱｯﾌﾟﾛｰﾄﾞできるのは、906/706iｼﾘｰｽﾞ以降の動画・画像2MBアップロード対応機\です。それ以外の機\種ではｱｯﾌﾟﾛｰﾄﾞできません ");
		}
	  }elsif($keitai_flag eq "pc"){
		if(($KEITAI_ENV{'MACHINE_TYPE'} eq "iPod")||($KEITAI_ENV{'MACHINE_TYPE'} eq "iPad")||($KEITAI_ENV{'MACHINE_TYPE'} eq "iPhone")||($HTTP_USER_AGENT=~ /android/i)){
		  # iPhone/iPod/Androidは許可
		}else{
#TODO		&error(" PCからは、画像をアップロードを受け付けません。 ");
		}
	  }else{
		&error(" この携帯からは、画像をアップロードを受け付けません。 ");
	  }

	  # □multipart/form-dataの場合の処理開始
	  $buffer =~ /^(.+)\r\n/;
	  $boundary = $1;
	  @pairs = split(/$boundary/, $buffer);

	  foreach $pair(@pairs){
		$check_count++;
		$pair=~ s/\r\n$/\r\nD_End/;
		@vars = split(/\r\n/, $pair);
		$vars = @vars;

	  	#---サポート用----#
		if(($check_count=='7')&&($FORM{'email'} eq "mt1")){&error(" デバックモードall-$vars,vars0- $vars[0]<BR>,1-$vars[1]<BR>\n,2-$vars[2]<BR>\n,3-$vars[3]<BR>\n,4-$vars[4]<BR>\n,5-$vars[5]<BR>\n,6-$vars[6]\n<BR>,7-$vars[7]\n.<BR>,Perl ver $] <BR>@vars test ");}
		#-----------------#

		# □アップロードファイルがついている場合

		if(($vars > 4)&&($vars[1] =~ /name\=\"(.+)\"\;\sfilename\=\"(.+)\"/)){

		  $name  = $1;
		  $fname = $2;
		  $content_type = $vars[2];
		  $full_fname = $fname;		# R7 NEW

		  # --- サポート用 2 ----#
		  if($FORM{'email'} eq "mt2"){&error("デバックモードall-$vars,name $name fname $fname content_type $content_type ");}
		  #-----------------#

        	  # マイムタイプにより、データを判別し、拡張子を生成する

#		# R7 の新機能を試す
#		if($full_fname!~ /^http:\/\//i){

	       	  # マイムタイプが不明な場合
		  if(($fname ne "")&&($content_type eq "")){

# 以下のケースが想定される．
# ケース１）J-PHONEの実装がいい加減で、マイムデータがそもそもない
# ケース２）J-PHONEのユーザが知らないもらったデータをアップロード

# この場合はデータのヘッダ部分のテキスト解析からの自動判別を試みる（Gif,JPEG）．
# 失敗したら拡張子が存在するかどうかをチェック
# 存在したら後の拡張子による判断に任せる．
# 存在しない場合警告を出し終了．

			# データヘッダから画像の種類を自動判別
			if($vars[3] =~ /^GIF8/i){
				$check_m .=" ヘッダー分析の結果４はGIF <BR>";
				$content_type="image/gif";
			}elsif($vars[3] =~ /^(.+)JFIF/i){
				$check_m .=" ヘッダー分析の結果４はJPEG <BR>";
				$content_type="image/jpeg";
			}elsif($vars[3] =~ /^\x89PNG/i){
				$check_m .=" ヘッダー分析の結果４はPNG <BR>";
				$content_type="image/png";
			# 拡張子らしき物がついている場合
			}elsif($fname=~ /\.(\w){1,4}$/){
				$content_type="unknown";#後の拡張子による判断に任せる
			}else{
				&error(" アップロードエラー。アップロードデータの属性が判断できません。<BR>\nファイル名に拡張子(.png.jpeg等)が<BR>\nついてない可\能\性があります。<BR>\nファイル名に適切な拡張子をつけてください。<BR><!--fname,$fname,Mime_types,$content_type,Mes.$check_m-->");
			}

			# マイムタイプより、拡張子を作る
			$ext = &content_type_check($content_type);

			# 画像データのみを抽出
			# ($vars[3]に実体,4=D_End $vars 5)
			foreach($i=3; $i<$vars;$i++){
				if($data eq ''){
					$data = $vars[$i];
				}else{
					$data .= "\r\n$vars[$i]";
				}
			}
			$data=~ s/\r\nD_End$//;

		  # マイムデータが通知された場合
		  }else{

			# マイムタイプより、拡張子を作る
			# 基本方針はセキュリティ重視からマイムタイプ優先
			$ext = &content_type_check("$content_type");

			# 画像データのみを抽出
                        #($vars[4]に実体,5=D_End,$vars 6)
			foreach($i=4; $i<$vars; $i++){
				if($data eq ''){
					$data = $vars[$i];
				}else{
					$data .= "\r\n$vars[$i]";
				}
			}
			$data=~ s/\r\nD_End$//;
                  }

		  # マイムタイプによる拡張子生成終了

		  # ここよりファイル書き出し処理になる

		  # 設定ミスをチェックする
		  $PM{'img_dir'} = '.' if($PM{'img_dir'} eq '');

		  if($PM{'img_dir'}=~ /^http\:\/\//i){
			&error(" img_dirの指定が間違えています。<BR> ディレクトリとＵＲＬは別の概念です。ディレクトリ指定が、httpで始まることはありません。<BR> 設定を変更してください。");
		  }

		  # 画像保存ディレクトリの確認
		  if(-d "$PM{'img_dir'}"){
		  }else{
			&error(" 画像データ保存用ディレクトリ\"$PM{'img_dir'}\"が見つかりません．<BR>指定ディレクトリ\"$PM{'img_dir'}\"が存在しない可\能\性があります<BR>画像保存用ディレクトリのパス設定をご確認ください．");
		  }

		  # ファイル名を決める
		  # パス名を消して、ファイル名のみを残す。
		  #95/NTからのアップロードに対応
		  $fname=~ s/^(.*)\\//;
		  # UNIX からのアップロードに対応
		  $fname=~ s/^(.*)\///;

		  #&error("ファイル名 $fname");

		  $use_orig_name=0;		# オリジナルファイル名保存機能削除
						# 今後は e_FTPboardでのみサポート
		  if($use_orig_name==1){				
		  #	&use_orig_name;
		  }else{
		  # 時刻でファイル名を付けるオプション。
		  # ファイル名のコンフリクトを防ぐ

			$date_count="19981204201523";
			$date_count="$year"."$month"."$mday"."$hour"."$min"."$sec";

			# ファイル名が重なる場合変更する
			if( -e "$PM{'img_dir'}/img$date_count\.$ext"){
				$date_count++;
			}elsif( -e "$PM{'img_dir'}/img$date_count\.$ext"){
				$date_count++;
			}elsif( -e "$PM{'img_dir'}/img$date_count\.$ext"){
				&error(" ファイル名決定処理中にエラーが発生しました。時刻ベースmode ");
			}

			$new_fname = "img$date_count\.$ext";
		  }

#		}# http://の終わり


		  # 複数ファイルアップロード対応用
		  push(@NEWFNAMES, $new_fname);

		  open(OUT, ">$PM{'img_dir'}/$new_fname")|| &error(" 画像デ\ータを$PM{'img_dir'}に保存中にエラーが起きました．<BR>指定ディレクトリ\"$PM{'img_dir'}\"に書込み許可がない可\能\性があります.<BR>ディレクトリのパーミション設定を確認してみてください．");
		  # IIS,PWS(NT/95)対策
		  if($www_server_os=~ /win/i){
			binmode(OUT);
		  }
		  eval "flock(OUT,2);" if($PM{'flock'} == 1 );
		  print OUT $data;
		  eval "flock(OUT,8);" if($PM{'flock'} == 1 );
		  close(OUT);

		  # テンポラリアップロードデータの存在確認フラグ
		  # 後処理で,登録中断エラー発生時に画像ファイルを削除するために使用。
		  # 削除はsub errorルーチン内で行う。
		  $img_data_exists=1;

		  # 携帯用ファイル作成 (R7 NEW)

		  #200306 add
		  if(-e "$PM{'set_make_snl_cgi_name'}"){
			require "$PM{'set_make_snl_cgi_name'}";
		  }
		  &make_snl_file;

		# □アップロードファイル以外のフォームの処理
		}elsif(($vars > 3) && ($vars[1] =~ /name\=\"(\S+)\"/)){

		  $name =$1;
		  $value = "$vars[3]";

		  # テキストエリアに関する処理
		  if($vars > 5){
			$value .= "\r\n";
			foreach($i=4; $i<$vars; $i++){
				$value .= "$vars[$i]\r\n";
			}
			$value=~ s/\r\nD_End\r\n$//;
			$value=~ s/D_End//g;
			#$value=~ s/\r/CR/g;
			#$value=~ s/\n/LF/g;
		  }

		  # sjisに変換 (imgboard1.22 Rev.3)
		  # jcode_sj.pl関連の設定ミスをトラップして検出
		  # (一度成功すればスキップして高速化)
		  if($jcode_eval_check_flag != '1'){
			eval "&jcode'convert(*value, 'sjis','sjis','z');";
			if($@ eq ""){
			    $jcode_eval_check_flag=1;
		 	    # 成功
			}else{
		 	    # 失敗
				&error(" CGI設定エラー 何らかの理由で日本語ライブラリ「 $jcode_name 」の読み込みに失敗しました。<BR> jcode_sj.pl等の名前が正しく指定されていないか、あるいは指定パス「 $jcode_name 」に該当ファイルが存在しないか、あるいはパーミッションが正しくないものと思われます ");
			}
		  }else{
			&jcode'convert(*value, 'sjis','sjis','z');
		  }
		  $FORM{$name} = $value;		# valueを返す

		}# □vars選択の終わり

	  }# foreach $pair(@pairs)の終わり

	}# マルチパート/非マルチパートの選択文の終わり

	# WindowsCE対策の属性名変更による外部設定ファイルの互換性
	# の問題をカバーする
	$FORM{'bbsaction'}	= "$FORM{'action'}" if($FORM{'action'} ne "");
	$FORM{'pre_bbsaction'}	= "$FORM{'pre_action'}" if($FORM{'pre_action'} ne "");
	$FORM{'prebbsaction'}	= "$FORM{'pre_bbsaction'}" if($FORM{'pre_bbsaction'} ne "");

	# J-PhoneでTEXTAREAを使うと、改行が混ざる可能性がある。それを防ぐ
	$FORM{'img'}=~ s/\n//g;
	$FORM{'img'}=~ s/\r//g;

#	&download_from_web("$FORM{'img'}");

}
#
#=========================#
# 記事データの追加 (R6)
# 2000.11 (スレッド対応型)
#=========================#

sub post_data{

	undef @NEW_MESSAGE;
	local($old_seq_no,$new_seq_no,$mes_counter);
	local($img_data_size_num);
	local($sage_flag);

	# ●セキュリティチェック
	#
	# GETに投稿を受け付けない。(ただしJSKYのみ可能)
	# あと$PM{'no_upload_from_pc'} ==1の場合はPCから投稿させない。
 	&check_form_method(" セキュリティ警告 "," GETによる記事投稿は受け付けません ");

	if($PM{'bbs_open'} == 2){
		&error(" 管理人の設定により、掲示板は書き込みお休み中(ReadOnly)となっております。記事の投稿はできません。 ");
	}elsif($PM{'bbs_open'} < 3){
		&error(" 管理人の設定により、掲示板は書き込みお休み中となっております。記事の投稿はできません。 ");
	}

	# ●フォームの内容をチェック
	&form_check;

	# フォームチェックで問題があれば終了する
	if($error_message ne ''){
		&rm_tmp_uploaded_files;
		&set_cookies;		# クッキーをセット(120Rev5以降)
		&error($error_message);
		exit;
	}

	# ●各種チェック終了、ここより各種変数の準備

	# 記事の日付表示（変更可能)
	$date_data = "\[$year/$month/$mday,$hour:$min:$sec\]";

	# 画像タイトル名作成
        if(($img_location ne '')&&($imgtitle eq '')){
	# タイトルがない場合はファイル名がタイトル
		$imgtitle="$img_location";
	}

	# 投稿画像の容量を計算
	if($img_location ne ''){
		$content_length="$ENV{'CONTENT_LENGTH'}";
		$content_length="$content_length"-800;
		$content_length_kb=int($content_length/1024);

		# R7 new Webでゲットしたファイルにサイズに差換え
		if($web_get_file_size > 0){
			$content_length_kb=int($web_get_file_size/1024);
		}

		if(("$content_length" > 0)&&("$content_length_kb"==0)){
	        	$img_data_size=1;
		}else{
        		$img_data_size="$content_length_kb";
		}
		$img_data_size_num="$img_data_size";
		$img_data_size="($img_data_size KB)";
	}
		# imgsizeのバージョンをチェック
	if($imgsize_lib_flag ==1){
		unless($imgsize_version >=20000509){
			&error(" 管理者設定のエラー。処理を中止しました。<BR>
			imgsize.plのバージョン $imgsize_version は古過ぎます。最新版をご利用ください。");
		}
	}

	# 投稿画像のプロパティを取得
	&check_uploaded_img_property;

	sub check_uploaded_img_property{
	  if((-e "$img_location")&&($imgsize_lib_flag== 1 )){	
		&imgsize("$img_location");
		if(($IMGSIZE{'result'} ==1)&&($img_data_exists==1)){
		#	$IMGSIZE{'name'}で渡す;
		}else{
			undef %IMGSIZE;
		}
	  }
	}

	# セパレータとして問題あるものを、事前に置換
	$subject=&Enc_EQ("$subject");

	undef $tmp_data;

	foreach $p_key(keys %FORM){
		if($p_key=~ /^opt(.+)$/){
			$tmp_data=&Enc_EQ($FORM{$p_key});
			$opt_data.="opt_data_"."$1"."\="."$tmp_data"."\;";
			undef $tmp_data;
		}
	}

	# 準備完了

	# ●メッセージを読み込む

	$comment_force_reload =1; # 慎重にするため、強制で読む
	undef @MESSAGE;	
	&read_file_data("$PM{'file'}");
	 # $HEAD_MESSAGE{'name'}にパラメータが入る
	 # $REM_HEAD_MESSAGE{'name'}にコメントアウトパラメータが入る
	 # @MESSAGEに記事ログが入る

	$old_seq_no=$HEAD_MESSAGE{'seq_no'};	


	# $all_message に記事数を入れる
	$all_message=@MESSAGE;


	# 連番処理
	if($old_seq_no eq ""){# ない場合は作る
		$old_seq_no='0';
	}
	$new_seq_no=$old_seq_no+1;

        # 暗号化
	if(($PM{'use_crypt'} == 1)&&($rmkey ne "no_key")&&($rmkey ne "")){
		$rmkey		= &make_pass("$rmkey");
	}

	# SNLとして存在するデータのリストを作る
	foreach (@SNL_MADE_DATA){
#		$existing_snl_type_list.="$_"."\/";
	}

	# 輸入URLでタブと;をエスケープ
	$img_import_url=~ s/\t//g;
	$img_import_url=~ s/\;//g;
	$img_import_url=~ s/\s+$//g;

	# sage機能
	if(($PM{'use_sage'} == 1)&&($email=~ /^sage$/i)){
		$email="";
		$sage_flag=1;
	}

	# ●新しいメッセージを作る（imgboard1.22R6.1新形式）
	$new_message = "$subject\t$name\t$email\t$date_data\t$body<\!--opt\:$opt_data-->\t$img_location\t$imgtitle<\!--dsize=$img_data_size;type=$IMGSIZE{'type'};width=$IMGSIZE{'width'};height=$IMGSIZE{'height'};hw_racio=$IMGSIZE{'hw_racio'};size=$img_data_size_num;img_import_url=$img_import_url;snl_dir=$PM{'img_dir'};snl_location=$snl_location;exist_snl_type=$existing_snl_type_list;-->\t$new_seq_no\t$FORM{'blood'}\t$rmkey\t$unq_id\t$permit\t$other";

	undef %IMGSIZE;

	# レスの付いた記事を上へ持って行くために、親スレッドリストへ追加する
	# 2004.12 sage機能を追加
	if(($FORM{'sage'} == "1")||($sage_flag == 1 )){
	  # リストに記録しない
	}else{
	  &update_bloods_list;
	}

	# 記事データを追加する
	if($FORM{'parent'} eq ""){
	# 親記事の場合
		unshift(@MESSAGE, $new_message);
		$all_message++;	# 記事数は一つ増
	}else{
	# 子記事の場合
		# 記事データを探索する

		$mes_counter=1;
		$last_child_number=0;

		foreach(@MESSAGE){
			if($_ =~ /$FORM{'blood'}/){
				$last_child_number=$mes_counter;
			}
			$mes_counter++;
		}

		# 記事データを追加する
		$mes_counter=1;

		foreach(@MESSAGE){
			push(@NEW_MESSAGE, $_);
			if($mes_counter==$last_child_number){
				push(@NEW_MESSAGE, $new_message);
				$all_message++;	# 記事数は一つ増
			}
			$mes_counter++;
		}
		@MESSAGE=@NEW_MESSAGE;

	}

	# 一番古い記事に関連した画像を削除しておく

	if($all_message > $PM{'max_message'}){
		for($i=$PM{'max_message'}; $i<$all_message; $i++){
			if($MESSAGE[$i] =~ /^([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]*)\t([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)/){

				$remove_file = $6;
				local($remove_imgtitle) 	= $7;
				local($tmp_unq_id)  		= $11;

				if($remove_file ne '' && -e $remove_file){
					unlink($remove_file);
					# メタファイルも削除する
					&rm_meta_file("$remove_file");

					# 携帯用ファイルも削除する
					if($remove_file=~ /\.(jpe?g|gif|png|bmp|mng)$/i){
					  # SNLのパスを調べる
					  if($remove_imgtitle ne ''){
					    &parse_img_param($remove_imgtitle);
					  }
					  &rm_snl_file("$tmp_unq_id","$IMG_PARAMETERS{'snl_dir'}","$IMG_PARAMETERS{'exist_snl_type'}");
					}

				}
			}

		}
	}

	# ●新しいファイルとして出力

	# 出力記事数を決める
	if($all_message > $PM{'max_message'}){
		$repost_message = $PM{'max_message'};
	}else{
		$repost_message = $all_message;
	}

	# 書き出し前にバッファに入れる
	undef @TMP_MESSAGE;
	$HEAD_MESSAGE{'seq_no'}=$new_seq_no;

	for($i=0; $i<$repost_message; $i++){
		$TMP_MESSAGE[$i]="$MESSAGE[$i]";
	}

	# 書き出し処理
	 # 以下の内容を出力する
	 # $HEAD_MESSAGE{'name'}
	 # $REM_HEAD_MESSAGE{'name'}
	 # @TMP_MESSAGE

	if($PM{'make_backup_file'}== 1 ){
 	 &make_backup_file; # バックアップファイル自動作成
	}

	&write_file_data("$PM{'file'}");
}
#
#
# 最近の記事とその血統(スレッド)を記憶するサブサブルーチン
# imgboardR6のログにおいては、最新の15記事分の記事ＩＤと
# 親の血統(スレッド)をパラメータとして覚えておくことにする。
#
sub update_bloods_list{

	local ($b_new_list);

#	return if($HEAD_MESSAGE{'last_bloods'} eq "");
	$HEAD_MESSAGE{'last_bloods'} =	&Enc_EQ("$HEAD_MESSAGE{'last_bloods'}");

	# 親記事追加の場合,親ー親で追加する
	if($FORM{'parent'} eq ""){	
		  return if($unq_id eq "");
		  $b_new_list="$unq_id-$unq_id"."\,"."$HEAD_MESSAGE{'last_bloods'}";
	}else{ 			
	# 子記事の場合、子ー親で追加する
	  return if($unq_id eq "");
	  return if($FORM{'blood'} eq "");
	  $b_new_list="$unq_id-$FORM{'blood'}"."\,"."$HEAD_MESSAGE{'last_bloods'}";
	}

	# １５以上になれば、１６以降は捨てる
	if($b_new_list=~ /^(\,*)([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)\,([\d|\-]*)(.*)/){
		$b_new_list="$2"."\,"."$3"."\,"."$4"."\,"."$5"."\,"."$6"."\,"."$7"."\,"."$8"."\,"."$9"."\,"."$10"."\,"."$11"."\,"."$12"."\,"."$13"."\,"."$14"."\,"."$15"."\,"."$16"."\,";
	}


	$HEAD_MESSAGE{'last_bloods'}=$b_new_list;	
}
# 最近の記事とその血統(スレッド)を記憶するリストを使い
# 最新のスレッドUIDを出す
#
sub load_latest_bloods_list{

	local (@LATEST_THREAD);
	local (@SEP_BLOODS);
	local ($child,$my_parent);
	local ($already_parent_exist_flag);

	return if($HEAD_MESSAGE{'last_bloods'} eq "");

	@SEP_BLOODS=split(/\,/,$HEAD_MESSAGE{'last_bloods'});# 一度分解する

	# 最新のスレッドのリストを作る
	for($numb=0;$numb < scalar(@SEP_BLOODS) ;$numb++){
		($child,$my_parent)=split(/\-/,$SEP_BLOODS{$numb});# 分解する
		$already_parent_exist_flag = 0;
		foreach(@LATEST_THREAD){
		    if($_ eq "$my_parent"){
			$already_parent_exist_flag = 1;
		    }
		}
                # リストにない場合のみ追加する 
		if($already_parent_exist_flag == 0){
		  push(@LATEST_THREAD,$my_parent);
	        }
	}

	return(@LATEST_THREAD);
}
#
#=============================#
# ファイルから記事データを読み
#=============================#
# 引数はファイル名
# 
# 中で呼ばれるsub open_comment_fileにより
# 1.@main に全ラインデータが入る
# 2.$comment_open_flagが1になる
# 強制リロードは$comment_force_reloadを1にする
# $HEAD_MESSAGE{'name'}にヘッダデータ
# $REM_HEAD_MESSAGE{'name'}にコメントアウトされたヘッダデータ
# @MESSAGEに記事データが入る

sub read_file_data{
 
	local($tmp_ffile) = @_;	# 引数はファイル名
	local($tmp_mes_line);


	# データ読込み（高速化のための@main再読み込みスキップ付き）
	# @mainにファイルの内容を格納
	if(($comment_open_flag !=1)||($comment_force_reload ==1)){
		&open_comment_file("$tmp_ffile");
	}

	# 出力変数群をリフレッシュする
	undef %HEAD_MESSAGE;
	undef %REM_HEAD_MESSAGE;
	undef @MESSAGE;

	foreach(@main){

		# HEADER保存 (将来への拡張もここで対応)
		# R6形式で今使っているのはこれだけ
		if($_ =~ /^\,param_seq_no(\s*)=(\s*)(\w+)(\s*)/i){
			$HEAD_MESSAGE{'seq_no'}=$3;
		}
		if($_ =~ /^\#?\,param_/i){
			# R6形式で今使っているのはseq_noだけ
			# R6形式があればそれを優先。なければR6.1で読む
			# 将来はR6.1にすべて移行できるようにしておく
			if($_ =~ /^\,param_seq_no(\s*)=(\s*)(\w+)(\s*)/i){
				if($HEAD_MESSAGE{'seq_no'} eq ""){
					$HEAD_MESSAGE{'seq_no'}=$3;
				}
			# 今後はR6.1形式に変更(=の横から;までがパラメータ)
			}elsif($_ =~ /^\,param_(\w+)(\s*)=(.*)\;/i){
				$HEAD_MESSAGE{$1}=$3;
				$HEAD_MESSAGE{$1}=&Dec_EQ("$HEAD_MESSAGE{$1}");
			}elsif($_ =~ /^\#\,param_(\w+)(\s*)=(.*)\;/i){
				# (#でコメントアウトしたものを含む)
				$REM_HEAD_MESSAGE{$1}=$3;
				$REM_HEAD_MESSAGE{$1}=&Dec_EQ("$REM_HEAD_MESSAGE{$1}");
			}else{
#				&error(" デバック 未定義パラメータ発見 $_ ");
			}

		}

		# 記事をバッファ@MESSAGEに入れる
		if($_ =~ /^([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t/){
			$tmp_mes_line="$_";
			chop($tmp_mes_line);
			push(@MESSAGE, $tmp_mes_line);
		}
	}
}

#=========================#
# 記事ファイルを開く
#=========================#
# 指定ファイルを読み、@main バッファに格納する。
# @main に全ラインデータが入る
# $comment_open_flagが1になる
# 強制リロードは$comment_force_reloadを1にする

sub open_comment_file{

#Debug
$read_file_counter++;

	local($local_file) = @_;	# 引数はファイル名
	undef @main;
	open(IN, "$local_file")|| &error(" 設定エラー．データ保存用ファイル\"$local_file\"が見つかりません．処理は中断されました．");
	eval "flock(IN,1);" if($PM{'flock'} == 1 );
		@main = <IN>;
	eval "flock(IN,8);" if($PM{'flock'} == 1 );
	close(IN);

	$comment_open_flag	='1';
	$comment_force_reload	='0';
}

#============================#
# ファイルに記事データを書く
#============================#
# 引数はファイル名
# $HEAD_MESSAGE{'name'}
# $REM_HEAD_MESSAGE{'name'}
# @TMP_MESSAGE（改行なし）に
# 記事データを入れて渡すこと
sub write_file_data{
 
	local($tmp_ffile) = @_;	# 引数はファイル名

	# 書き出し処理
	open(OUT, "> $tmp_ffile")|| &check_file_open_error;

	eval "flock(OUT,2);" if($PM{'flock'} == 1 );


		# ヘッダパラメータを出力(R6形式)
		foreach $p_key(keys %HEAD_MESSAGE){
		  	if($p_key eq "seq_no"){
	 		  # seq_noだけ互換性のためにR6形式で出す
			  print OUT "\,param_seq_no=$HEAD_MESSAGE{'seq_no'}\n";
			}
		}
		# ヘッダパラメータを出力(R6.1形式)
		foreach $p_key(keys %HEAD_MESSAGE){
	 		  # seq_no以外はR6.1形式で出す
			  $file_line=&Enc_EQ($HEAD_MESSAGE{$p_key});
			  $file_line=~ s/\n//g;
			  $file_line=~ s/\r//g;
			  $file_line="\,param_"."$p_key"."\="."$file_line";
			  print OUT "$file_line\;\n";
			  undef $file_line;
		}

		# コメントアウトされたヘッダパラメータを出力(R6.1形式)
		foreach $p_key(keys %REM_HEAD_MESSAGE){
			$file_line=&Enc_EQ($REM_HEAD_MESSAGE{$p_key});
			$file_line=~ s/\n//g;
			$file_line=~ s/\r//g;
			$file_line="\#\,param_"."$p_key"."\="."$file_line";
			print OUT "$file_line\;\n";
			undef $file_line;
		}

		# 記事部分を出力
		foreach $file_line(@TMP_MESSAGE){
		    if($file_line eq ""){
			next;
		    }
		    $file_line=~ s/\n//g;
		    $file_line=~ s/\r//g;
		    print OUT "$file_line\n";
		}
	eval "flock(OUT,8);" if($PM{'flock'} == 1 );
	close(OUT);

	# データ消失を防ぐ
	$comment_force_reload=1;
}

sub check_file_open_error{
  unless(-e "$tmp_ffile"){
	&error(" 設定エラー．データ用保存ファイル\"$tmp_ffile\"にデータを書込むことができませんでした．<BR>\"$PM{'file'}\"という名前のファイルが正しい位置に見つからないためです。パスの設定を再確認してみてください。投稿処理は中断されました．");
  }
  unless(-w "$tmp_ffile"){
	&error(" 設定エラー．データ用保存ファイル\"$tmp_ffile\"にデータを書込むことができませんでした．<BR>$PM{'file'}に対する書込み許可がないためだと思われます．パーミションの設定を再確認してみてください。投稿処理は中断されました．");
  }
  &error(" 設定エラー．データ用保存ファイル\"$tmp_ffile\"にデータを書込むことができませんでした．<BR>設定を再確認してみてください。投稿処理は中断されました．");
}

#================================#
#  記事データの削除 (メイン部)
#================================#
# R6Ver(親子スレッド対応型)
# 2000/11
#
sub remove_data{

	$tmpnum=0;
	local($killed_blood_name);		# 削除した親記事の血統を保存
	local($tmp_rm_num);
	@remove_list=@_;# 引数 削除リスト(新モード)

	# ■セキュリティチェック
 	if($ENV{'REQUEST_METHOD'} eq 'GET'){
	  	if(($keitai_flag eq "J-PHONE")&&($jstation_flag < 3)){
		#  Ｊフォンでかつ、ＪＳＫＹの時だけ特別にGETを認める
		}else{
		  &error(" エラー。いたずら防止のため、GETメ\ソ\ッドでは、削除できない仕様になっています ");
		}
	}

	# ■複数の削除指定を受け取り、配列にする
	# rmid旧連番S新固有IDをチェックボックスからもらう
	# R4形式を@old_remove_list R6形式を@remove_listへ入れること

	foreach $form(sort keys %FORM){
	   if($form =~ /^rmid/){
		if($FORM{"$form"} == 1){
			($tmp_old_rmid,$tmp_new_rmid)	=split(/S/,$form);
			$tmp_old_rmid =~ s/rmid//g;
			push(@old_remove_list, $tmp_old_rmid);
			push(@remove_list, $tmp_new_rmid);
		}
	   }
	}
	$remove_article_number= @remove_list;	# 削除予定数


	# ■データ読込み（スキップ付き）
	&read_file_data("$PM{'file'}");

	# ヘッダは保存
	# @MESSAGEに記事ログが入る

	# ■全体を調べて、削除しないやつを@TMP_MESSAGEに一時的に入れる
	foreach(@MESSAGE){

			$tmpnum++;
			undef @SEP_DATA;
			undef %LDATA;

#	@IM122R6DATA=('subject','name','email','date','body','img_location','imgtitle','seq_no','blood_name','rmkey','unq_id','permit','other');

			$tmpdata 	= $_;	# 全体データを保存

			@SEP_DATA 	= split(/\t/,"$_");	# 切断して配列に入れる

			$i=0;
			foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
				$LDATA{$p_key}=$SEP_DATA[$i];
				$i++;
			}


		#	$tmp_body	= $LDATA{'body'};	# check_guest_passwdへ渡す

		#	$LDATA{'img_location'}	# 画像の場所
		#	$LDATA{'seq_no'}	# 連番
		#	$LDATA{'blood_name'}	# 親の血統ID(子供のみ持つ)
		#	$LDATA{'rmkey'}		# 削除キー
		#	$LDATA{'unq_id'}	# 固有ID(時刻ベース)

			$flag_remove = 0;
		#	undef $host_flag;
			undef $allow_remove_flag;
			undef $tmp_rm_num;

			# 親が消えた場合は子記事は問答無用で全部消す
			if($killed_blood_name ne ""){
			   if($LDATA{'blood_name'} ne ""){
				if($killed_blood_name eq "$LDATA{'blood_name'}"){
				  # 子記事の画像データを消す
				  if($LDATA{'img_location'} ne ""){ # 子記事に添付画像があれば
				    # 画像ファイルを削除する
				    if(-e $LDATA{'img_location'}){
					unlink($LDATA{'img_location'});
					# メタファイルも削除する
					&rm_meta_file("$LDATA{'img_location'}");

					# 携帯用ファイルも削除する
					if($LDATA{'img_location'}=~ /\.(jpe?g|gif|png|bmp|mng)$/i){
					  # SNLのパスを調べる
					  if($LDATA{'imgtitle'} ne ''){
					    &parse_img_param($LDATA{'imgtitle'});
					  }
					  &rm_snl_file("$LDATA{'unq_id'}","$IMG_PARAMETERS{'snl_dir'}","$IMG_PARAMETERS{'exist_snl_type'}");
					}
				    }
				  }
				# 記事が削除されたらスレッドリストから削除する
				  $HEAD_MESSAGE{'last_bloods'}=~ s/$LDATA{'unq_id'}\-(\d+)\,//gi;	

				# 正規に消えた親と血統が一致した場合、子も消す
					next;
				}
			   }
			}

			undef @do_remove_list; # 初期化する

			# ここで新旧の指定の違いを吸収する
			if($LDATA{'unq_id'} ne ""){	
			# R6形式のログの場合
			# 固有IDで消す(より安全)
				$tmp_rm_num="$LDATA{'unq_id'}";
				@do_remove_list=@remove_list;
			}else{
			# R4以前の旧形式のログの場合
			# ページ中の連番で消す
				$tmp_rm_num="$tmpnum";
				@do_remove_list=@old_remove_list;
			}

			foreach $tmp_list(@do_remove_list){
				if($tmp_rm_num == $tmp_list){

					if($remove_mode eq "guest"){
#						&check_guest_passwd;# ゲストパスワードをチェック
				  	}elsif($remove_mode eq "rmkey"){
						# 削除キーをチェック
						&check_rmkey("$LDATA{'rmkey'}");
					}else{
						$allow_remove_flag=1;
					}

					if($allow_remove_flag ==1){

						$flag_remove = 1;
						# 画像ファイルを削除
						if(-e "$LDATA{'img_location'}"){
							unlink("$LDATA{'img_location'}");
							# メタファイルも削除する
							&rm_meta_file("$LDATA{'img_location'}");

							# 携帯用ファイルも削除する
							if($LDATA{'img_location'}=~ /\.(jpe?g|gif|png|bmp|mng)$/i){
							  # SNLのパスを調べる
							  if($LDATA{'imgtitle'} ne ''){
							    &parse_img_param($LDATA{'imgtitle'});
						  	  }
						  	  &rm_snl_file("$LDATA{'unq_id'}","$IMG_PARAMETERS{'snl_dir'}","$IMG_PARAMETERS{'exist_snl_type'}");
							}

						}
					}
				}
			}
			# 結果の処理
			if($flag_remove == 0){
				# 削除に失敗したときは、バッファに入れて保存、記事を残す
				push(@TMP_MESSAGE, $tmpdata);
			}else{
				# 削除に成功

				# 記事が削除されたらスレッドリストから削除する
				$HEAD_MESSAGE{'last_bloods'}=~ s/$LDATA{'unq_id'}\-(\d+)\,//gi;	


				if($LDATA{'blood_name'} eq ""){	# 新ログ形式の場合
					# 親には血統がない。
					# 親だった場合は、血統を残しておく
					# 親を削除できた場合はパスワード無用で
					# 子は全部消える。なお、子には画像はない
					$killed_blood_name="$LDATA{'unq_id'}";
				}else{				# 旧ログ形式の場合
					$killed_blood_name="";
				}
			}

	} #End of foreach

	# ■書き出し処理をする
	# $HEAD_MESSAGE{'name'}にパラメータが
	# $REM_HEAD_MESSAGE{'name'}にコメントアウトパラメータ
	# @TMP_MESSAGEに記事ログ
	&write_file_data("$PM{'file'}");
}
#
#====================================#
# 親スレッド新着順UIDリストを取得する
#====================================#
# $HEAD_MESSAGE{'last_bloods'}を入力として
# @NEW_BLOODS として親スレッド新着順UIDリストを出す
# @RECENT_MESSAGE_UID として最近の記事の新着順UIDリストを出す
#（@RECENT_MESSAGE_UIDは副産物。今は(new)表示に使っている）
sub output_new_bloods_list{

	undef @NEW_BLOODS;
	undef @RECENT_MESSAGE_UID;

	local (@SEP_FAMILY);
	local ($b_child,$b_parent,$already_find_flag);

	return if($HEAD_MESSAGE{'last_bloods'} eq "");

	# 子（親）ー親のペアに分解する
	@SEP_FAMILY=split(/\,/,$HEAD_MESSAGE{'last_bloods'});

	for($numb=0;$numb < scalar(@SEP_FAMILY) ;$numb++){
	  ($b_child,$b_parent)=split(/\-/,$SEP_FAMILY[$numb]);
	  if($b_parent ne ""){
		$already_find_flag=0;
		# 既に親リストにあれば追加しない
		foreach(@NEW_BLOODS){
		  if($_ eq "$b_parent"){
			$already_find_flag=1;
		  }
		}
		if($already_find_flag == 0){
		 push(@NEW_BLOODS, $b_parent);
		}
	  }
	  if($b_child ne ""){
		 push(@RECENT_MESSAGE_UID, $b_child);
	  }
	}
	return(scalar(@NEW_BLOODS));
}
#
#==================================================#
#  記事データの削除 (削除キー部)
#==================================================#
# 2001.02(暗号化対応) 
sub check_rmkey{

# 削除キー機能を有効にすると,削除キーが一致する場合、記事の削除ができる。
# ゲストパスワードとの同時使用はできない。削除キーが設定されてない場合は、
# 記事の削除ができる。チェックを行い,条件を満たせば$allow_remove_flag=1となる。

	local($ttmp_rmkey) = @_;	# 記事中に埋め込まれた削除キー
	# フォームで入力された削除キー（暗号化前）
	local($tmp_form_rmkey)=$FORM{'passwd'};	
	# フォームで入力された削除キー（暗号化したもの）
	local($cpt_form_rmkey);	

	if($PM{'use_crypt'} ==1 ){
		$cpt_form_rmkey=&make_pass($tmp_form_rmkey);
	}

	if(($ttmp_rmkey eq "")||($ttmp_rmkey eq "no_key")){
		# 削除キーがログにない古い記事の場合、削除を不許可
		# 削除キーがログにない記事の場合、削除を不許可
		$skipped_rmkey_remove++;	# 削除失敗した記事の数

		if($remove_article_number=='1'){
			&error(" パスワードが違います．削除を中止しました ");
		}elsif($remove_article_number == "$skipped_rmkey_remove"){
			&error(" パスワードが違います．削除を中止しました ");
		}
		return;
	}else{
		# 削除キーがログに存在する場合
		if($tmp_form_rmkey eq ""){
			&error(" 削除キーが入力されていません。削除できませんでした。<BR>この記事には投稿者により、削除キーが設定されています。記事投稿時に用いた削除キーを入力してください。なお、削除キーを忘失した場合は、掲示板管理者に頼んで削除してもらってください ");
		}elsif($tmp_form_rmkey eq "$ttmp_rmkey"){
			$allow_remove_flag=1;
		}elsif($cpt_form_rmkey eq "$ttmp_rmkey"){
			$allow_remove_flag=1;
		}else{
			&error(" 入力された削除キー「$FORM{'passwd'}」が違います。削除できませんでした。<BR>この記事には投稿者により、削除キーが設定されています。記事投稿時に用いた削除キーを入力してください。なお、削除キーを忘失した場合は、掲示板管理者に頼んで削除してもらってください ");
		}
	}

}
#=====================#
#  設定読込み処理
#=====================#
# 2000.11(暗号化対応) 
# 2001.09(シンプル＆高速化) 
sub read_config{

# 設定を読み$PM{name}を上書きする

	# ●メッセージを読み込む
	&read_file_data("$PM{'file'}");
	 # $HEAD_MESSAGE{'name'}の連想配列にパラメータが入る

	 # 上書きしても良いパラメータ(選択)
	@OVERWRITE_PARA=('title','im_body_bgcolor','make_backup_file','body_background','im_body_text','im_body_link','im_body_vlink','back_url','max_message','img_url','message_per_page','kiji_disp_limit_imode','hankaku_filter','no_upload_from_pc','no_view_from_pc','form_check_name','form_check_email','form_check_subject','form_check_body','form_check_img','form_check_rmkey','form_check_optA','form_check_optB','form_check_optC','form_check_optD','form_check_optE','form_check_optF','auto_url_link','view_passwd','use_view_password','use_post_password','post_passwd','use_html_tag_in_comment','use_img_tag_in_comment','no_upload_by_no_RH_user','no_upload_by_black_word','res_go_up','disp_new_notice','error_message_to_black_word','limit_upload_times_flag','upload_limit_type','upload_limit_times','use_email','recipient','mail_body_limit','bbs_open','oyasumi_message','keitai_force_set','use_rep','allow_other_multimedia_data','upload_mail_address','m2w_server_url','cgi_url');

	foreach (@OVERWRITE_PARA){
	  if($HEAD_MESSAGE{$_} eq ""){	# 2002.10バグ修正
		next;
	  }
	  if(($HEAD_MESSAGE{$_} ne "default")&&($HEAD_MESSAGE{$_} ne "")){
		  $PM{$_}=$HEAD_MESSAGE{$_};
	  }
	}
}
#
#=================================#
#  自動バックアップ作成処理(R6 new)
#=================================#
# 2001.07(ver.0.7)
# 万一のサーバのファイル出力中ダウンや投稿によるログ消失事故に
# 備えて、$PM{'backup_day_interval'}で指定された間隔日でfile.datの
# 自動バックアップを作る機能を追加する。
#
sub make_backup_file{

# sub post_data内で呼ばれる
# $HEAD_MESSAGE{'last_backup_date'}に最終バックアップ日時が
# unq_id と同じ形式で入っている。
# $PM{'backup_day_interval'}  でバックアップ間隔を設定し
# $PM{'backup_file_name'} にバックアップファイル名を指定し、作っておくこと

	local($do_backup_flag);
	local($today_day_count);
	local($tmp_day_count);

	# 設定されてないときは処理しない（互換性）
	if(($PM{'backup_day_interval'} eq "")||($PM{'backup_file_name'} eq "")){
	  return;
	}

	# 記事が5件以下の場合は、処理しない
	#（空ファイルのバックアップによる、バックアップファイル消滅を防ぐ）
	if($all_message < 6 ){
	  return;
	}

	# 初回用
	if($HEAD_MESSAGE{'last_backup_date'} eq ""){
	  $HEAD_MESSAGE{'last_backup_date'}='20001112020459';
	}
	if($HEAD_MESSAGE{'last_backup_date'}=~ /^(20..)(..)(..)(..)(..)(..)$/){
		$tmp_day_count=$1*365+$2*31+$3;
		if($unq_id=~ /^(....)(..)(..)......$/){
			$today_day_count=$1*365+$2*30+$3;
			if($today_day_count-$tmp_day_count > $PM{'backup_day_interval'}){
			  $do_backup_flag=1;
			}
		}
	}

	# このフラグが1ならバックアップを作る
	if($do_backup_flag ==1){
		if(-e "$PM{'backup_file_name'}"){
		   $HEAD_MESSAGE{'last_backup_date'}=$unq_id;# 最終バックアップ日を更新
		   &write_file_data("$PM{'backup_file_name'}");
		}else{
	   	&error(" 設定エラー。投稿処理は中断されました。記事バックアップデータ保存用ファイル\"$PM{'backup_file_name'}\"にデータを書込むことができませんでした．<BR>\"$PM{'backup_file_name'}\"という名前のファイルが正しい位置に見つからないためです。ファイルをまだ作っていない人は、$PM{'file'}をコピーして$PM{'backup_file_name'}という名前にして、同じディレクトリに置き、パーミッションを６０６等にして下さい。置いたけど、またこのエラーが出た人は、パスの設定を再確認してみてください。");
		}
	}
}

#=====================#
# クッキーを読む
#=====================#

sub read_cookie{

	local($given_cookie_data);

	$given_cookie_data="$ENV{'HTTP_COOKIE'}";

	# データが取れない時は以下の処理をスキップ
	if($given_cookie_data eq ""){
		undef %COOKIES;
		undef %COOKIE;
		return;
	}

	# URLデコードをする(2002.08.12)
	$given_cookie_data=~ s/%([0-9A-Fa-f][0-9A-Fa-f])/pack("C", hex($1))/eg;

	@pairs = split(/\;/,$given_cookie_data);
	foreach $pair(@pairs){
		local($name,$value) = split(/\=/,$pair);
		# エンコードしたセパレータ＝を戻す．	
		$name		=~ s/Enc_eq/\=/g;
		$value	=~ s/Enc_eq/\=/g;
		$name 	=~ s/ //g;
		$COOKIES{$name} = $value;
	}

	foreach ( split(/\,/,$COOKIES{'imgboard121'})){
		local($name,$value) = split(/\:/);
		$value=&Dec_EQ($value);
		$COOKIE{$name} = $value;
	}

}

#========================#
# クッキーを書く(R5Ver)
#========================#

# imodeではクッキーは無効なので、工夫する

sub set_cookies{

	undef $set_value;

	# セパレータと区別できなくなる＝を事前にEnc_eqに置換

	$FORM{'utc'}=$new_utc_set;	# 連続投稿カウンタ

	@ENC_COOKIE=('subject','name','email','body','viewmode',
'optA','optB','optC','optD','optE','optF',
'imgtitle','utc','entrypass','importURL');

	# 文字をエンコードする
	foreach(@ENC_COOKIE){
		&CEnc_EQ($_);
	}

	foreach $p_key(keys %T_COOKIE){
		# パスワードはXXXpasswdというNAMEにする
		# これは暗号化される
		if($PM{'use_crypt'} ==1){
		   if($p_key=~ /passwd$/){
			$T_COOKIE{$p_key}=&make_pass("$T_COOKIE{$p_key}");
		   }
		}
		$set_value.="$p_key"."\:"."$T_COOKIE{$p_key}"."\,";
	}
	$set_value.="end\:end";

	&set_cookie("imgboard121","$set_value");
}

# 繰り返し
sub CEnc_EQ{
	local($p_name) =$_[0];
	local($jp_name)=$_[0];
	$jp_name	=~ s/_//g; # J-PHONE対策
	$T_COOKIE{$p_name}	=$FORM{"$jp_name"}; # ここで吸収
	$T_COOKIE{$p_name}=&Enc_EQ($T_COOKIE{$p_name});
	return("$T_COOKIE{$p_name}");
}

sub Enc_EQ{
	# セパレータと区別できなくなる文字を事前に置換
	local($tmp_data)=@_;
	$tmp_data	=~ s/\=/Enc_eq/g;
	$tmp_data	=~ s/\:/Enc_cln/g;
	$tmp_data	=~ s/\;/Enc_scln/g;
	$tmp_data	=~ s/\,/Enc_km/g;
	return($tmp_data);
}

sub Dec_EQ{
	# セパレータと区別できなくなる文字を復元
	local($tmp_data)=@_;
	$tmp_data	=~ s/Enc_eq/\=/g;
	$tmp_data	=~ s/Enc_cln/\:/g;
	$tmp_data	=~ s/Enc_scln/\;/g;
	$tmp_data	=~ s/Enc_km/\,/g;
	return($tmp_data);
}

sub Enc_EQ2{
	# セパレータと区別できなくなる文字を事前に置換(mailto用)
	local($tmp_data)=@_;
	$tmp_data	=&Enc_EQ("$tmp_data");
	$tmp_data	=~ s/\?/Enc_qt/g;
	$tmp_data	=~ s/\&/Enc_amp/g;
	$tmp_data	=~ s/\</Enc_lt/g;
	$tmp_data	=~ s/\>/Enc_gt/g;
	return($tmp_data);
}

sub Dec_EQ2{
	# セパレータと区別できなくなる文字を復元
	local($tmp_data)=@_;
	$tmp_data	=&Dec_EQ("$tmp_data");
	$tmp_data	=~ s/Enc_qt/\?/g;
	$tmp_data	=~ s/Enc_amp/\&/g;
	$tmp_data	=~ s/Enc_lt/\</g;
	$tmp_data	=~ s/Enc_gt/\>/g;
	return($tmp_data);
}
#==============================================================#
# i-shot圧縮

sub Enc_EQ_Short{
	# セパレータと区別できなくなる文字を事前に置換
	local($tmp_data)=@_;
	$tmp_data	=~ s/http:\/\//_Hp/g;	# 圧縮する
	$tmp_data	=~ s/https:\/\//_Hs/g;	# 圧縮する
	$tmp_data	=~ s/\-/_Eh/g;		# MailBody中のセパレータなのでエスケープ
	$tmp_data	=~ s/\=/_Eq/g;
	$tmp_data	=~ s/\:/_Cln/g;
	$tmp_data	=~ s/\;/_Scln/g;
	$tmp_data	=~ s/\,/_Km/g;
	return($tmp_data);
}

sub Dec_EQ_Short{
	# セパレータと区別できなくなる文字を復元
	local($tmp_data)=@_;
	$tmp_data	=~ s/_Hp/http:\/\//g;	# 圧縮する
	$tmp_data	=~ s/_Hs/https:\/\//g;	# 圧縮する
	$tmp_data	=~ s/_Eh/\-/g;		# MailBody中のセパレータなので
	$tmp_data	=~ s/_Eq/\=/g;
	$tmp_data	=~ s/_Cln/\:/g;
	$tmp_data	=~ s/_Scln/\;/g;
	$tmp_data	=~ s/_Km/\,/g;
	return($tmp_data);
}

sub Enc_EQ2_Short{
	# セパレータと区別できなくなる文字を事前に置換(mailto用)
	local($tmp_data)=@_;
	$tmp_data	=&Enc_EQ_Short("$tmp_data");
	$tmp_data	=~ s/\?/_Qt/g;
	$tmp_data	=~ s/\&/_Amp/g;
	$tmp_data	=~ s/\</_Lt/g;
	$tmp_data	=~ s/\>/_Gt/g;
	return($tmp_data);
}

sub Dec_EQ2_Short{
	# セパレータと区別できなくなる文字を復元
	local($tmp_data)=@_;
	$tmp_data	=&Dec_EQ_Short("$tmp_data");
	$tmp_data	=~ s/_Qt/\?/g;
	$tmp_data	=~ s/_Amp/\&/g;
	$tmp_data	=~ s/_Lt/\</g;
	$tmp_data	=~ s/_Gt/\>/g;
	return($tmp_data);
}

sub set_cookie{

	#Copyright(C) to-ru@big.or.jp (1.20以降 2000年対応 NEWバージョン)
        local($name,$value) = @_;
        local($sec,$min,$hour,$mday,$mon,$year,$wday,$date);
        local($days) = 900;      # Expire Date(有効期間。デフォルト180日)

        ($sec,$min,$hour,$mday,$mon,$year,$wday) 
                        = (localtime(time+$days*24*60*60))[0,1,2,3,4,5,6];
        $sec   = "0$sec"  if($sec  < 10);
        $min   = "0$min"  if($min  < 10);
        $hour  = "0$hour" if($hour < 10);
        $mday  = "0$mday" if($mday < 10);
        $year += 1900;				# 2000年対策
        $wday  = ("Sun","Mon","Tue","Wed","Thu","Fri","Sat")[$wday];
        $mon   = ("Jan","Feb","Mar","Apr","May","Jun",
                  "Jul","Aug","Sep","Oct","Nov","Dec")[$mon];
        $date = "$wday, $mday\-$mon\-$year $hour:$min:$sec GMT";

		# 2002.08.12 Opera対策で日本語をURLエンコードすることにした
		$value =~ s/(\W)/sprintf("%%%02X", unpack("C", $1))/eg;

 	# ■クッキー出力
	if($cookie_ok_flag >= 1){
	  print "Set-Cookie: $name=$value; expires=$date\n";     
	}
}
#
#==================================#
# iクッキー用の番号を新規生成
#==================================#
# 番号を入力しなかった人(この場合$memberIDが空で入ってくる)に対して、
# 新番号をここで作成する。これはiクッキー識別に使われる。
# 成功すると$memberIDに代わりの乱数が入る。
# これは既存iクッキー番号と重ならない9998以下の乱数である。
# なおrmkeyがない場合(imodeでは項目を設けないので通常は空),この番号が
# sub post_dataで使われる。つまりrmkeyとしても併用される仕様とする。
# これはユーザの利便性を高めるためである。
sub make_memberID{

     local($new_id_number);
     local($saved_id_number);
	 local($sub_no_used_flag);

	# ■従来のiクッキー番号と重ならない番号を作る
	$exit_flag=0;
	while($exit_flag==0){
		srand(time | $$);

		if(($KEITAI_ENV{'UP_SHORT_SUBNO'} ne "")&&($sub_no_used_flag !=1)){
		# EZには、サブスクライバーナンバーをオススメする
			$new_id_number=$KEITAI_ENV{'UP_SHORT_SUBNO'};
			$sub_no_used_flag=1;
		}else{
		 $new_id_number=int(rand(9998));
		}

		# 暗号化前にとっておく
		$saved_id_number=$new_id_number;
		if($PM{'use_crypt'}==1){
        		$new_id_number = &make_pass("$new_id_number");
		}
		$FORM{'memberID'}="$saved_id_number";
		$exit_flag=1;
	}
	$FORM{'memberID'}="$saved_id_number";

	# ■結果をグローバル変数に代入
	# rmkeyが空の場合(imodeの時は常に空である)、この番号を削除キーにする
	if($rmkey eq ""){
		$FORM{'rmkey'}="$saved_id_number";
 	}
#		&error(" ;aデバック．memberID -$FORM{'memberID'}-rmkey $FORM{'rmkey'}");
	return;
}

#=========================#
# html出力
#=========================#
#
sub output_html{

        # cgi_wrap使用プロバイダ対策
	# 古いプロバイダの中にはcgi_wrapを使っているプロバイダがあります。
	# 相対パス指定を使用する場合、下記の数値を1にして、そのイメージ
	# 保存ディレクトリのURLを$PM{'img_url'}で指定することにより、掲示板を
	# 使用する事ができます。それ以外の人は必ず0に指定してください。
	# なお、1を指定した場合は$PM{'img_url'}の設定が必須になります。   
	$using_cgi_wrap=0;#(デフォルト0)

	# 表示メッセージの初めと終わりを決める
	if($FORM{'page'} > 0){
		if($FORM{'page'} < $PM{'max_message'}){
			$start_message = $FORM{'page'};
		}else{
			$start_message = $PM{'max_message'};
		}
	}else{
		$start_message = 1;
	}

	$last_message = $start_message + $PM{'message_per_page'} - 1;
	if($last_message > $PM{'max_message'}){
		$last_message = $PM{'max_message'};
	}

	# メッセージを読み込む
	&read_file_data("$PM{'file'}");
	# $HEAD_MESSAGE{'name'}にヘッダデータ
	# @MESSAGEに記事ログが入る


	# ワード検索機能
	if($FORM{'mode'} eq "search_menu"){
		&word_search("$FORM{'SearchWords'}","$FORM{'MatchMode'}");
	}

	# レスがついたものを上に
	if($PM{'res_go_up'} == 1 ){
		&res_message_up;
	}


#===========================#
# レスがあったものを上へ
#===========================#

sub res_message_up{

	undef	@TEMP_MESSAGE; 
	undef	@GOUP_MESSAGE;	  # 上へ持って行くメッセージ(ただ抜いたもの)
	undef	@LATEST_MESSAGE;  # 上へ持って行くメッセージ(抜いてソート後)
	undef	@RECENT_MESSAGE_UID;  # 最近登録されたメッセージ

	# @NEW_BLOODS として親スレッド新着順UIDリストを出す
	&output_new_bloods_list;

# Debug
#&error("NEW_BLOODS @NEW_BLOODS");

	undef $tp_match_flag;		# 親UIDを３つまでにするためのフラグ
	local($tp_loop_counter)=0;	# 親UIDを３つまでにするためのカウンタ
	local($pre_tmp_parent);		# 前回抜いた親スレッドUID
	local($match_tp_counter)=0;	# MESSAGEから抜いた親スレッド数

	# リストから順番を変更するスレッドを抜く
	foreach $line_data(@MESSAGE){
		$tp_match_flag = 0;
		if($match_tp_counter < 4){ # 無駄回転を防ぐ
		 $tp_loop_counter=0;
		 foreach $tmp_parent(@NEW_BLOODS){
		  # 3スレッドまで上へ持って行く
		  # それ以上にすると負荷が上がるのでやめる
		  last if($tp_loop_counter >= 3);
		  if($line_data =~ /^([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]*)\t([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)/){
		    if(($tmp_parent eq "$9")||($tmp_parent eq "$11")){

			$tp_match_flag = 1;

			# 前回マッチと親が違う場合はカウントアップ
			if($pre_tmp_parent ne $tmp_parent){
			  $match_tp_counter++;
			}
			$pre_tmp_parent=$tmp_parent;
			last;# 検出したら抜ける
		    }
		  }
		  $tp_loop_counter++;
		 }
		}

		if($tp_match_flag == 1){
		  push(@GOUP_MESSAGE, $line_data);
		  $all_message++;
		}else{
		  push(@TEMP_MESSAGE, $line_data);
	  	  $all_message++;
		}

	}

# Debug
#&error("GOUP_MESSAGE @GOUP_MESSAGE");


	# 抜いたスレッドをソートする

      local($tmp_goup_line);
      $tp_loop_counter=0;
      foreach $tmp_parent(@NEW_BLOODS){
	# 3スレッドまで上へ持って行く
	  last if($tp_loop_counter >= 3);
	  foreach $tmp_goup_line(@GOUP_MESSAGE){
		if($tmp_goup_line =~ /^([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]+)\t([^\t]*)\t([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)\t?([^\t]*)/){
	  	  if(($tmp_parent eq "$9")||($tmp_parent eq "$11")){
			push(@LATEST_MESSAGE,$tmp_goup_line);
	  	  }
	 	}
	  }
	  $tp_loop_counter++;
	}

# Debug
#&error("LATEST_MESSAGE @LATEST_MESSAGE");

	# ソートしたスレッドを@MESSAGEの先端に足す
	unshift(@TEMP_MESSAGE,@LATEST_MESSAGE);
	@MESSAGE=@TEMP_MESSAGE;
	undef @TEMP_MESSAGE; 
}
#
#=================#
# ワード検索機能
#=================#

sub word_search{


	local($search_words)	= $_[0];	# 検索語を引数でもらう
	local($match_mode)	= $_[1];	# 検索タイプ(AND OR)
	local($match_flag);
	local($tmp_parent_seq_no);


	if($search_words ne ""){

	  $search_words =~ s/　/ /g;
	  @tmp_search_words = split(/\s+/, $search_words);

	  undef @TEMP_MESSAGE;

	  foreach $line_data(@MESSAGE){
		undef $match_flag;
		undef %LDATA;
		$tmp_line_data="$line_data";	# 検索するラインを保存

		@SEP_DATA 	= split(/\t/,"$line_data");	# 切断して配列に入れる
		$t=0;
		foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
			$LDATA{$p_key}=$SEP_DATA[$t];
			$t++;
		}
		undef $child_kiji_flag;	# 子記事確認用フラグ
		undef $old_kiji_flag;	# 旧形式確認用フラグ
		if($LDATA{'unq_id'} eq ""){
			$old_kiji_flag="1";	# 旧形式確認用フラグ
		}
		if($LDATA{'blood_name'} ne ""){
			$child_kiji_flag="1";	# 子記事確認用フラグ
		}else{
			$tmp_parent_seq_no="$LDATA{'seq_no'}";
			$parent_counter++
		}

		# 連番 (その記事の親記事をBLOODから調べる連想配列)
		# レスの親表示に使う
		$BLOOD2SEQNO{"$LDATA{'blood_name'}"}="$tmp_parent_seq_no";

		# 検索する項目のエリアを指定する
		@WSAREA=@IM122R6DATA;
		foreach $p_key(@WSAREA){
			$tmp_line_data.="$LDATA{$p_key}"."\t";
		}

		foreach $tmp_search_word(@tmp_search_words) {

			$tmp_enc_search_word=&Enc_EQ("$tmp_search_word");

if(($tmp_search_word =~/http/)&&($tmp_line_data =~/http/)){
#&error("tmp_search_word-$tmp_search_word-tmp_line_data-$tmp_line_data");
}
			if (index($tmp_line_data,$tmp_search_word) >= 0) {
				$match_flag=1;
				if($match_mode eq 'OR') {
				 last;
				}
			}elsif (index($tmp_line_data,$tmp_enc_search_word) >= 0) {
				$match_flag=1;
				if($match_mode eq 'OR') {
				 last;
				}
			}else{
				if ($match_mode eq 'AND') {
				  $match_flag=0;
				  last;
				}
			}
		}

		if($match_flag ==1){	
		   push(@TEMP_MESSAGE, $line_data);
		}
	  }

	  @MESSAGE=@TEMP_MESSAGE;
	  undef @TEMP_MESSAGE; 
	}else{
		&error(" 検索ワードが入力されていません ");
	}
}



	undef @main;			# メモリ解放（しないけど）
	undef %LDATA;
	$all_message=@MESSAGE;		# 全記事数を取得


   # ■お休みモードの設定
	if($PM{'bbs_open'} < 2){
print<<HTML_END;
$HR
管理人の設定により、掲示板の閲覧/書き込み等は一時お休み中となっております。\申\し訳ありません。
HTML_END
exit;
	}


   # ■削除の時の説明を出す

	if($FORM{'page'} >= 2){
 
	  if($FORM{'mode'} eq "remove_select"){
	    print "<BR>[削除MODE] <a href=\"$cgi_name?mode=disp_admin_menu\" accesskey=0>戻る</a><BR>$HR\n"; 
	  }else{

	  print "<HR>\n";

	  }

	}elsif($FORM{'mode'} eq "remove_select"){

	  print "<BR>[削除対象選択]<BR><a href=\"$cgi_name?mode=disp_admin_menu\" accesskey=0>戻る</a><BR>左欄をﾁｪｯｸ(複数指定可),最下欄にﾊﾟｽﾜﾄﾞを入れ、削除ﾎﾞﾀﾝ。\n";  
	  if($PM{'use_rep'} ==1){
	  print "<BR>\n"; 
	  }

          print "<HR>\n";

	}elsif($FORM{'mode'} eq "disp_input_menu"){

   # ■フォーム部分のＨＴＭＬを出力する
	  &output_form_html;
	}else{
          print "<HR>\n";
	}

#====================================#
# フォーム部分のＨＴＭＬを出力する
#====================================#

sub output_form_html{

	# 代入する変数を準備

		# 前処理（埋込みデータを加工）

		# 会員パスワード設定をしてない場合、項目は出さない。
		if($PM{'use_post_password'} != 1){
			$cm_out_pw_h='<!--';
			$cm_out_pw_f='-->';
		}

		# タグを許可する場合、注意書きを追加しミスを予防する。
		if($PM{'use_html_tag_in_comment'} == 1){
			$tag_siyou_tyuui=' ﾀｸﾞ可。閉忘れ注意 ';
		}

		# 返信フォーム時は画像アップロードさせない
		if($FORM{'bbsaction'} eq 'disp_rep_form'){
			$cm_out_img_h='<!--';
			$cm_out_img_f='-->';
		}


	# 入力フォーム部form_htmlのHTMLを出力（書換えは初期設定の所で行う）

	&form_imode_html;	# フォーム(2009.12 imodeに統一した)

	# 入力フォーム下の説明部分を出力（書換えは初期設定の所で行う）

	&middle_A_html;
	&middle_B_html;

}


   # ■ページ変更ボタン

	&disp_button;

   # ■記事を出力する
	&output_kiji_html;

#====================================#
# 記事部分のＨＴＭＬを出力する
#====================================#

sub output_kiji_html{

	local($tmp_ldata);

	# 記事削除指定用のフォーム開始部

	if($FORM{'mode'} eq "remove_select"){
		print"<!-- 記事削除指定用のフォーム開始部 -->\n";
		print"<FORM ACTION=\"$cgi_name\" METHOD =\"$form_method\">\n";
		print"<INPUT TYPE=HIDDEN NAME=\"page\" VALUE=$start_message>\n";
	}

	# 記事部 inu
	for($i=$start_message-1; $i<$last_message; $i++){

	    if($MESSAGE[$i] eq ""){
		next;
	    }
			undef %LDATA;

			@SEP_DATA 	= split(/\t/,"$MESSAGE[$i]");	# 切断して配列に入れる
			$t=0;
			foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
				$LDATA{$p_key}=$SEP_DATA[$t];
				$t++;
			}

			# パラメータの準備
			undef $child_kiji_flag;	# 子記事確認用フラグ
			undef $old_kiji_flag;	# 旧形式確認用フラグ
			undef $new_kiji_flag;	# 最近の記事フラグ


			#	$LDATA{'img_location'}	# 画像の場所
			#	$LDATA{'seq_no'}	# 連番（その記事）
			#	$LDATA{'blood_name'}	# 親の血統ID(子供のみ持つ)
			#	$LDATA{'rmkey'}		# 削除キー
			#	$LDATA{'unq_id'}	# 固有ID(時刻ベース)

			$tmp_rmid		= $i+1;

			# 準備

			if($LDATA{'unq_id'} eq ""){
				$old_kiji_flag="1";	# 旧形式確認用フラグ
			}

			if($LDATA{'blood_name'} ne ""){
				$child_kiji_flag="1";	# 子記事確認用フラグ
			}else{
				$parent_counter++
			}

			# 最新投稿記事にはフラグを立てる
 			if($PM{'disp_new_notice'}==1){
			    $tp_loop_counter=0;
			    foreach $recent_uid(@RECENT_MESSAGE_UID){
 			     last if($tp_loop_counter >= 3 );
 			     if($recent_uid == $LDATA{'unq_id'} ){
				$new_kiji_flag="1";	# 最近の記事フラグ
				last;
			     }
			     $tp_loop_counter++;
			    }
			}

			$LDATA{'subject'}=&Dec_EQ("$LDATA{'subject'}");

			# 画面が狭いimode用では、カタカナは半角にする

			if(($PM{'hankaku_filter'}==1)&&(($keitai_flag eq "imode")||($keitai_flag eq "J-PHONE"))){

			  @HANKAKU_PARA=('subject','name','body','imgtitle');
			  foreach(@HANKAKU_PARA){
			    $tmp_ldata=$LDATA{$_};
			    &jcode'z2h_sjis(*tmp_ldata);
			    $LDATA{$_}="$tmp_ldata";
			    undef $tmp_ldata;
			  }
			}

			# 準備

			undef %IMG_PARAMETERS;

			# imgtitleから情報を抜き出す
			if($LDATA{'imgtitle'} ne ""){
				$LDATA{'imgtitle'}=&parse_img_param("$LDATA{'imgtitle'}");
			}

			# 予備入力項目パラメータを復元
			# bodyの中に、コメントアウト形式でデータは隠し保存されている
			# 書式<!--opt:パラメータ名=値;パラメータ名2=値2・・・-->
			#<!--opt:と-->を除きパラメータ部を抽出する処理
			if($LDATA{'body'} ne ''){
				($LDATA{'body'},$opt_form_data)	=split(/<\!--opt:/,$LDATA{'body'});
				$opt_form_data			=~ s/-->//g;
			}

			#パラメータ$opt_form_dataが追加されている場合．
		        undef %OPTDATA;

			if($opt_form_data ne ''){
				foreach ( split(/;/,$opt_form_data)){
					local($name,$value)	= split(/\=/);
					$value			=&Dec_EQ("$value");
					$OPTDATA{$name}	= $value;
					# 従来パラメータと互換性確保
					if($name=~ /^opt_data_(.+)$/){
						$OPTDATA{"opt$1"}	= $value;
					}
				}
			}

			# 相手のホスト名を変数$user_IP に代入
			# （なりすまし防止などの事情で相手のＩＰを表示したい場合はこの変数を使って下さい）
			if($LDATA{'body'}=~ /user：\s([^>]*)(\s*)--/){
			    $user_IP="$1";
			    $user_IP=&tiny_decode("$user_IP"); #2002.02
			}else{
			    $user_IP="No IP info";
			}

			# 携帯の時はソース見れないので、IP出さない
			$LDATA{'body'}=~ s/<!-- user：\s([^>]*)(\s*)-->//ig;

			# テキストリンク用ＨＴＭＬ指定部に代入する$data_typeを選択
			&define_data_type_disp;

			if($LDATA{'img_location'} ne ''){
				# 画像タイトルがない場合,画像名をタイトルに
				$LDATA{'imgtitle'} = $LDATA{'img_location'} if $LDATA{'imgtitle'} eq '';
				$LDATA{'imgtitle'} =~ s/^(.*)\///;	# パスを消去して名前のみにする
	
			}



			#=============================================================#
			# CGI別ディレクトリサイト、cgiwrapサイト対策(imgboard1.22 Rev.3)
			#=============================================================#

			# 互換性のため
			if($SERVER_NAME eq ""){
				$SERVER_NAME		=$ENV{'SERVER_NAME'};
			}
			if($SERVER_NAME=~ /\.www5(.?)\.biglobe/){
				$using_cgi_wrap=1;
		        }
			if($SERVER_NAME=~ /\.arena\.ne\.jp/){
				$using_cgi_wrap=1;
			}
			if($SERVER_NAME=~ /\.interq\.or\.jp/){
				$using_cgi_wrap=1;
			}
			if($SERVER_NAME=~ /\.coara\.or\.jp/){
				$using_cgi_wrap=1;
			}

			if(($LDATA{'img_location'} =~ /^\/(.+)\/(.+)$/)||($using_cgi_wrap==1)){
				# 絶対パス指定の場合やCGIWRAPサーバの場合はURL指定に変更
				if($using_cgi_wrap==1){
					$LDATA{'img_location'}=~ s/^(.+)\///g;
			    }else{
					$LDATA{'img_location'}=~ s/^\/(.+)\///g;
			    }
				# ある時だけ、継ぎ足す
				if($LDATA{'img_location'} ne ""){
				  $LDATA{'img_location'}="$PM{'img_url'}/$LDATA{'img_location'}";
				}
			}

	if($FORM{'mode'} eq "remove_select"){
	# 削除モード時は記事をかなり短くする
		&cut_long_kiji_for_imode("46");
	}else{
	# imodeで表示する場合に制限長より長い記事を短くする
	  if(($keitai_flag eq "imode")||($keitai_flag eq "J-PHONE")){
		# 初期設定で設定した$PM{'kiji_disp_limit_imode'}より長い場合カット
		if($KEITAI_ENV{'OTHER_PARAM'} eq "FOMA"){
		  # FOMAは余裕があるのでケチケチしない
		  &cut_long_kiji_for_imode("$PM{'kiji_disp_limit_foma'}");
		# Softbank 2009.12 update	
		}elsif($jstation_flag >= 5){
		  # softbankは300K.余裕があるのでケチケチしない
		  &cut_long_kiji_for_imode(($PM{'kiji_disp_limit_foma'}*3));
		}else{
		  # 2004.06.20 add
		  if(($KEITAI_ENV{'CACHE_SIZE'} >= 20)||($ishot_flag == 1)){
		    &cut_long_kiji_for_imode(($PM{'kiji_disp_limit_imode'}*6));
		  # 2004.06.20 J-Phoneパケット機
		  }elsif(($KEITAI_ENV{'CACHE_SIZE'} >= 12)||($jstation_flag >= 3)){
		    &cut_long_kiji_for_imode(($PM{'kiji_disp_limit_imode'}*4));
		  # 2003.03.27 add
		  # 2004.06.20 add au 3G機対策
		  }elsif(($KEITAI_ENV{'CACHE_SIZE'} >= 10)||($ishot_flag == 1)||($au_3G_flag >= 1)){
		    &cut_long_kiji_for_imode(($PM{'kiji_disp_limit_imode'}*3));
		  }else{
		    &cut_long_kiji_for_imode("$PM{'kiji_disp_limit_imode'}");
		  }
		}
	  }
	}


			# 自動URLリンクをする
			if($PM{'auto_url_link'}==1){
				$LDATA{'body'}=&set_auto_url_link($LDATA{'body'});
			}


			# メールアドレスがある場合のみリンクにする
			if($LDATA{'email'} ne " no_email"){
			  $mail_a_start	="<A HREF=\"mailto:$LDATA{'email'}\">";
			  $mail_a_end	="</A>";
			}else{
			  $mail_a_start	="";
			  $mail_a_end	="";
			}

			undef $disp_seq_no;	
			if($LDATA{'seq_no'} ne ""){
				$disp_seq_no="$LDATA{'seq_no'}";
			}

			# 2003.07 add
		  	if($keitai_flag eq "pc"){
			 if ($LDATA{$_} =~ /[\xF8\xF9]/) {
	  			 $LDATA{$_} =&remove_emoji_i("$LDATA{$_}");	 # 2002.05 add
	  		 }
			}

			# 準備終わり


	#------------------------------------------------------------------#
	# 記事部分のＨＴＭＬ(編集は初期設定部でおこなってください)

	undef $disp_re;
	undef $disp_rm_cbox;

	# R6 new
	if($FORM{'mode'} eq "remove_select"){
		undef $mes_rmid;
		# 旧連番SEP新固有IDを送る
		$mes_rmid="rmid"."$tmp_rmid"."S"."$LDATA{'unq_id'}";
		$disp_rm_cbox=qq|<INPUT TYPE="CHECKBOX" NAME="$mes_rmid" VALUE="1">\n|;
	}

	 if($new_kiji_flag ==1){
		$disp_new_kiji=qq| -(new)-<BR>\n|;
	 }else{
	  $disp_new_kiji="";
	 }

	# R6スレッド対応
	if($child_kiji_flag == '1' ){	# 子の場合
		    if($keitai_flag eq "J-PHONE"){
		      # J-PHONEはHRの指定が無効
		      print "<BR>＞＞-----------<BR>\n";
		    }else{
				print "<HR width=\"90%\" color=gray>\n";
		    }
		  print"$disp_rm_cbox";
		  print"$disp_new_kiji";
		# 返信記事（書換えは初期設定の所で行う）
		print"<BLOCKQUOTE>" if($FORM{'mode'} ne "search_menu");
		&kiji_base_html;# １．テキスト記事
		print"</BLOCKQUOTE>" if($FORM{'mode'} ne "search_menu");

	}else{				# 親の場合
		if($old_kiji_flag == '1'){# 旧形式なら返信リンクを出さない
		}else{
		    if($PM{'use_rep'} == 1 ){
			 $disp_re=qq|<a href="$cgi_name?mode=disp_attach_confirm&page=$start_message&viewpass=$FORM{'viewpass'}&blood=$LDATA{'unq_id'}&parent=$LDATA{'seq_no'}">返信</a>|;
		    }
		}
		if($PM{'use_rep'}==1){
			print "<HR size=3 color=gray>\n";
		}else{
			print "<HR color=gray>\n";
		}
		print"$disp_rm_cbox";
		print"$disp_new_kiji";
		&kiji_base_html;# １．テキスト記事
	}

	}# forループの終了


	# フッターを表示

	# 下部にバナー広告を義務付けられている場合は、設定部の$B_BANNER{'2'}にHTMLソースを書いてください    

	$dd_guest_passwd="$PM{'guest_passwd'}";
	$dd_guest_passwd="" if($PM{'use_guest_passwd'} =='-1');
	$dd_guest_passwd="" if(($keitai_flag eq "imode")||($keitai_flag eq "J-PHONE"));

          print "<HR>\n";


	foreach(keys %B_BANNER){
		$b_banner_num++;
	}
	$b_banner_num=1 if($b_banner_num ==0);
	$banner_num=($real_page_num % $b_banner_num);
	$banner_num=$b_banner_num if($banner_num == 0);

print<<HTML_END;
	$B_BANNER{$banner_num}
HTML_END

if($FORM{'mode'} eq "remove_select"){
print<<HTML_END;
        <BR>
	<INPUT TYPE="HIDDEN" NAME="bbsaction" VALUE="remove">
	<BR>
        <INPUT TYPE="password" NAME="passwd" SIZE="6" VALUE="$dd_guest_passwd" istyle="4" MODE=numeric>
	<INPUT TYPE="SUBMIT" VALUE="削除">
	</FORM>
HTML_END
}

		print "<BR>\n";

# 頁変更用ボタンを表示する
&disp_button;

sub disp_button{

	local($last_disp_message)="$last_message";
	local($enc_SearchWords)=$FORM{'SearchWords'};
	local($mes_p1);
	local($mes_p2);

	# 真のページ数をグローバル変数として作っておく
	if($PM{'message_per_page'} >0){
		$real_page_num=int($start_message/$PM{'message_per_page'})+1;
	}else{
		$real_page_num="1";
	}
	# お尻の位置をチェック
	if($last_message > $all_message){
	    $last_disp_message="$all_message";
	}

	# 2010.10 docomoフルブラウザでボタンが画面右端に消える現象に対処
#	print "<center>\n";

	# ワード検索時のページ変更ボタンは検索パラメータを引きずっていく
	if($FORM{'mode'} eq "search_menu"){
		# 空白等でgetのSearchWords引数が切れないようにURLエンコードする
		$enc_SearchWords =~ s/(\W)/'%'.unpack("H2", $1)/ego;
		$mes_p1="&SearchWords=$enc_SearchWords&MatchMode=$FORM{'MatchMode'}";
		$mes_p2="ヒット";

	}else{
		undef $mes_p1;
		undef $mes_p2;
	}


	if($start_message != $last_disp_message){
# 通常
print<<HTML_END;
 &nbsp;&nbsp; $start_message-$last_disp_message/$mes_p2$all_message件中<BR>
HTML_END
	}else{
# １ページに１記事しかない
print<<HTML_END;
 &nbsp;&nbsp; $start_message/$mes_p2$all_message件中<BR>
HTML_END

	}

	if($start_message > 1){
		$pre_message = $start_message - $PM{'message_per_page'};
		$pre_message =1 if $pre_message < 1;
#		$tmp_disp_message=$start_message-1;

	    print " <a href=\"$cgi_name?page=1&mode=$FORM{'mode'}&bbsaction=page_change&viewpass=$FORM{'viewpass'}$mes_p1\"> \<先頭\</a> l\n";
		print " <a href=\"$cgi_name?page=$pre_message&mode=$FORM{'mode'}&bbsaction=page_change&viewpass=$FORM{'viewpass'}$mes_p1\"> 前$PM{'message_per_page'}</a>\n";

		   if($all_message > $last_message){
			print "-\n"; # 前の５件と後ろの５件があるときのseparator
		   }

	}else{
	}

	# 下部のページ切替えボタンのHTML
	if($all_message > $last_message){
		$next_message = $last_message+1;
		$last_disp_message=$last_message+$PM{'message_per_page'};
		if($all_message < $last_disp_message){
			$last_disp_message=$all_message;
		}

		print " <a href=\"$cgi_name?page=$next_message&mode=$FORM{'mode'}&bbsaction=page_change&viewpass=$FORM{'viewpass'}$mes_p1\"> 次$PM{'message_per_page'}\> </a>\n";

	}else{
		print "    \n";
	}
#	print "</center>\n";

} # End of disp_button

	
	# 改造,非改造を問わず,下記クレジットの変更は固くお断りします。（著作権侵害となります）
	# なお,当スクリプトの一部,あるいは全部を利用,あるいは参考にしたスクリプトを作成された場合も,
	# かならず当リンクをその掲示板に付加してください。
	print "<HR>\n";
	print "<DIV ALIGN=\"RIGHT\">";
	print "<A HREF=\"http:\/\/www.big.or.jp\/~talk\/welcome\/welcome_im01.cgi?v=120509\">imgboard2015<\/A>!!<BR>\n";
	print qq|携帯3ｷｬﾘｱ/iPhone/Android/ﾊﾟｿALL対応<BR>\n|;
	print "<\/DIV><BR>\n";

} #????これは何？

		print "<BR><BR><BR>\n"; # 非マルチタッチフォン対応
	print "<\/BODY>\n<\/HTML>\n";
		
} #end of output_kiji_html
#
#======================================================#
# imodeで表示する場合に制限長より長い記事を短くする
#======================================================#
#
sub cut_long_kiji_for_imode{

	
	# 引数 制限長
	local($tmp_imode_kiji_disp_limit)=$_[0];	# 制限長

	local($tmp_limit);
	local($total_kiji_length);

	if($tmp_imode_kiji_disp_limit){
		$tmp_limit="$tmp_imode_kiji_disp_limit";
	}else{
		$tmp_limit='1000';
	}
	$total_kiji_length=length($LDATA{'body'})+length($LDATA{'subject'})+length($LDATA{'name'})+20;
	# 初期設定で設定した制限より長い場合 
	if(($total_kiji_length > ($tmp_limit-10))&&($tmp_limit > 10)){
#&error("aaaa total_kiji_length $total_kiji_length tmp_limit$tmp_limit");

		$LDATA{'body'}=~ s/\<\!--(\s*)user\：\s([^>]*)(\s*)--\>//g;

		# 先頭から指定バイトまでのみ残す
		$LDATA{'body'} =substr("$LDATA{'body'}",0,$tmp_limit);

		if($FORM{'mode'} eq "remove_select"){
		  $LDATA{'body'} .=" ...<font color=green>(以下略)</font></B>";
		}else{
		  $LDATA{'body'} .=" ...<font color=green>(表\示ｵｰﾊﾞ。以下略)</font></B>";
		}
	}

}
#
#===========================================================#
# テキストリンク用ＨＴＭＬ指定部に代入する$data_typeを選択
#===========================================================#
#
sub define_data_type_disp{

	# サムネイルがある場合はそちらを使う
	if($IMG_PARAMETERS{'snl_location'} ne ""){
	 &define_data_type_disp2;
	 return;
	}

	undef $data_type;
	undef $mes_type;
	# 取り扱い可能判定フラグ
	undef $can_handle_flag;# 1=OK,2=NO,3,0=不明
	undef $best_fit_type;	# 推奨タイプ

	if($LDATA{'img_location'} ne ""){

	  if(($keitai_flag eq "imode")||($keitai_flag eq "J-PHONE")){	  
	   # 通常のimodeとSoftbankとau_3Gのケース
	    if($LDATA{'img_location'}=~ /\.bmp$/i){
		$data_type="[PC画像(BMP形式)]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.(png|gif)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[$mes_type画像]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.(jpe?g)$/i){
		 $data_type="[画像(JPEG形式)]";
		 $can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.(asf)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC動画($mes_type形式)]";
		 $can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.m4a$/i){
		# 2005.01 追加
		$data_type="[音声(MPEG-4 AAC形式)]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.m4v$/i){
		# 2009.12 追加
		$data_type="[PC動画(H.264形式)]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.aax?$/i){
		# 2009.12 追加
		$data_type="[iPod音声(audible形式)]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.aiff?$/i){
		# 2009.12 追加
		$data_type="[Apple音声DATA]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.(mp3|midi?|wav)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC音声($mes_type形式)]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.wma?$/i){
		$data_type="[WinMediaAudio]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.wax?$/i){
		$data_type="[WinMediaAudio]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.mp4$/i){
		$data_type="[MPEG-4動画]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.3gp/i){
		$data_type="[iMotion/着ムービ]";
		$can_handle_flag=1;
	    }elsif($LDATA{'img_location'}=~ /\.pdf/i){
		$data_type="[PDF書類]";
		 $can_handle_flag=1;
# 2003.06 追加 2006.12 変更
	    }elsif($LDATA{'img_location'}=~ /\.epub/i){
		$data_type="[epub書類]";
		 $can_handle_flag=1;
# 2009.12 追加
	    }elsif($LDATA{'img_location'}=~ /\.swf$/i){
		$data_type="[Flash形式]";
		$can_handle_flag=1;
# 2006.12 追加
	    }elsif($LDATA{'img_location'}=~ /\.flv$/i){
		$data_type="[Flash Video形式]";
		 $can_handle_flag=1;
# 2002.04 追加
	    }elsif($LDATA{'img_location'}=~ /\.mmf$/i){
		# Jフォン or EZ。互換性はないらしい。
		 $data_type="[SB or EZ着メロ/うた]";
		if($keitai_flag eq "J-PHONE"){
		 $can_handle_flag=1;
		}elsif($au_3G_flag >= 1 ){
		 $can_handle_flag=1;
		}else{
		 $can_handle_flag=2;
		}
	    }elsif($LDATA{'img_location'}=~ /\.txt$|\.html?$/i){
		$data_type="[ﾃｷｽﾄ]";
		$can_handle_flag=1;
	    }else{
		$data_type="[ﾃﾞｰﾀ]";
		$can_handle_flag=1;
	    }
	  }else{
	  # PCから（デモ画面用 半角カタカナを使用しない）
		$can_handle_flag=1;
	    if($LDATA{'img_location'}=~ /\.(png|bmp|gif|jpe?g)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
	   	$mes_type =~ s/JPG/JPEG/;
		$data_type="[$mes_type画像]";
	    }elsif($LDATA{'img_location'}=~ /\.(asf)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC動画($mes_type形式)]";
		# 2009.12 追加
	    }elsif($LDATA{'img_location'}=~ /\.m4a$/i){
		$data_type="[音声(MPEG-4 AAC形式)]";
	    }elsif($LDATA{'img_location'}=~ /\.m4v$/i){
		$data_type="[PC動画(H.264形式)]";
	    }elsif($LDATA{'img_location'}=~ /\.aax?$/i){
		$data_type="[iPod音声(audible形式)]";
	    }elsif($LDATA{'img_location'}=~ /\.aiff?$/i){
		$data_type="[Apple音声DATA]";
	    }elsif($LDATA{'img_location'}=~ /\.(mp3|midi?|wav)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC音声($mes_type形式)]";
	    }elsif($LDATA{'img_location'}=~ /\.wmv$/i){
		$data_type="[WinMediaVideo]";
	    }elsif($LDATA{'img_location'}=~ /\.wma?$/i){
		$data_type="[WinMediaAudio]";
	    }elsif($LDATA{'img_location'}=~ /\.mp4$/i){
		$data_type="[MP4動画]";
	    }elsif($LDATA{'img_location'}=~ /\.mov$/i){
		$data_type="[QuickTime動画]";
	    }elsif($LDATA{'img_location'}=~ /\.3gpp?$/i){
		$data_type="[3gp動画]";
	    }elsif($LDATA{'img_location'}=~ /\.3gp?p?2$/i){
		$data_type="[3g2動画(au)]";
	    }elsif($LDATA{'img_location'}=~ /\.pdf$/i){
		$data_type="[PDF書類]";
# 2008.06追加ここまで
# 2009.12追加
	    }elsif($LDATA{'img_location'}=~ /\.epub$/i){
		$data_type="[epub書類]";
		
	    }elsif($LDATA{'img_location'}=~ /\.swf$/i){
		 $data_type="[Flash形式]";
	    }elsif($LDATA{'img_location'}=~ /\.flv$/i){
		$data_type="[Flash Video形式]";
	    }elsif($LDATA{'img_location'}=~ /\.mld$/i){
		$data_type="[iメロディ]";
	    }elsif($LDATA{'img_location'}=~ /\.txt$|\.html?$/i){
		$data_type="[テキスト]";
	    }else{
		$data_type="[デ\ータ]";
		$can_handle_flag=1;
	    }
	  }
	}
}
#
#===========================================================#
# テキストリンク用ＨＴＭＬ指定部に代入する$data_typeを選択
#===========================================================#
#
sub define_data_type_disp2{

	undef $data_type;
	undef $data_type_icon_ok;

	undef $mes_type;
	undef $tatenaga_flag;

	if($IMG_PARAMETERS{'hw_racio'} > 100){
	  $tatenaga_flag=1;
	}
	if($IMG_PARAMETERS{'exist_snl_type'} ne ""){
	}

	# saru

	# 取り扱い可能判定フラグ
	undef $can_handle_flag;# 1=OK,2=NO,3,0=不明
	undef $best_fit_type;	# 最推奨タイプ
	undef $second_fit_type;	# 次点推奨タイプ
#	undef $third_fit_type;	# 次々点推奨タイプ
	undef $handle_data_line;# 扱える形式を含む文字列
	if($LDATA{'img_location'} eq ""){
		return;
	}

#======= 外部画像の場合==========================================#
	# 画像変換サーバ対応
	if($LDATA{'img_location'}=~ /^http\:\/\//i){
		 $data_type="[外部画像]";
		 $can_handle_flag=1;
		  return;
	}

#======= 画像の場合==========================================#
	if($LDATA{'img_location'} =~ /\.(png|gif|jpe?g|bmp)$/i){

	    $mes_type="$1";
	    $mes_type =~ tr/a-z/A-Z/;
	    $mes_type =~ s/JPG/JPEG/;

	    # iモードはGIFのみ表示できる
	    # 音声はiメロディmld
	    if($keitai_flag eq "imode"){

		$best_fit_type		="jpg-iL-10000";
		$second_fit_type	="jpg-iS-10000";
		$handle_data_line	="gif-jpeg";

		# FOMA対応 (2009.12update シンプルにした)
		if($KEITAI_ENV{'OTHER_PARAM'} eq "FOMA"){
		 $best_fit_type		="jpg-iL-10000";
		 $second_fit_type	="jpg-ps1-10000";
		 $handle_data_line="gif-jpeg";
		# au_3G(XHTML機は、ここで吸収) 2004.06.20
		}elsif($au_3G_flag >= 1 ){
			$best_fit_type		="jpg-iL-10000";
			$second_fit_type	="jpg-ps1-10000";
			$handle_data_line	="gif-jpeg";
		}
		
		$data_type_icon_ok	="&#63714;";


	    }elsif($keitai_flag eq "J-PHONE"){

# http://developers.softbankmobile.co.jp/dp/ 参照

		if($jstation_flag >= 6){
		  # Softbank(300KB)
			$handle_data_line	="jpeg-png-gif"; # mng not support on Softbank
			$best_fit_type		="jpg-iL-20000";
			$second_fit_type	="jpg-iS-20000";
		}elsif($jstation_flag >= 4){
			# パケット機(12KB)
#			$handle_data_line	="jpeg-png-gif";
			$handle_data_line	="jpeg-png"; # 2009.12修正
			$best_fit_type		="jpg-iL-20000";
			$second_fit_type	="jpg-iS-20000";
		}elsif($jstation_flag >= 3){
		  # ステーション機(6KB jpeg-png)
			$handle_data_line	="jpeg-png";
			$best_fit_type	="jpg-iL-6000";
			$second_fit_type	="jpg-ps1-10000";
		}
		$data_type_icon_ok	="";

	    # PC
	    }else{
			$best_fit_type		="jpg-iL-10000";
			$second_fit_type	="jpg-ps1-10000";
			$handle_data_line	="jpeg-gif-png-bmp";
	    }

	# 事前処理終わり

	    @CHECK_DATA_TYPE_DISP=('png','gif','jpe?g','bmp');

	    # 原画像に対して、表示できるならリンクを作る。できないならタイプを説明する
	    foreach $tmp_data_type(@CHECK_DATA_TYPE_DISP){
	      if($LDATA{'img_location'} =~ /\.$tmp_data_type$/i){
		if($handle_data_line =~ /$tmp_data_type/){
			  $data_type="原画";
			  $can_handle_flag=1;
		}else{
			  $data_type="[$tmp_data_type画像]";
			  $data_type=~ s/\?//;
			  $can_handle_flag=2;
		}
	      last;
	      }
	    }

    	    $data_type =~ tr/a-z/A-Z/;
    	    $data_type="$data_type_icon_ok"."$data_type";
 
#=============その他データ=================================#
    }elsif($LDATA{'img_location'}=~ /\.(asf)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC動画($mes_type形式)]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.(mp3|midi?|wav)$/i){
		$mes_type="$1";
	   	$mes_type =~ tr/a-z/A-Z/;
		$data_type="[PC音声($mes_type形式)]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.(mp4)$/i){
		$data_type="[動画(MPEG-4形式)]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.3gp/i){
		$data_type="[動画(iﾓｰｼｮﾝ/EZ形式)]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.wma?$/i){
		$data_type="[WinMediaAudio]";
		$can_handle_flag=1;
# 2003.06 追加
    }elsif($LDATA{'img_location'}=~ /\.swf$/i){
		 $data_type="[Flash形式]";
		 $can_handle_flag=1;
# 2006.12 追加
    }elsif($LDATA{'img_location'}=~ /\.flv$/i){
		$data_type="[Flash Video形式]";
		 $can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.mmf$/i){
		# Jフォン or EZ。互換性はないらしい。
		$data_type="[着メロ(SMAF形式)]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.mld$/i){
		$data_type="[iﾒﾛﾃﾞｨ]";
		$can_handle_flag=1;
    }elsif($LDATA{'img_location'}=~ /\.txt$|\.html?$/i){
		$data_type="[ﾃｷｽﾄ]";
		$can_handle_flag=1;
    }else{
		$data_type="[ﾃﾞｰﾀ]";
		$can_handle_flag=1;
    }




}
#
#=====================================================#
# その他のサブルーチン
#=====================================================#

#=========================#
# フォームのチェック
#=========================#

sub form_check{

	local($crypt_RH)=$REMOTE_HOST;

	foreach $form(sort keys %FORM){

		# フォームの整形
		# タグ禁止の場合
		if($PM{'use_html_tag_in_comment'} !=1){
			$FORM{$form} =~ s/(<|%3C)/&lt;/g;		# タグ禁止
			$FORM{$form} =~ s/(>|%3E)/&gt;/g;		# タグ禁止

			if($FORM{$form}=~ /%3d|%21|%22|%26|%27|\!|\"|\'/){
				$FORM{$form} =~ s/(href|src|style|cookie|documen|alert)/RemovebyImgboardSecurityCheck_XSS_value_word/ig;
			}
			$FORM{$form} =~ s/(onClick|onblur|onchange|onmouse|onError|onload|onfocus|onselect|onsubmit|onunload|onreset|onabort|ondblclick|onkey|ondragdrop)/RemovebyImgboardSecurityCheck_JS/ig;

			# Style指定	禁止
			$FORM{$form} =~ s/style(\s*)=(.|\n)*/
			Sorry..You can not use style in comment./ig;

		}else{
		# タグ許可の場合

# (掲示板イタズラ対策) 各種危険タグを除去

# 2011.12.14 XSS対策で修正
if(($FORM{$form}=~ /</)||($FORM{$form}=~ /%3C/i)||($FORM{$form}=~ />/)||($FORM{$form}=~ /%3E/i)){
# タグがあった場合のみチェックする(高速化)
$FORM{$form} =~ s/<!--(.|\n)*-->//g;			# SSI等	除去
$FORM{$form} =~ s/<IM(A?)G(E?)(\s|\n)*SRC(.|\n)*\.(cgi|pl)(\s*)>/ig
Sorry..You can not load IMG tag CGI in comment./ig;	# IMGタグ CGI	除去
$FORM{$form} =~ s/<(\/?)COMMENT(.|\n)*>(\s*)(\n?)/
Sorry..You can not use COMMENT tag in comment./ig;	# COMMENTタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)FORM(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use FORM tag in comment./ig;		# FORM		除去

# imodeではマーキーが良く使われるということと、Netscapeのシェアが2割以下に
# なっていることから、マーキーを許可することに変更します(2000.4)
#$FORM{$form} =~ s/<(\/?)MARQUEE(.|\n)*>(\s*)(\n?)/
#Sorry..You can not use MARQUEE tag in comment./ig;	# マーキー	除去
$FORM{$form} =~ s/<(\/?)A(.|\n)*tel\:(.|\n)*>(\s*)(\n?)/
Sorry..You can not use auto-tel tag in comment./ig;	# 自動電話アンカー除去
$FORM{$form} =~ s/(<|%3C)(\/?)INPUT(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use FORM element tag in comment./ig;# FORM要素	除去
$FORM{$form} =~ s/(<|%3C)(\/?)SELECT(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use FORM element tag in comment./ig;# SELECTタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)script(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use SCRIPT tag in comment./ig;	# Javascript,VBscript 除去
$FORM{$form} =~ s/(<|%3C)(\/?)OBJECT(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use OBJECT tag in comment./ig;	# OBJECT(ActiveX) 除去
$FORM{$form} =~ s/(<|%3C)(\/?)applet(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use JAVA in comment./ig;		# APPLET 除去
$FORM{$form} =~ s/(<|%3C)META(.+)Refresh(.|\n)*(>|%3E)(\s*)(\n?)//ig;#METAタグ飛ばし禁止
$FORM{$form} =~ s/(<|%3C)(\/?)EMBED(.+)SRC(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use EMBED tag in comment./ig;	# EMBEDタグ	除去
$FORM{$form} =~ s/<(\/?)SERVER(.|\n)*>(\s*)(\n?)/
Sorry..You can not use SERVER tag in comment./ig;	# SERVERタグ	除去
$FORM{$form} =~ s/<(\/?)plaintext(.|\n)*>(\s*)(\n?)/
Sorry..You can not use plaintext tag in comment./ig;	# PLAINTEXTタグ	除去
$FORM{$form} =~ s/<(\/?)xmp(.|\n)*>(\s*)(\n?)/
Sorry..You can not use xmp tag in comment./ig;		# XMPタグ	除去
$FORM{$form} =~ s/<(\/?)strike(.|\n)*>(\s*)(\n?)/
Sorry..You can not use strike tag in comment./ig;	# STRIKEタグ	除去
$FORM{$form} =~ s/<s>/
Sorry..You can not use strike tag in comment./ig;	# STRIKEタグ	除去
$FORM{$form} =~ s/<(\/?)listing(.|\n)*>(\s*)(\n?)/
Sorry..You can not use listing tag in comment./ig;	# LISTINGタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)BODY(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use BODY tag in comment./ig;		# BODYタグ	除去
$FORM{$form} =~ s/<(\/?)TITLE(.|\n)*>(\s*)(\n?)/
Sorry..You can not use TITLE tag in comment./ig;	# TITLEタグ	除去
$FORM{$form} =~ s/<(\/?)BASEFONT(.|\n)*>(\s*)(\n?)/
Sorry..You can not use BASEFONT tag in comment./ig;	# BASEFONTタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)frame(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use FRAME tag in comment./ig;	# FRAMEタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)iframe(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use IFRAME tag in comment./ig;	# IFRAMEタグ	除去
$FORM{$form} =~ s/(<|%3C)(\/?)HTML(.|\n)*(>|%3E)(\s*)(\n?)/
Sorry..You can not use HTML tag in comment./ig;		# HTML閉タグ	除去
$FORM{$form} =~ s/(\/?)COMMENT(.|\n)*>(\s*)(\n?)/
Sorry..You can not use COMMENT tag in comment./ig;	# COMMENTタグ	除去
	}
# タグがあってもなくても調べる
#unless(($form eq "body")||($form eq "subject")||($form eq "viewmode")||($form eq "name")){
if($FORM{$form} =~ /style(\s*)(\=|%3d)(.|\n)*font\-size:(\s*)(\d+)px/){
 if($4 > 48){
$FORM{$form} =~ s/style(\s*)(\=|%3d)(.|\n)*/
Sorry..You can not use style in tag on comment./ig;	# Style指定	禁止
 }
}
if($FORM{$form} =~ /style(\s*)(\=|%3d)(.|\n)*script/){
$FORM{$form} =~ s/style(\s*)(\=|%3d)(.|\n)*script/
Sorry..You can not use style in tag on comment./ig;	# Style指定	禁止
}
# visibility悪用等
if($FORM{$form} =~ /style(\s*)(\=|%3d)(.|\n)*bility/){
$FORM{$form} =~ s/style(\s*)(\=|%3d)(.|\n)*bility/
Sorry..You can not use style in tag on comment./ig;	# Style指定	禁止
}
$FORM{$form} =~ s/(.|\n)*(onClick|onblur|onchange|onmouse|onError|onload|onfocus|onselect|onsubmit|onunload|onreset|onabort|ondblclick|onkey|ondragdrop)(\w{0,8})(\s*)(\=|%3d)/
(imgboardセキュリティ保護システム)Sorry..You can not use char <B><font color=red>$2<\/font><\/B> in comment./ig;	# onClick等javascriptイベントを除去(クロスサイトスクリプティング対策)
#}
#危険タグ除去ここまで
			# IMGタグの埋込みを可否？
			if($PM{'use_img_tag_in_comment'} !=1){
				$FORM{$form} =~ s/<IM(A?)G(E?)(\s|\n)*SRC(.|\n)*>(\s*)(\n?)/Sorry..You can not use IMG tag in comment./ig;#IMGタグ除去
			}else{
			# IMGタグを埋込む場合は外部画像画像であることを明記する。
				if(($form eq 'body')&&($FORM{$form}=~ /<IMA?GE?(\s)*SRC(.*)>/i)){
					$FORM{$form} =~ s/ALT(\s)*=(\s)*\"(.+)\"/ /ig;	#ALT除去
					$FORM{$form} =~ s/ALT(\s)*=(\s)*([^>]+)/ /ig;	#ALT除去
					$FORM{$form} =~ s/border(\s)*=(\s)*([^>]+)/ /ig;#Border除去
					$FORM{$form} =~ s/<IMA?GE?\s*SRC\s?=\s*(\S*)(\s*)>/<IMG SRC=$1 ALT="この画像は外部ＷＷＷサーバの画像です" Border=0>外部画像 /ig;
				}
			}
		}
		$FORM{$form} =~ s/\r//g;		#CR除去
		$FORM{$form} =~ s/\n/<BR>/g;		#LFを<BR>に
		$FORM{$form} =~ s/\t//g;		#TABの除去
	}

	# フォームの値を代入
	$name      	= "$FORM{'name'}";
	$email     	= "$FORM{'email'}";
	$subject   	= "$FORM{'subject'}";
	$body      	= "$FORM{'body'}";
	$rmkey		= "$FORM{'rmkey'}";
	$imgtitle 	= "$FORM{'imgtitle'}";
	$img_location	= "$PM{'img_dir'}/$new_fname" if $new_fname ne '';
	$viewmode	= "$FORM{'viewmode'}";
	$memberID	= "$FORM{'memberID'}";

	#<フォームの有無のチェック>
	# 基本的にチェックする。ただし、プロファイル登録だけを行う
	# ユーザの場合は名前やemailをチェックしない。
	if($FORM{'bbsaction'} ne 'pf_change'){
		&check_form_data_exist;
	}
        # 各パラメータは空にならないようにする
	$name	 =' 無名 '      if $name eq '';
	$email   =' no_email'   if $email eq '';
	$subject =' 無題 '      if $subject eq '';
	$body    =' 本文なし '  if $body eq '';
	$rmkey  ='no_key'  	if $rmkey eq '';
	$memberID='9999'	if $memberID eq '';

        # 新パラメータ
	if(($keitai_flag ne "pc")||($HTTP_USER_AGENT=~ /iPhone|iPod|iPad|android/i)){
	  $FORM{'optKeitaiFlag'}			="$keitai_flag";
	  $FORM{'optKeitaiServiceCompany'}	="$KEITAI_ENV{'SERVICE_COMPANY'}";
	  $FORM{'optKeitaiHttpVersion'}		="$KEITAI_ENV{'HTTP_VERSION'}";
	  $FORM{'optKeitaiMachineType'}		="$KEITAI_ENV{'MACHINE_TYPE'}";
	  $FORM{'optKeitaiOtherParam'}		="$KEITAI_ENV{'OTHER_PARAM'}";
	  $FORM{'optKeitaiMelodyType'}		="$KEITAI_ENV{'MELODY_TYPE'}";
	}

# 追加項目に未記入の場合のデフォルト値は以下の書き方を参考にしてください
#	$FORM{'optA'} =' 無題 '      if $FORM{'optA'} eq '';

	# 本文にユーザ情報を含める
	# 暗号化
	if($KEITAI_ENV{'SERIAL_LONG'} ne ""){
		$crypt_RH="$KEITAI_ENV{'SERIAL_LONG'}"."-KSN-"."$KEITAI_ENV{'SERIAL_SHORT'}";
	}
 	if($crypt_RH ne ""){
		$crypt_RH=&tiny_encode("$crypt_RH");
	}
	$body    = "$body<!-- user： $crypt_RH-->";


        # いたずら防止 (99/12/01 追加分)
        $email   =~ s/"/&quot;/g;
        $email   =~ s/style(\s*)=(.|\n)*//ig;

		# 2011.09 youbu.be埋め込み二つならアボート
		if($body=~ /youtu\.be(.|\n)*youtu\.be/i){
			&error(" エラー。youtu.beのURLは複数埋め込みできません。ひとつにしてください。 ");
		}
	
	if($PM{'use_trip_flag'}==1){

		# 修正記事選択画面なら偽判定しない
		if(($FORM{'amode'} eq "select_edit")&&($FORM{'bbsaction'} ne "edit_form")){
		# 通常なら偽判定する
		}else{
	  		$name =~ s/◆/◇偽/g; # 偽物は白◇にする
		}

		#2010.02 trip機能(なりすまし防止)をつける
		if($name=~ /^(.+)\#(.+)$/g){
			$name		=$1;
			$trip_plain =$2;
			$trip_plain 	=~ s/　//g;# 全角フィルタ
			$trip_plain 	=~ s/\s//g;
 		
			if($trip_plain ne ""){
		 		$trip_plain=substr($trip_plain,1,8);
		 	$salt=substr($trip_plain,2,2);
		 	$salt =~ s/[^\.-z]/\./go;
		 	$salt =~ tr/:;<=>?@[\\]^_`/ABCDEFGabcdef/;
		 	$trip = crypt($trip_plain,$salt);
		 	$trip = substr($trip,-5);
		 	$trip = '◆'."$trip";
		 	$name="$name"."$trip";
#&error("$name");
			}
		}
	}
	
	undef $p_key;	
	foreach $p_key(keys %FORM){
		if($p_key=~ /opt_data/){
			$FORM{$p_key}=~ s/style(\s*)=(.|\n)*//ig;
			$FORM{$p_key}=~ s/"/&quot;/g;
		}
	}


}
#=======================#
# パスワード整合チェック
#=======================#
#
# 引数1 = チェックしたいパスワード
# 引数2 = 暗号化済み（かもしれない）パスワード
# 返値  = 一致=1, 不一致=0
sub check_pass{

	local($guess, $pass) = @_;
	local($crypt_guess);
	local($crypt_pass);

	$crypt_guess	=&make_pass("$guess");
	$crypt_pass	=&make_pass("$pass");

	if($crypt_guess eq "$crypt_pass"){
		return(1);
	}else{
		return(0); # 外れたら失敗
	}
}
#
#========================#
# 暗号化パスワードを作成
#========================#
#
sub make_pass{

	local($plain,$cvar) = @_;# 引数
	local($salt);
	local($tmp_pass);

	# ワンタイム型
	if($cvar eq "MACHINE_TYPE"){
	  if($KEITAI_ENV{'MACHINE_TYPE'} ne ""){
	    $salt="$KEITAI_ENV{'MACHINE_TYPE'}"."$plain";
	    $salt =substr("$salt",3,10);
	  }else{
	    @TMP_IP_ADDR=split(/\./,"$ENV{'REMOTE_ADDR'}");
	    $salt="$TMP_IP_ADDR[0]"."$plain";
	  }
	# その他
	}else{
	    $salt="$ENV{'PROCESSOR_REVISION'}"."$plain";
	}
#&error("aa- $salt");
	if($plain=~ /^ZzZ/){	# 2重暗号化を防ぐ
		$tmp_pass = "$plain";
	}else{
		$tmp_pass = crypt($plain, $salt);
		$tmp_pass = "ZzZ"."$tmp_pass";
	}
	return ($tmp_pass);

}
#
sub tiny_encode{
	local($plain) = @_;# 引数
	 return($plain) if($plain=~ /\,/);
  	 $plain =~ s/n/\,/ig;
    	 $plain =~ tr/a-m/b-n/;
   	 $plain =~ tr/A-M/B-N/; # 2002.12 自宅サーバ対応で追加
  	 $plain =~ s/\,/a/ig;
   	 $plain =~ s/4/\,/g;
    	 $plain =~ tr/0-3/1-4/;
  	 $plain =~ s/\,/0/g;
 	 $plain ="T-Enc"."$plain";
	 return($plain);
}

sub tiny_decode{
	local($plain) = @_;# 引数
	 if($plain=~ /T-Enc(.*)$/){
	  $plain = $1;
	  $plain =~ s/a/\,/ig;
    	  $plain =~ tr/b-n/a-m/;
   	  $plain =~ tr/B-N/A-M/; # 2002.12 自宅サーバ対応で追加
  	  $plain =~ s/\,/n/ig;
   	  $plain =~ s/0/\,/g;
    	  $plain =~ tr/1-4/0-3/;
  	  $plain =~ s/\,/4/g;
	 }
	 return($plain);
}
#
#=========================================================#
#   <あるunq_idの記事を一つ呼び出す（記事修正機能用）>    #
#=========================================================#
#
sub load_target_data{

	local($t_pattern)=$FORM{'target'};
	local($found_number)=0;

	undef @T_MESSAGE;
	local ($t_message);

	# データ読込み
	&read_file_data("$PM{'file'}");

	undef $match_count;
	undef @SEP_DATA;
	foreach (@MESSAGE){

		$tmpdata 	= $_;	# 全体データを保存
		@SEP_DATA 	= split(/\t/,"$_");	# 切断して配列に入れる

	#	@IM122R6DATA=('subject','name','email','date','body','img_location','imgtitle','seq_no','blood_name','rmkey','unq_id','permit','other');

		$i=0;
		foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
			$LDATA{$p_key}=$SEP_DATA[$i];
			$i++;
		}

		if($LDATA{'unq_id'} eq "$t_pattern"){
				push(@T_MESSAGE, $_);
				last; # ループから抜ける
		}
	}

	$found_number=@T_MESSAGE;

	if($found_number != 1){
		&error(" ターゲットデ\ータ探索異常。探索unq_id $t_pattern 発見数$found_number");
	}

	# ターゲットのデータをクッキーに代入してフォームに表示して見せる

	$LDATA{'subject'}=&Dec_EQ("$LDATA{'subject'}");

	# 予備入力項目パラメータを復元
	# bodyの中に、コメントアウト形式でデータは隠し保存されている
	# 書式<!--opt:パラメータ名=値;パラメータ名2=値2・・・-->
	#<!--opt:と-->を除きパラメータ部を抽出する処理
	if($LDATA{'body'} ne ''){
		($LDATA{'body'},$opt_form_data)	=split(/<\!--opt:/,$LDATA{'body'});
		$opt_form_data			=~ s/-->//g;
	}

	$LDATA{'body'}=~ s/\<!-- user：\s([^>]*)(\s*)--\>//g;

	#パラメータ$opt_form_dataが追加されている場合．
         undef %OPTDATA;

	if($opt_form_data ne ''){
		foreach ( split(/;/,$opt_form_data)){
			local($name,$value) = split(/\=/);
			$value=&Dec_EQ("$value");
			$OPTDATA{$name}	= $value;
			$COOKIE{$name}		="$value";
		}
	}
	$COOKIE{'subject'}	="$LDATA{'subject'}";
	$COOKIE{'name'}		="$LDATA{'name'}";
	$COOKIE{'email'}	="$LDATA{'email'}";
	$COOKIE{'body'}		="$LDATA{'body'}";
}
#
#=========================================================#
#   <あるblood_nameの記事群を呼び出しバッファに入れる>    #
#=========================================================#
#
# 返信時の参照記事を表示するために使う
sub load_family_data{

# 引数は親の unq_id(blood_name)で有り、そのblood_nameを持つ記事は
# @T_MESSAGEに入って返される。

	local($t_pattern)=$FORM{'target'};
	local($found_number)=0;
	local($tmp_find_flag)=0;

	undef @T_MESSAGE;
	local ($t_message);

	# データ読込み
	&read_file_data("$PM{'file'}");

	undef $match_count;
	undef @SEP_DATA;
	foreach (@MESSAGE){

		$tmpdata 	= $_;	# 全体データを保存
		@SEP_DATA 	= split(/\t/,"$_");	# 切断して配列に入れる

	#	@IM122R6DATA=('subject','name','email','date','body','img_location','imgtitle','seq_no','blood_name','rmkey','unq_id','permit','other');

		$i=0;
		foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
			$LDATA{$p_key}=$SEP_DATA[$i];
			$i++;
		}

		if(($tmp_find_flag==0)&&($LDATA{'unq_id'} eq "$t_pattern")){
			$tmp_find_flag=1;
			push(@T_MESSAGE, $_);
		}elsif(($tmp_find_flag==1)&&($LDATA{'blood_name'} eq "$t_pattern")){
				push(@T_MESSAGE, $_);
		}
	}

	$found_number=@T_MESSAGE;
	return($found_number);
}
#
#====================#
# METHODのチェック
#====================#
#
# GETに投稿を受け付けない。(ただしJSKYのみ可能)
# あと$PM{'no_upload_from_pc'} ==1の場合はPCから投稿させない。
#
sub check_form_method{

	local($mes_p1,$mes_p2)=@_;	# 引数 エラーメッセージ

  	if($ENV{'REQUEST_METHOD'} ne 'POST'){
		# GETの時
	  	if(($keitai_flag eq "J-PHONE")&&($jstation_flag < 3)){
		#  Ｊフォンでかつ、ＪＳＫＹの時だけ特別にGETを認める
		}else{
		  &error("$mes_p1<BR>$mes_p2");
		}
	}
	# $PM{'no_upload_from_pc'} ==1の場合はPCから投稿させない。
	&check_upload_from_pc;

}
#
#====================#
# PCチェック
#====================#
#
# $PM{'no_upload_from_pc'} ==1の場合はPCから投稿させない。
#
sub check_upload_from_pc{

	if($PM{'no_upload_from_pc'} ==1){
	  	if(($keitai_flag eq "imode")||($keitai_flag eq "J-PHONE")||($ENV{'REMOTE_ADDR'} eq "219.119.113.35")){
			# 投稿できます
		}else{
			&error(" 携帯アクセスCGIのエラー。ＰＣからの書き込みはできません。<BR> 現在の設定では、携帯からしか投稿を受け付けないようになっています。<BR> PCから書き込みする場合は管理者に<a href=\"$PM{'cgi_hontai_name'}\">imgboard本体($PM{'cgi_hontai_name'})</a>のアクセス先URLを聞き、そのURLから書き込みしてください ");
		}
	}else{
		# 投稿できます
	}
}
#
#====================#
# 記事データの修正
#====================#

sub replace_data{

	local($target_tid)=@_;	# 引数 ターゲットのID
	local($tmp_rmkey);	# 記事に設定されていた削除キー
	local($tmp_crypt_rmkey)=$FORM{'rmkey'};	# 暗号化する削除キー

	if($PM{'use_crypt'} ==1 ){
		$tmp_crypt_rmkey=&make_pass($tmp_crypt_rmkey);
	}

	# ●セキュリティチェック
 	&check_form_method(" セキュリティ警告 "," GETによる書き込みは受け付けません ");

	# ●フォームの内容をチェック
	&form_check;

	if($error_message ne ''){
		&set_cookies;		# クッキーをセット(120Rev5以降)
		&error($error_message);
		exit;
	}

	# セパレータとして問題あるものを、事前に置換
	$subject=&Enc_EQ("$subject");

	undef $tmp_data;

	foreach $p_key(keys %FORM){
		if($p_key=~ /^opt(.+)$/){
			$tmp_data=&Enc_EQ($FORM{$p_key});
			$opt_data.="opt_data_"."$1"."\="."$tmp_data"."\;";
			undef $tmp_data;
		}
	}

	$all_message=0;

	# データ読込み
	&read_file_data("$PM{'file'}");

	undef $match_count;
	undef @SEP_DATA;
	foreach (@MESSAGE){

	       if(($_=~ /$target_tid/)&&($match_count < 1)){

			$match_count++;

			undef @SEP_DATA;
			undef %LDATA;

#	@IM122R6DATA=('subject','name','email','date','body','img_location','imgtitle','seq_no','blood_name','rmkey','unq_id','permit','other');

			$tmpdata 	= $_;	# 全体データを保存

			@SEP_DATA 	= split(/\t/,"$_");	# 切断して配列に入れる

			$i=0;
			foreach $p_key(@IM122R6DATA){ # init_valiablesで定義
				$LDATA{$p_key}=$SEP_DATA[$i];
				$i++;
			}

	# ●新しいメッセージを作る（imgboard1.22R6.1新形式）
#	$new_message = "$subject\t$name\t$email\t$date_data\t$body<\!--opt\:$opt_data-->\t$img_location\t$imgtitle<\!--dsize=$img_data_size;type=$IMGSIZE{'type'};width=$IMGSIZE{'width'};height=$IMGSIZE{'height'};hw_racio=$IMGSIZE{'hw_racio'};-->\t$new_seq_no\t$FORM{'blood'}\t$rmkey\t$unq_id\t$permit\t$other";


#&error("$subject $target_tid mc $match_count 9 $LDATA{'rmkey'}");

			# 上書きするものはここで上書き代入
			# 前のデータ保存をそのまま保存するものはコメントアウト

			$LDATA{'subject'}	="$subject";
			$LDATA{'name'}		="$name";
			$LDATA{'email'}		="$email";
#			$LDATA{'date'}		="$date_data";
			$LDATA{'body'}		="$body<\!--opt\:$opt_data-->";
#			$LDATA{'img_location'}	="$img_location";
#			$LDATA{'imgtitle'}	="$imgtitle<\!--dsize=$img_data_size;type=$img_type;width=$img_width;height=$img_height;hw_racio=$img_hw_racio;-->";
#			$LDATA{'seq_no'}	="$new_seq_no";
#			$LDATA{'blood_name'}	="$FORM{'blood'}";
#			$LDATA{'rmkey'}		="$rmkey";
#			$LDATA{'unq_id'}	="$unq_id";
#			$LDATA{'permit'}	="";
#			$LDATA{'other'}		="";

			# 結合して復元する
			foreach(@IM122R6DATA){
				$new_message.="$LDATA{$_}"."\t";
			}
			push(@TMPMESSAGE, $new_message);
		}else{
			push(@TMPMESSAGE, $tmpdata);
		}# end of if

	} #end of foreach

	$tmp_rmkey="$LDATA{'rmkey'}";

	# パスワードをチェック
	if(($tmp_rmkey eq "$FORM{'rmkey'}")||($PM{'admin_passwd'} eq "$FORM{'rmkey'}")||($tmp_rmkey eq "$tmp_crypt_rmkey")||($PM{'admin_passwd'} eq "$tmp_crypt_rmkey")){
#		&error("一致しました。既削除キー $tmp_rmkey 入力されたパスワード$FORM{'rmkey'} ");
	}else{
		&error("パスワードが違います。","記事の修正には投稿時に入力したパスワードが必要です。<BR>再度パスワードを入力してください ");
	}


	# データ書き出し開始
	# 書き出し前にバッファに入れる
	undef @TMP_MESSAGE;

	@TMP_MESSAGE	=@TMPMESSAGE;

	# 書き出し処理
	&write_file_data("$PM{'file'}");
#&error("$subject $target_tid mc $match_count");

}

#===============================#
# フォームの入力項目のチェック
#===============================#

sub check_form_data_exist{
#

	if(($PM{'form_check_name'}==1)&&($name eq '')){
		$error_message .= "名前がありません。<BR>";
	}
	if(($PM{'form_check_email'}==1)&&($email eq '')){
	  if($filter_bbs_spam==1){
		# 2006.04 SPAM対策の影響
		$error_message .= "設定エラー。メール,URL禁止SPAM対策時はemailは必須にできません。<BR>";
	  }else{
		$error_message .= "emailがありません。現在の設定ではemailは必須項目となっています。<BR>";
	  }
	}
	if(($PM{'form_check_subject'}==1)&&($subject eq '')){
		$error_message .= "題名がありません。<BR>";
	}
	if(($PM{'form_check_body'}==1)&&($body eq '')){
		$error_message .= "本文がありません。<BR>";
	}
	if(($PM{'form_check_img'}==1)&&($img_data_exists != '1')){
		$error_message .= "添付画像がありません。<BR>";
	}
	if(($PM{'form_check_rmkey'}==1)&&($rmkey eq '')){
		$error_message .= "削除キーがありません。<BR>";
	}
# 追加項目に未記入の場合の警告メッセージは、以下の「optX」等の部分を適宜
# 書き換えてください

	if(($PM{'form_check_optA'}==1)&&($FORM{'optA'} eq '')){
		$error_message .= "optA がありません。<BR>";# 予備
	}
	if(($PM{'form_check_optB'}==1)&&($FORM{'optB'} eq '')){
		$error_message .= "optB がありません。<BR>";# 予備
	}
	if(($PM{'form_check_optC'}==1)&&($FORM{'optC'} eq '')){
		$error_message .= "optC がありません。<BR>";# 予備
	}
	if(($PM{'form_check_optD'}==1)&&($FORM{'optD'} eq '')){
#		$error_message .= " アイコン選択がありません。<BR>";# 予備
	}
	if(($PM{'form_check_optE'}==1)&&($FORM{'optE'} eq '')){
		$error_message .= " 携帯番号がありません。<BR>";# 予備
	}
	if(($PM{'form_check_optF'}==1)&&($FORM{'optF'} eq '')){
		$error_message .= "optF がありません。<BR>";# 予備
	}
}

#==========================================#
# フォームの入力項目の省略可・必須を自動表示
#==========================================#

sub auto_omit_disp{

	# パラメータデフォルトを指定
	if($PM{'auto_disp_omit_frag'} ne '1'){
		$PM{'auto_disp_omit_frag'}=0;
	}

	local($html_h)="*"; # 必須の場合
	local($html_s)="";  # 省略可能な場合

	if($PM{'auto_disp_omit_frag'} eq "1"){

		foreach(keys %PM){
		    if($_ =~ /^form_check_(.+)$/){
			if($PM{$_}==1){
			  $DISP_OMIT{$1} .="$html_h";
		    	}else{
			  $DISP_OMIT{$1} .="$html_s";
		        }
		    }
		}
	}
}
#
#============================#
# 登録会員キーチェック
#============================#
#
sub check_entrypass{

	local($tmp_entrypass)=$FORM{'entrypass'};
 
	if($PM{'use_crypt'} == 1){
		$tmp_entrypass=&make_pass("$tmp_entrypass");
	}

	# ２バイト文字が入り、コンパイルエラーになる現象を防ぐ
	if($FORM{'entrypass'}=~ /[\x80-\x9f\xe0-\xff]/){
	  $FORM{'entrypass'}="";
	  &error(" エラー 会員パスに日本語は使えません "," 4ケタ以内の半角数字に限られます ");
	}


	# 会員キーチェック
	if($PM{'use_post_password'}==1){
	 if($FORM{'entrypass'} eq "$PM{'post_passwd'}"){
		return;	# OK
	 }elsif($tmp_entrypass eq "$PM{'post_passwd'}"){
		return;	# OK
	 }else{
		&error(" 会員ﾊﾟｽﾜﾄﾞ（数字）が違います．投稿できませんでした．<BR>(詳細)携帯からの投稿の場合、ユーザを特定する手段がないため、掲示板に対する連続投稿いたずら等を防げません。そのため、携帯からの書き込みには会員パスワドが必須となっています。携帯ユーザの方は、掲示板管理者から会員パスワドを教えてもらってください ");
	 }
	}
}

sub protect_from_NON_member{
#
# （閲覧会員限定）

	local($w_pattern);
#	local($tt_member_true_flag)=0;
	local($tt_member_ok_flag)=0;

# <追加の閲覧パスワード>
#  閲覧パスワードを増やしたい場合はここに列記することにより増やすことができる。
#  なお、１項目目の$PM{'view_passwd'}は消さないこと。
@VIEW_MEMBER_PASSWD=("$PM{'view_passwd'}","","","","","");

#	&read_cookie;# クッキーを読込む(パスの関係でPOST渡しするが、その場合は読まれないため)

	# 閲覧パスワードチェック

	# 通常の場合はクッキーからもらう

	if($PM{'use_crypt'} == 1){
		$FORM{'viewpass'}=&make_pass("$FORM{'viewpass'}",'MACHINE_TYPE');
	}

	# ２バイト文字が入り、コンパイルエラーになる現象を防ぐ
	if($FORM{'viewpass'}=~ /[\x80-\x9f\xe0-\xff]/){
	  $FORM{'viewpass'}="";
	  &error(" エラー 会員パスに日本語は使えません "," 4ケタ以内の半角数字に限られます ");
	}

	# 投稿パスを知っている人もOK
	if(&check_view_passwd($FORM{'viewpass'},'MACHINE_TYPE')==5){
		$tt_member_ok_flag=1;
	}

	# メンバーでないときは、パスワード入力画面にする
	if($tt_member_ok_flag!=1){
		&output_Content_type;
		&top_html;
		&output_view_member_check_HTML;
		exit;
	}
}

sub check_view_passwd{
#
# 引数: チェックしたいパスワード
# 結果: 一致すると５が返る

	local($t_input_passwd)=$_[0];
	local($t_cvar)   =$_[1];	# saltのヒント
	local($w_pattern);

	return 0 if($t_input_passwd eq "");

	foreach (@VIEW_MEMBER_PASSWD){
	    $w_pattern="$_";
	    if($w_pattern ne ""){
	     if(&check_passwd("$t_input_passwd","$w_pattern","0","$t_cvar")==1){
		return 5;
	     }
	    }
	}
}
#
#============================#
# パスワード照合
#============================#
# 2002.04.01 UPDATE
# 引数1,2を照合する。一致なら1,不一致なら2が返る
sub check_passwd{

	local($cp1_passwd)=$_[0];	# 引数1として取得
	local($cp2_passwd)=$_[1];	# 引数2として取得
	local($match_level)=$_[2];	# 厳密さ（１なら厳密）
	local($tmp_cvar)   =$_[3];	# saltのヒント
	local($cpt_cp1_passwd);		# 引数1を暗号化したもの
	local($cpt_cp2_passwd);		# 引数2を暗号化したもの

	$cpt_cp1_passwd=&make_pass($cp1_passwd,$tmp_cvar);
	$cpt_cp2_passwd=&make_pass($cp2_passwd,$tmp_cvar);

	# パスワード照合
	# 厳密
	if($match_level == 1){
	 if($cp1_passwd eq "$cp2_passwd"){
		return 1;
	 }else{
		return 2;
	 }
	}

	# 普通
	if($cp1_passwd eq "$cp2_passwd"){
		return 1;
	}elsif($cp1_passwd eq "$cpt_cp2_passwd"){
		return 1;
	}elsif($cpt_cp1_passwd eq "$cp2_passwd"){
		return 1 ;
	}elsif($cpt_cp1_passwd eq "$cpt_cp2_passwd"){
		return 1 ;
	}else{
		return 2;
	}
}
#
#=======================================#
# 掲示板荒し対策２(1.22Rev6 機能強化版)
#=======================================#

sub protect_from_BBS_cracker{
#
# （悪質掲示板荒らし対策です）
#
# 大幅機能追加(1.22 Rev4)
# 名前、禁止単語による制限機能を追加しました。ホスト名を頻繁に変更する
# 相手等、高度な「荒し技」を持つ相手からのイタズラが続く場合に、これを
# 使ってください。 リストは初期設定のところにあります。

	undef $bad_user_flag;
	local($error_mes_bl);
	local($error_mes_type);
	local($w_pattern);

	# デフォルトのダミーエラーメッセージ
	$error_mes_bl="CGI error 223458 BLT Default";


	#外部のブラックリストファイル（禁単語）を読込む
	if(($use_ext_blacklist ==1)&&($PM{'no_upload_by_black_word'}==1)){
		$add_black_word_count=&load_ext_list('blkword.txt','BLACK_WORD');
	}

	#外部のスパムリストファイル（ホスト名）を読込む
	if($use_ext_spamlist ==1){
	  $add_spam_count=&load_ext_list('spamlist.cgi','SPAM_HOSTS_IP');
	}

	#外部のスパムリストファイル（禁単語）を読込む
	if($use_ext_spamlist ==1){
	  $add_spam_word_count=&load_ext_list('spamword.cgi','SPAM_WORD');
	}

 	# 投稿時以外(view時など)は、ホスト名以外のフィルタはスキップして負荷軽減（ここから）
	if($FORM{'bbsaction'} eq 'post'){

	# 2006.03 add 掲示板SPAM対策
	if($limit_bbs_spam_flag==1){
		if($FORM{'sf'} eq "$spam_keyword"){
		}else{
			&error(" CGIエラー．投稿できませんでした． ");
		}

		# 2010.02 onetime_tokenによるSPAM対策
		if($FORM{'onetime_token'} eq "$uniq_token"){
#&error("同じtoken  ttmp_uniq_char $ttmp_uniq_char--$FORM{'onetime_token'} - $uniq_token");
		}elsif($FORM{'onetime_token'} eq "$uniq_token_old"){
#&error("一つ前token ttmp_uniq_char $ttmp_uniq_char--$FORM{'onetime_token'} - $uniq_token");
		}else{
			if($PM{'make_bbs_html_top'}!=1){
#&error("CGIエラー tokenが時間切れになりました。ttmp_uniq_char $ttmp_uniq_char--$FORM{'onetime_token'} - $uniq_token");
			&error(" CGIエラー．tokenが時間切れになり、投稿できませんでした．SPAM対策のため投稿は、24時間以内に記入し、投稿してください ");
			}
		}

	}

	# 2006.03 add 掲示板SPAM対策
	if($filter_bbs_spam==1){
		$PM{'no_upload_by_black_word'}=1;
		push(@BLACK_WORD,"tp:");
		push(@BLACK_WORD,"\@");
		push(@BLACK_WORD,"ｔｔｐ ");
		push(@BLACK_WORD,"\[url=");# 2007.05 追加
#		push(@BLACK_WORD," ＠ ");
	}

	# 2008.06 ニコニコの仕様変更に対応 2011.06修正
	if($FORM{'body'}=~ /iframe.*src="http:\/\/([\-a-zA-Z0-9]+)\.nicovideo\.jp\/thumb\/([\-a-zA-Z0-9_]+)"/i){
	 if($PM{'auto_nicovideo_find'}==1){
		&error(" 操作エラー。ニコニコ動画のリンクはIFRAMEタグを使わず、 http://www.nicovideo.jp/watch/$2 と本文にURLだけを記載すると、自動的にきちんと埋め込み表\示\されます。同記載方法に変更し、再投稿してください ","","1");
	 }else{
		&error(" 操作エラー。IFRAMEタグはセキュリティ上問題あるため、本文中に使えません。","","1");
	 }
	}

	# 2009.12
	if($FORM{'body'}=~ /src\=\"http\:\/\/www\.dailymotion\.com/i){
		$PM{'spam_url_link_limit_2'}=0;
		$PM{'spam_url_link_limit_3'}=0;
		$PM{'spam_url_link_limit_4'}=0;
		$PM{'spam_url_link_limit_5'}=1;
	}


	# エラーで出る説明で誘導する
	if(($allow_nicovideo_in_res == 0)&&($FORM{'prebbsaction'} eq "disp_rep_form")){
	 if($FORM{'body'}=~ /ttp:\/\/([\-a-zA-Z0-9]+)\.nicovideo\.jp\/watch\//i){
		&error(" ユーザー操作エラー。現在、返信記事にはニコニコ動画URLの埋め込みはできない設定になっています。<BR>動画埋め込みは、親記事でおこなってください。 ","","1");
	 }
	}

	# 既に検出している場合はスキップして高速化
	if(($bad_user_flag!=1)&&($PM{'no_upload_by_black_word'}==1)){

	  foreach (@BLACK_WORD){

	    $w_pattern="$_";
	    $w_pattern=~ s/\s//g;
	    $w_pattern=~ s/　//g;

	    if($w_pattern ne ""){
		$blkw_count++;
		#記事すべての項目をチェックする
		local(@ALL_ITEM)=('body','name','subject','email','imgtitle','optA');
		local($ttt_form)="";
		foreach $form(@ALL_ITEM){
		        $ttt_form = $FORM{"$form"};
			$ttt_form =~ s/\s//g;
			$ttt_form =~ s/　//g;
			if (index($ttt_form,$w_pattern) >= 0){
				$error_mes_type="black_word";
				$bad_user_flag=1;
				last;# 検出したら抜ける
			}
		}
	    }
	  }
	}

	# 2006.04 SPAM対策
	if(($PM{'no_upload_by_spam_word'}==1)&&($bad_user_flag != 1)){

	  # 2006.06 SPAM対策 URLリンク列挙型SPAM対策
	  if($FORM{'body'}=~ /ttp(.*)/is){

		 if($1=~ /tp:(.*)/is){ #1
		    if($PM{'spam_url_link_limit_1'}==1){
		     &error("URLリンクはひとつまでにしてください。");
		    }
		  if($1=~ /tp:(.*)/is){#2
		    if($PM{'spam_url_link_limit_2'}==1){
		     &error("URLリンクはふたつまでにしてください。");
		    }
		   if($1=~ /tp:(.*)/is){#3
		    if($PM{'spam_url_link_limit_3'}==1){
		     &error("URLリンクはみっつまでにしてください。");
		    }
		    if($1=~ /tp:(.*)/is){#4
		     if($PM{'spam_url_link_limit_4'}==1){
		      &error("URLリンクはよっつまでにしてください。");
		     }
		     if($1=~ /tp:(.*)/is){#5
		      if($PM{'spam_url_link_limit_5'}==1){
		       &error("URLリンクはいつつまでにしてください。");
		      }
		      if($1=~ /tp:(.*)/is){#6
		       if($PM{'spam_url_link_limit_6'}==1){
		        &error("URLリンクはむっつまでにしてください。");
		       }
		      }#6
		     }#5
		    }#4
		   }#3
		  }#2
		 }#1
	  }

	  # 2007.05 英語のみの投稿を排除
	  if($PM{'spam_limit_non_japanese'}==1){
	   if($img_data_exists == 1){
	   }else{
	    if($FORM{'body'} eq ""){
		&error(" スパム対策により、本文が空の投稿はできません。 ");
	    }elsif($FORM{'body'}=~ /^[\x00-\x7f]+$/){
		&error(" スパム対策により、英語のみの文字投稿はできません。 ");
	    }
	   }
	  }

	  #2007.05 タイトルなどにURLを埋め込むSPAM対策
	  if($PM{'no_upload_by_spam_word'} == 1){

	      local(@LINKCHK_ITEM)=('name','subject','email','imgtitle');

	      foreach $form(@LINKCHK_ITEM){
	        $ttt_form = $FORM{"$form"};
		$ttt_form =~ s/\s//g;
		$ttt_form =~ s/　//g;
		if($ttt_form=~ /tp:\/\/(.*)/is){
		      &error("URLリンクはこの欄($form)には埋め込めません ");
		}
		# 2007.06.05 タグ埋め込みSPAM対策を追加
		if($ttt_form=~ /<\//g){
		      &error("タグはこの欄($form)には埋め込めません ");
		}
		# XHTML対策
		if($ttt_form=~ /\/>/g){
		      &error("タグはこの欄($form)には埋め込めません ");
		}
		# Webエスケープ対策
		if($ttt_form=~ /&#\d+/g){
		      &error("Webエスケープ文字＆＃XXXはこの欄($form)には埋め込めません ");
		}
	      }
	  }

	  #2007.05 SPAMによるメールアドレス投稿をブロック
	  if(($PM{'no_upload_by_spam_word'} == 1)&&($PM{'no_upload_by_spam_country_mail'} == 1)){

	      local(@LINKCHK_ITEM)=('name','subject','email');

	      foreach $form(@LINKCHK_ITEM){
	        $ttt_form = $FORM{"$form"};
		$ttt_form =~ s/\s//g;
		$ttt_form =~ s/　//g;
	        if(($ttt_form=~ /\@/g)||($ttt_form=~ /＠/g)){
		 foreach (@SPAM_MAIL_COUNTRY){
		    $w_pattern="$_";
		    $w_pattern=~ s/\s//g;
	    	    $w_pattern=~ s/　//g;
		    if($ttt_form=~ /$w_pattern/ig){
		      &error("スパムフィルター設定により、このメールアドレスは($form)欄に書き込めません。 ");
		    }

		 }
		}

	      }
	  }

	  # 2010.02
			# IPアドレスによるSPAMフィルタ機能の追加について(2010.02 )
			# ドメイン名を５０以上持つ業者も多いが、これはドメインの登録が安価だからであり、
			# 契約にそれなりの費用がかかる実IPアドレスは数個以下である場合がほとんどである。
			# 従って、ドメイン名をリストに追加して排除する方法より、リンク先の固定IPを
			# 調べ、禁止リストに追加して、SPAMを排除した方が、効率が良い。
			# ドメイン名からIPアドレスを調べるには、ネットに接続した状態で、
			#  MS-DOSコマンドラインで「ping ホスト名」を入力すれば、結果として表示される。
			# そのIPアドレスを@SPAM_HOSTS_IPに追記すれば良いだろう。
			#
			#2010.02 kisaragi-SPAM対策
			if($FORM{'body'} ne ""){
			 $ttmp2_form_data="$FORM{'body'}";
	    	 $ttmp2_form_data=~ s/\s//g;
	    	 $ttmp2_form_data=~ s/　//g;

			 # 新kisaragi-SPAM対策
			 # Webエスケープ対策をする
			 if($ttmp2_form_data=~ /&#\d+/g){
		      &error("SPAM対策により、Webエスケープ文字＆＃XXXは本文には埋め込めません ");
			 }
			 # 2010.07
			 if($ttmp2_form_data=~ /&#x/gi){
		      &error("SPAM対策により、Webエスケープ文字＆＃xは本文には埋め込めません ");
			 }

			 $ttmp_host_addr="";
			 $ttmp_host_ip="";
			 # 2010.09 update SPAM業者ドメインの多様化に対処
			 # 2010.12 update 顔文字の誤検出に対処
			 # 2012.05 update 専用ドメインを持つタイプに対処
			 if (@URL_HOST_LINKS =  $ttmp2_form_data =~ /\/+([^\)\/]+[\.com|\.net|\.org|\.info|\.biz|\.uk|\.name|\.in|\.tk|\.be|\.mobi|\.co|\.asia|\.jp])/) {
  				foreach (@URL_HOST_LINKS) {
				 next if($_ eq "");
				 # ドメインからIPアドレスを得る(API非公開プロバイダを配慮)
				 $ttmp_host_addr = gethostbyname($_);
  				 $ttmp_host_ip = join('.', unpack("C*", $ttmp_host_addr));
	  			 push(@URL_IP_LINKS, $ttmp_host_ip);  # IPアドレスを配列に保存する。
#&error("SPAM = URL_IP_LINKS - @URL_IP_LINKS - URL_HOST_LINKS - @URL_HOST_LINKS - SPAM_HOSTS_IP - @SPAM_HOSTS_IP");
  			 	}
  			 }
  			 
		 	 foreach (@URL_IP_LINKS){		 	 
				next if($_ eq "");
				$ttmp_link_url_ip="$_";
		 	 	foreach (@SPAM_HOSTS_IP){
				 next if($_ eq "");
			    # 正規表現をPerlパターンマッチへ変換
	    		$ip_pattern=&change_pattern_match($_);
				   if ($ttmp_link_url_ip =~ /^$ip_pattern/i){
					$error_mes_type="black_word";
					$bad_user_flag=1;
#2008.08.08 temp あとで必ず削除
#&error("SPAM検出 = URL_IP_LINKS - @URL_IP_LINKS - URL_HOST_LINKS - @URL_HOST_LINKS - ttmp_link_url_ip $ttmp_link_url_ip ip_pattern $ip_pattern -");				
					last;# 検出したら抜ける
		 	 	   }
		 	    }
		 	    last if($bad_user_flag==1);
 			 }
 			}

	  foreach (@SPAM_WORD){

	    $w_pattern="$_";
	    $w_pattern=~ s/\s//g;
	    $w_pattern=~ s/　//g;

	    if($w_pattern ne ""){
		$blkw_count++;
		#記事すべての項目をチェックする
		local(@ALL_ITEM)=('body','name','subject','email','imgtitle','optA');
		local($ttt_form)="";
		$spam_link_find_flag=0;# URLリンクがあるかどうか（グローバル）
		foreach $form(@ALL_ITEM){
			$spam_link_find_flag=0;	# 初期化
		        $ttt_form = $FORM{"$form"};
			$ttt_form =~ s/\s//g;
			$ttt_form =~ s/　//g;
			if($ttt_form=~ /tp:/i){
				$spam_link_find_flag=1;
			}elsif($ttt_form=~ /ｔｔｐ/i){
				$spam_link_find_flag=1;
			}elsif($ttt_form=~ /\@/i){
				$spam_link_find_flag=1;
#			}elsif($ttt_form=~ /＠/i){
#				$spam_link_find_flag=1;
# 2007.05 修正
			}elsif($ttt_form=~ /\[url=/i){
				$spam_link_find_flag=1;
			}else{
				$spam_link_find_flag=0;
				next;	# URLリンクがない場合はチェックしない
			}
			if (index($ttt_form,$w_pattern) >= 0){
				$error_mes_type="black_word";
				$bad_user_flag=1;
				last;# 検出したら抜ける
			}
		}
	    }
	  }
	}


	# 問題点を検出した場合の処理
	if($bad_user_flag==1){
		# ダミーのエラーメッセージを出す
		if($error_mes_type eq "black_word"){
			# 設定で指定している場合はそれを使う。ないならデフォルト	
			if($PM{'error_message_to_black_word'} ne ""){
				$error_mes_bl="$PM{'error_message_to_black_word'}";
			}
		}
       	&error("$error_mes_bl $blkw_count<!--abwc $add_black_word_count asc $add_spam_count aswc $add_spam_word_count -->");
	}

	}	# 投稿時以外(view時など)は、ホスト名以外のフィルタはスキップ（ここまで）
}

# 外部リストをロードする部品

sub load_ext_list{

	local($list_fname)	= $_[0];	# リストの名前
	local($array_name)	= $_[1];	# 配列の名前
	local($add_count)	= 0;		# リストから追加された項目数

	if(-e "$list_fname"){
	open(IN, "$list_fname")|| &error("設定エラー．ファイル\"$list_fname\"を読込めません．処理は中断されました．");
	eval "flock(IN,1);" if($PM{'flock'} == 1 );
		while(<IN>){
			if($_ =~ /^([^#])(.*)$/){	#コメントアウトは除く
				if($_ =~ /^(\s*)(\S+)(\s*)(\#?)(.*)$/){
					# Perl4でも動く書き方にする（長くなるけど）
					if($array_name eq 'BLACK_LIST'){
# 携帯非対応					push(@BLACK_LIST, $2);
					}elsif($array_name eq 'BLACK_WORD'){
						push(@BLACK_WORD, $2);
					}elsif($array_name eq 'SPAM_HOSTS_IP'){
						push(@SPAM_HOSTS_IP, $2);
					}elsif($array_name eq 'SPAM_WORD'){
						push(@SPAM_WORD, $2);
					}
					$add_count++;
				}
			}
		}
	eval "flock(IN,8);" if($PM{'flock'} == 1 );
	close(IN);
	}
	return($add_count);	# リストから追加された項目数
}

sub change_pattern_match{

	# 正規表現をPerlパターンマッチへ変換

	local($d_pattern)	= $_[0];
	$d_pattern=~ s/\s|\r|\n|\;|\)//g;	# 念のため
	$d_pattern=~ s/\./\\./g;
	$d_pattern=~ s/\?/\./g;
	$d_pattern=~ s/\*/\.\*/g;
	$d_pattern=~ s/P_TAIL$/\$/i;
	$d_pattern=~ s/P_END$/\$/i;
	$d_pattern=~ s/^P_HEAD/\^/i;
	$d_pattern=~ s/P_SPACE/\\s/i;
	return($d_pattern)
}


#============================#
# ＪＵＭＰ用ＨＴＭＬ
#============================#

sub jump_html{

	local($mes_01)	= $_[0];	# メッセージを引数として取得
	local($mes_02)	= $_[1];	# メッセージを引数として取得
	local($cgin01)	= "$cgi_name";
	&output_Content_type; 

	# 返信時にページを記憶する
	if(($FORM{'page'} ne "")&&($FORM{'bbsaction'} eq "post")&&($FORM{'prebbsaction'} eq "disp_rep_form")){
		$cgin01="$cgin01"."?page=$FORM{'page'}";
# レスを上に持って行く設定の場合スレッドが先頭へ行くので、先頭へジャンプ
		if($PM{'res_go_up'} == 1){
			$cgin01="$cgin01"."?page=1";
		}
	}

print<<HTML_END;
<HTML>
<HEAD>
 <TITLE>wait..</TITLE>$top_html_header
 <META HTTP-EQUIV="Refresh" CONTENT="5; URL=$cgin01">
</HEAD>
<BODY BGCOLOR="#D0D0D0">
[Imgboard - Mes]<BR>

 $mes_01 $mes_02 <BR>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}" accesskey=0>掲示板へ戻る</a>
</BODY>
</HTML>
HTML_END

}


#============================#
# エラーの出力
#============================#

sub error{

	local($error_message)	= $_[0];	# メッセージを引数として取得
	local($error_message2)	= $_[1];	# メッセージを引数として取得
	local($lform_action);

	if($keitai_flag eq "imode"){
		$lform_action="$ENV{'HTTP_REFERER'}";
	}elsif($keitai_flag eq "J-PHONE"){
		$lform_action="$cgi_name";
	}else{
		$lform_action="$ENV{'HTTP_REFERER'}";
	}

	&output_Content_type; 


print<<EOF;
<HTML>
<HEAD>
<TITLE>Error</TITLE>
$top_html_header
</HEAD>

<BODY BGCOLOR=\"#D0D0D0">
<CENTER>
from imgbd.<BR>
 [ｴﾗｰです] 
</CENTER>
<BR>
$error_message<BR>/
$error_message2<BR>
<FORM METHOD="GET" ACTION="$lform_action">
<INPUT TYPE="HIDDEN" NAME="page" VALUE=1>
<INPUT TYPE="SUBMIT" VALUE=" 戻る "> 
</FORM>
</BODY>
</HTML>
EOF

	&rm_tmp_uploaded_files;			# 一時保存された画像データを削除
	exit;
}

#===================================#
# 一時登録された画像ファイルの削除
#===================================#
# asx/asf対応(2001.01)
sub rm_tmp_uploaded_files{
	if($img_data_exists==1){
		sleep 1;
		foreach $fname_list(@NEWFNAMES){
			if(-e "$PM{'img_dir'}/$fname_list"){
				unlink("$PM{'img_dir'}/$fname_list");
				# メタファイルも削除する
				&rm_meta_file("$PM{'img_dir'}/$fname_list");
			}
			# 携帯用ファイルも削除する
			if($fname_list=~ /\.(jpe?g|gif|png|bmp|mng)$/i){
				  &rm_snl_file("$unq_id","$PM{'img_dir'}","$existing_snl_type_list");
			}
		}
	}
}

#===================================#
# ASX メタファイルの削除 & 3GP拡張
#===================================#
# Winodows Mediaのストリーム再生やeggyのストリーム再生対応のために
# 機能拡張した。 削除するファイルがメタファイルを持っていそうな
# 名前だったらメタファイルらしきファイルを探し、もしあれば消しておく
# 3GP用に拡張
sub rm_meta_file{

	local($tmp_rm_meta_file)=$_[0]; # 引数は削除するファイル名本体（パス付き）

	# asx等に対応
	if($tmp_rm_meta_file=~ /^(.*)\.(asf|wma|wmv?)$/){
	   if(-e "$1\.asx"){
		unlink("$1\.asx");# ASF(古い表記の仕方)
	   }
	   if(-e "$1\.wvx"){
		unlink("$1\.wvx");# ASF&WinMediaAudio/Video(現在はこれが推奨らしい)
	   }
	}
	# 3gp/3g2複製運用に対応(重複ファイルを削除)
	if($tmp_rm_meta_file=~ /^(.*)\.3g2$/){
	   if(-e "$1\.3gp"){
		unlink("$1\.3gp");# 3gp
}
        }
	if($tmp_rm_meta_file=~ /^(.*)\.3gp$/){
	   if(-e "$1\.3g2"){
		unlink("$1\.3g2");# 3g2
	   }
	}
}
#
#
#=============================#
# 携帯用ファイルの削除(R7)
#=============================#
#
# 将来の全携帯対応を考えて拡張子は
# いろいろできるようにしておく
#
sub rm_snl_file{

	local($tmp_rm_snl_unq_id)	=$_[0]; # 引数1はUID
	local($tmp_rm_snl_dir)		=$_[1]; # 引数2はパス
	local($tmp_rm_snl_exist_type)	=$_[2]; # 引数3はSNL存在リスト

	local($snl_future_bit);		# 携帯用ファイル名の将来拡張ビット
	local($snl_ext);		# 携帯用ファイルの実際の拡張子

	$tmp_rm_snl_unq_id="snl"."$tmp_rm_snl_unq_id";

	 @SNL_TYPE=split(/\//,$tmp_rm_snl_exist_type);

	if($tmp_rm_snl_exist_type ne ""){
	   foreach $snl_type(@SNL_TYPE){
	    	($snl_ext,$snl_future_bit,$dummy)=split(/\-/,$snl_type);
		if(-e "$tmp_rm_snl_dir/$tmp_rm_snl_unq_id$snl_future_bit\.$snl_ext"){
			unlink("$tmp_rm_snl_dir/$tmp_rm_snl_unq_id$snl_future_bit\.$snl_ext");
		}
	   }
	}
}
#
#====================#
# ブラウザチェック
#====================#

sub check_browser_type{

	if($HTTP_USER_AGENT=~ /icab/i){
	  # icabで投稿するとエラーになるので排除する
	  if($FORM{'bbsaction'} eq 'post'){
	    &error(" エラー このブラウザでは記事の投稿はできません ");
	  }
	}

	$jstation_flag	="1";	# ステーション、パケット対応機フラグ(Ｊフォン)

	# 2004.06.01 add
	$au_3G_flag	="0";	# au_3G判別(0=不明,1以上 au_3G)

	$http_upload_ok_flag	="0";# HTTPアップロード対応フラグ(R7 NEW)
	$file_attach_mail	="0";# メールに添付ファイル可能機種(R7 NEW)

	# 2010.09 add
	$http_upload_fullb_only_flag="0";# フルブラウザのみ、HTTPアップロード対応の機種フラグ

	# 2008.06.02 add
	$wmv_play_flag	="0";	# WMV対応判別(0=不明,1以上対応)

	# 2009.12.08 add
	$imode_ver	="1";		# imode Version(0=不明,1以上対応)
	$cookie_ok_flag	="0";	# cookie(0=不明,1以上対応)

	# QVGA判定 2004.06.20 add update 2009.12
	$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
	$KEITAI_ENV{'DISPLAY_HEIGHT'}		="320";

	# 2008.06 WVGA機種増加により仕様拡張
	$qvga_flag	="0";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)


	undef @TMP_UA;
# saru

# 動作チェック
#$HTTP_USER_AGENT="DoCoMo/1.0/F504i/c10/TB";
#$HTTP_USER_AGENT="UP.Browser/3.04-SN13 UP.Link/3.3.0.5";
#$HTTP_USER_AGENT="KDDI-HI21 UP.Browser/6.0.2.254(GUI) MMP/1.1";
#$HTTP_USER_AGENT="KDDI-KC3G UP.Browser/6.2.0.14.1 (GUI) MMP/2.01"; #upload NG maybe
#$HTTP_USER_AGENT="KDDI-CA3D UP.Browser/6.2_7.2.7.1.K.3.330 (GUI) MMP/2.0";#upload NG
#$HTTP_USER_AGENT="KDDI-SH38 UP.Browser/6.2_7.2.7.1.K.3.330 (GUI) MMP/2.0";#SH001 upload ok
#$HTTP_USER_AGENT="KDDI-TS3N UP.Browser/6.2_7.2.7.1.K.3.330 (GUI) MMP/2.0";#T001 upload ok
#$HTTP_USER_AGENT="KDDI-TS3O UP.Browser/6.2_7.2.7.1.K.4.182 (GUI) MMP/2.0";# upload ok
#$HTTP_USER_AGENT="KDDI-TS3P UP.Browser/6.2_7.2.7.1.K.4.182 (GUI) MMP/2.0";# upload ok
#$HTTP_USER_AGENT="KDDI-TS3R UP.Browser/6.2_7.2.7.1.K.4.303 (GUI) MMP/2.0";#T003 upload ok
#$HTTP_USER_AGENT="KDDI-SH3E UP.Browser/6.2_7.2.7.1.K.3.350 (GUI) MMP/2.0";#SH004

#$HTTP_USER_AGENT="PDXGW/1.0 (TX=8;TY=7;GX=96;GY=84;C=C256;G=BF;GI=2)";
#$HTTP_USER_AGENT="J-PHONE/2.0/J-SH02";
#$HTTP_USER_AGENT="J-PHONE/4.0/J-SH51/SNxxxxx SH/0001a Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.1.0";
#$HTTP_USER_AGENT="J-PHONE/4.2/J-SH53/SNJSHF1002783 SH/0003aa Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.2.1";
#$HTTP_USER_AGENT="J-PHONE/5.0/V801SA/SN*********** SA/0001JP Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.1.0";
#http://developers.softbankmobile.co.jp/dp/tool_dl/web/useragent.php

#$HTTP_USER_AGENT="Vodafone/1.0/V904SH/SHJ001/SN";
#$HTTP_USER_AGENT="SoftBank/1.0/910T/TJ001/SN";
#$HTTP_USER_AGENT="SoftBank/1.0/911T/TJ001/SN*************** Browser/NetFront/3.3 Profile/MIDP-2.0";
#$HTTP_USER_AGENT="SoftBank/1.0/935SH/SHJ001/SN*************** Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1";

#$HTTP_USER_AGENT="DoCoMo/2.0 F906i(c100;TB)";
#$HTTP_USER_AGENT="DoCo/2.0 F03A(c100;TB;W24H17)";
# imode2.0
#$HTTP_USER_AGENT="DoCoMo/2.0 F03B(c500;TB;W24H16)";
#
# 2008.06.13
# iPod Touch
#$HTTP_USER_AGENT="Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A100a Safari/419.3";
# iPhone
#$HTTP_USER_AGENT="Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3";
# Wii
#$HTTP_USER_AGENT="Opera/9.10 (Nintendo Wii; U; ; 1621; ja)";
# PSP
#$HTTP_USER_AGENT="Mozilla/4.0 (PSP PlayStation Portable); 2.00)";
# Nintendo DS ブラウザ
#$HTTP_USER_AGENT="Mozilla/4.0 (compatible; MSIE 6.0; Nitro) Opera 8.50 [ja]";
# PlayStation 3
#$HTTP_USER_AGENT="Mozilla/5.0 (PLAYSTATION 3; 1.00)";
# X01HT
#$HTTP_USER_AGENT="Mozilla/5.0 (PDA; NF34PPC/1.0; like Gecko) NetFront/3.4";
# 
#Mozilla/5.0 (Linux; U; Android 1.5; ja-jp; HT-03A Build/CDB72) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1
#HT-03A Android 1.5
#$HTTP_USER_AGENT="Mozilla/5.0 (Linux; U; Android 1.5; ja-jp; HT-03A Build/CDB72) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1";
#HT-03A Android 1.6
#$HTTP_USER_AGENT="Mozilla/5.0 (Linux; U; Android 1.6; ja-jp; Docomo HT-03A Build/DRD08) AppleWebKit/528.5+(KHTML, like Gecko) Version/3.1.2 Mobile Safari/ 525.20.1";



	@TMP_UA = split(/\//,$HTTP_USER_AGENT);


	# FOMA対応(2002.09.25)
	# http://www.nttdocomo.co.jp/service/imode/make/content/spec/useragent/index.html

	if($HTTP_USER_AGENT=~ /DoCoMo\/2\.0\s(\w+)\(c(\d+)/i){

	  $keitai_flag="imode";
	  $KEITAI_ENV{'SERVICE_COMPANY'}	='DoCoMo';
	  $KEITAI_ENV{'HTTP_VERSION'}		='2.0';
	  $KEITAI_ENV{'MACHINE_TYPE'}		="$1";
	  $KEITAI_ENV{'CACHE_SIZE'}		="$2"; # KB(N2001以外は100KB)
	  $KEITAI_ENV{'STREAM_SPEED'}		="";
	  $KEITAI_ENV{'OTHER_PARAM'}		="FOMA";

	  $http_upload_ok_flag	="0";
	  $http_upload_fullb_only_flag="0";# フルブラウザのみ、HTTPアップロード対応の機種フラグ
	  $file_attach_mail	="1";
	  $handle_data_line	="png-jpeg-gif";

	  # QVGA判定 2004.06.20 update
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /9..i/i){
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="320";
	  }

	  # 2007,2008年
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /90[5|6]i/i){

		$KEITAI_ENV{'DISPLAY_WIDTH'}		="480";

		if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(D90|SO90|F90)/i){
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="864";
		}else{
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="854";
		}
		$qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)

	  # 706iシリーズ
	  }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /SH706i/i){
	  
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="480";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="854";
		$qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	  }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /706i/i){
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="400";
		$qvga_flag	="2";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	  # 2008,2009年
	  }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /[A-Z]+(\d+)[A|B|C|D|E]/i){

	    $http_upload_ok_flag	="1";
	    $wmv_play_flag		="1";

	    # 70Xi相当でQVGA 2010.09fix bug 
	    if($KEITAI_ENV{'MACHINE_TYPE'}=~ /^(N03A|P03A|P03B|F07A|F10A|L01A|L03A|L03B|L04B|N05A|N06B|P04A|P05A|P05B|P06A|P06B|P10A|F05A)/i){
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="320";
		$qvga_flag	="1";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	    }else{
	    # last update 2010.09.23
	    # N-08Bだけ横長キーボード付きで縦横反対だ。どうしようか。
	    
		 $KEITAI_ENV{'DISPLAY_WIDTH'}		="480";
	 	 $top_html_header	="<meta name=\"disparea\" content=\"vga\">";

		 if($KEITAI_ENV{'MACHINE_TYPE'}=~ /^(F01A|F06A)/i){
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="864";
		 }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /^(F01B|F09A|F03A|F04B|F06B)/i){
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="960";
		 }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /^(L..A|L..B|L..C|F09B)/i){
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="800";
		 }elsif($KEITAI_ENV{'MACHINE_TYPE'}=~ /^(F02A|F04A|SH..A|SH..B|N..A|N..B|F02B|F03B|F08A|F08B)/i){
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="854";
		 }else{
		    $KEITAI_ENV{'DISPLAY_HEIGHT'}		="854";
		 }
		 $qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	    }
	  }


	  # 2008.06 FOMA 906iの動画・画像2MBアップロード対応に対処
	  # imode CHTML7.2以降はアップできる
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /906i/i){
	    $http_upload_ok_flag	="1";
	    $wmv_play_flag			="1";
	  }

	  # 2008.06 FOMA 706iの一部の動画・画像2MBアップロード対応に対処
	  # imode CHTML7.2以降はアップできる
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(F706|SH706|P706)i/i){
	    $http_upload_ok_flag	="1";
	  }
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(SH706|P706)i/i){
	    $wmv_play_flag			="1";
	  }

	  # 2010.09 imode1.0のXHTML2.3機種対処
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(F09B|SH0.A|N01A|N02A|N04A|P0.A|P10A|F0.A)i/i){
	    $http_upload_ok_flag	="1";
	  }

	  # Cache500KB以降は全機種UPLOAD対応だろう
	  if($KEITAI_ENV{'CACHE_SIZE'} >= 500){
	    $imode_ver	="2";	# imode Version(0=不明,1以上対応)
	  }

	  if($imode_ver >= 2){
	    $http_upload_ok_flag	="1";
	    $wmv_play_flag		="1";
		$cookie_ok_flag	="1";# cookie ok	    
	  }

	# 2010.09 フルブラウザへ誘導するべき機種を検出
	  # フルブラウザ世代
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(904|905|704|705|706)i/i){
	   #HTTPアップロード不可のもの
	   if($http_upload_ok_flag != 1){
		  $http_upload_fullb_only_flag="1";   
	   }
	  }

	  # P705i,PROSOLID,P903iX,SH905i,P905iはフルブラウザのみWMVなので関係ないが
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /(P705i|PROSOLID|P903iX|SH905i|P905i)/i){
	    $wmv_play_flag			="1";
	  }


	}elsif($TMP_UA[0]=~ /^DoCoMo/i){ # 2010.01.23update

	  $keitai_flag="imode";
	  $KEITAI_ENV{'SERVICE_COMPANY'}	=$TMP_UA[0];
	  $KEITAI_ENV{'HTTP_VERSION'}		=$TMP_UA[1];
	  $KEITAI_ENV{'MACHINE_TYPE'}		=$TMP_UA[2];
	  $KEITAI_ENV{'CACHE_SIZE'}		=$TMP_UA[3];
	  $KEITAI_ENV{'STREAM_SPEED'}		=$TMP_UA[4];
	  $KEITAI_ENV{'OTHER_PARAM'}		=$TMP_UA[5];

	  $KEITAI_ENV{'CACHE_SIZE'}		=~ s/c//ig;#cを除去

	  if($KEITAI_ENV{'CACHE_SIZE'} eq ""){
	    $KEITAI_ENV{'CACHE_SIZE'}		=5; # 501の時はキャッシュは5KB
	  }

	  # 2002.12 i-shot判別を追加(2009.12修正)
	  if($KEITAI_ENV{'MACHINE_TYPE'}=~ /504iS|505i|506i|25.i|270./i){
		$ishot_flag=1;
	  }

	  # QVGA判定 2004.06.20 update 2009.12 PDC(504 after)=QVGA ni shita
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="320";

	  $http_upload_ok_flag	="0";
	  $file_attach_mail	="0";
	  $handle_data_line	="gif";

	  $cookie_ok_flag	="0";# cookie NG	    

	# 2009.12 update 2008.03 HDML service haishi wo hanei
	}elsif($TMP_UA[0]=~ /^KDDI/i){ #2010.01.23

		# ブラウザ名/ブラウザバージョン-デバイスID UP.Link/UP.Linkバージョン
		# KDDI-HI21 UP.Browser/6.0.2.252(GUI) MMP/1.1   # EZ次世代(実機)

		$keitai_flag="imode";
		$au_3G_flag	="1";	# au_3G判別(0=不明,1以上 au_3G)
		$KEITAI_ENV{'CACHE_SIZE'}=10; # html 10KB IMG 30KB total 50KB

#		$http_upload_ok_flag	="1";	# どうもできるらしい
		$http_upload_ok_flag	="0";	# どうもできないらしい

		if($TMP_UA[0]=~ /KDDI\-(..)(.*)7\.2(.*)\.K/i){
		  if($1 eq "TS"){
			$http_upload_ok_flag	="1";	# どうもできるらしい
		  }elsif($1 eq "SH"){
			$http_upload_ok_flag	="1";	# どうもできるらしい
		  }
		}

		$file_attach_mail	="1";	# 現在販売されているものは全機種ＯＫ
	  
		local($tmp_ez_mc);
		local($tmp_ez_cc);
		if($TMP_UA[0]=~ /^(KDDI)\-(..)(.)(.+)\s(.*)$/i){
			$tmp_ez_mc = $2;# 機種コード
			$tmp_ez_cc = $3;# キャリアコード
		}elsif($TMP_UA[0]=~ /^(..)(.)(.+)\s(.*)$/i){
			$tmp_ez_mc = $1;# 機種コード
			$tmp_ez_cc = $2;# キャリアコード
		}
		$KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
		$KEITAI_ENV{'DISPLAY_HEIGHT'}		="348";

	  # 2004.06.20 QVGA判定 (環境変数による自動判定) 2009.12 update
	  if($ENV{'HTTP_X_UP_DEVCAP_SCREENPIXELS'}=~ /(\d+)\,(\d+)/){
		if(($1 > 110)&&($2 > 150)){# 0,0 mo aru
	  	 $KEITAI_ENV{'DISPLAY_WIDTH'}		="$1";
	  	 $KEITAI_ENV{'DISPLAY_HEIGHT'}		="$2";
	  	}
	  }

	  # 2010.01.25 CACHE判定 (環境変数による自動判定)
	  if($ENV{'HTTP_X_UP_DEVCAP_MAX_PDU'}=~ /(\d+)/){
		if($1 > 10000){
		 $KEITAI_ENV{'CACHE_SIZE'}=int($1/1000); 
	  	}
	  }
	  
# http://www.au.kddi.com/ezfactory/tec/spec/4_4.html

	  # 機種コード
	   if($tmp_ez_mc eq "SY"){
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_SANYO";
	   }elsif($tmp_ez_mc eq "CA"){
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_CASIO";
	   }elsif($tmp_ez_mc eq "TS"){
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_東芝";
	   }elsif($tmp_ez_mc eq "HI"){
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_日立";
	   }elsif($tmp_ez_mc eq "MA"){
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_松下";
	   }else{
	   	$KEITAI_ENV{'MACHINE_TYPE'}="3G_";
	   }

	   $KEITAI_ENV{'SERVICE_COMPANY'}="au";
	   $KEITAI_ENV{'HTTP_VERSION'}=$TMP_UA[2];

	   $KEITAI_ENV{'MACHINE_TYPE'}="$KEITAI_ENV{'SERVICE_COMPANY'}"."-"."$KEITAI_ENV{'MACHINE_TYPE'}"."-"."WAP"."2.0";

#	$ENV{'HTTP_X_UP_SUBNO'}="0123456789_c7.ezweb.ne.jp";

	  # iクッキー用の番号をサブスクライバーIDより作成
	  $KEITAI_ENV{'UP_SUBNO'}=$ENV{'HTTP_X_UP_SUBNO'};
	  # 0123456789_c7.ezweb.ne.jp のような形式が入る
	  if($ENV{'HTTP_X_UP_SUBNO'}=~ /^.+(\d{4,4})_/i){
	  	$KEITAI_ENV{'UP_SHORT_SUBNO'}=$1;
		$KEITAI_ENV{'SERIAL_SHORT'}=$1;
		$KEITAI_ENV{'SERIAL_LONG'}=$ENV{'HTTP_X_UP_SUBNO'};
	  }else{
#	  	$KEITAI_ENV{'MACHINE_TYPE'}="UP.sim";
	  }

		$cookie_ok_flag	="1";# cookie ok	    

	# 2007.05修正
  	}elsif($TMP_UA[0]=~ /J-PHONE/i){

		# http://developers.softbankmobile.co.jp/dp/
	  	$keitai_flag="J-PHONE";
	  	$KEITAI_ENV{'MACHINE_TYPE'}=$ENV{'HTTP_X_JPHONE_MSNAME'};

		# 2004.06.20 QVGA判定 (環境変数による自動判定)
		if($ENV{'HTTP_X_JPHONE_DISPLAY'}=~ /(\d+)\*(\d+)/){
		 if(($1 > 110)&&($2 > 150)){# 0,0 mo aru
		  $KEITAI_ENV{'DISPLAY_WIDTH'}		="$1";
		  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="$2";
		 }
		}

		$http_upload_ok_flag	="0";	# 基本的にできない
	  	$file_attach_mail	="1";	# 基本的にできる

	  	$KEITAI_ENV{'SERVICE_COMPANY'}=$TMP_UA[0];
	  	$KEITAI_ENV{'HTTP_VERSION'}=$TMP_UA[1];
	  	$KEITAI_ENV{'OTHER_PARAM'}=$TMP_UA[3];
	   	if($KEITAI_ENV{'HTTP_VERSION'} >= 4){
			# パケット対応機
			# 2009.12update
			if($KEITAI_ENV{'HTTP_VERSION'} >= 5.0){
				# W type
				$jstation_flag="5";
				$KEITAI_ENV{'CACHE_SIZE'}='200';
				$cookie_ok_flag	="1";# cookie ok	    
			}elsif($KEITAI_ENV{'HTTP_VERSION'} >= 4.3){
				# P2 type
				$jstation_flag="4.3";
				$KEITAI_ENV{'CACHE_SIZE'}='30';
			}else{
				# P1 type
				$jstation_flag="4";
				$KEITAI_ENV{'CACHE_SIZE'}='12';
			}
			$handle_data_line	="png-jpeg-gif";

			# iクッキー用の番号をシリアル番号より作成
	  		# SNXXXXXXXXX SH のような形式が入る
	  		if($KEITAI_ENV{'OTHER_PARAM'} =~ /^SN(.+)(\d{4,4})\s/i){
	  			$KEITAI_ENV{'SERIAL_LONG'}="$1"."$2";
	  			$KEITAI_ENV{'SERIAL_SHORT'}=$2;
	  		}

			$http_upload_ok_flag	="1"; # 51系は直接アップ可能

#&error("aagg-$KEITAI_ENV{'SERIAL_LONG'}-$KEITAI_ENV{'SERIAL_SHORT'}");

		}elsif($KEITAI_ENV{'HTTP_VERSION'} >= 3){
			# ステーション対応機 C3 type
			$jstation_flag="3";
			$KEITAI_ENV{'CACHE_SIZE'}='6';
			$handle_data_line	="png-jpeg";
		}
  	}elsif($TMP_UA[0]=~ /^Vodafone|^SoftBank/i){

		# http://developers.softbankmobile.co.jp/dp/
	  	$keitai_flag="J-PHONE";
	  	$KEITAI_ENV{'MACHINE_TYPE'}=$ENV{'HTTP_X_JPHONE_MSNAME'};

		# 2004.06.20 QVGA判定 (環境変数による自動判定)
		if($ENV{'HTTP_X_JPHONE_DISPLAY'}=~ /(\d+)\*(\d+)/){
		 if(($1 > 110)&&($2 > 150)){# 0,0 mo aru		
		  $KEITAI_ENV{'DISPLAY_WIDTH'}		="$1";
		  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="$2";
		 }
		}
		# 要検証
		$http_upload_ok_flag	="1";	# 基本的にできる
	  	$file_attach_mail	="1";	# 基本的にできる

	  	$KEITAI_ENV{'SERVICE_COMPANY'}=$TMP_UA[0];
	  	$KEITAI_ENV{'HTTP_VERSION'}=$TMP_UA[1];
	  	$KEITAI_ENV{'OTHER_PARAM'}=$TMP_UA[3];

		$jstation_flag="6";
		# 2009.12 update
		$KEITAI_ENV{'CACHE_SIZE'}='300';
		$handle_data_line	="png-jpeg-gif";

		$cookie_ok_flag	="1";# cookie ok	    

		# iクッキー用の番号をシリアル番号より作成
  		# SNXXXXXXXXX SH のような形式が入る
  		if($KEITAI_ENV{'OTHER_PARAM'} =~ /^SN(.+)(\d{4,4})\s/i){
  			$KEITAI_ENV{'SERIAL_LONG'}="$1"."$2";
  			$KEITAI_ENV{'SERIAL_SHORT'}=$2;
  		}

	}elsif($HTTP_USER_AGENT=~ /iPhone|iPod|iPad/i){

#iPod
#Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A100a Safari/419.3

#iPhone
#Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3

	  	$keitai_flag="pc";
	  	$http_upload_ok_flag	="1";
	  	$file_attach_mail	="1";
	  	$handle_data_line	="jpeg-gif-png-bmp";

		$cookie_ok_flag	="1";# cookie ok	    

	  	$top_html_header	="<meta name=\"viewport\" content=\"width=480, maximum-scale=1.0, minimum-scale=0.5, \">";

	  	
	  	if($TMP_UA[0]=~ /iPod/i){
	  		$KEITAI_ENV{'MACHINE_TYPE'}='iPod';
		}elsif($TMP_UA[0]=~ /iPhone/i){
	  		$KEITAI_ENV{'MACHINE_TYPE'}='iPhone';
		  	$KEITAI_ENV{'SERVICE_COMPANY'}='Softbank';
		}elsif($TMP_UA[0]=~ /iPad/i){
	  		$KEITAI_ENV{'MACHINE_TYPE'}='iPad';
#		  	$KEITAI_ENV{'SERVICE_COMPANY'}='Softbank';
			$top_html_header	="<meta name=\"viewport\" content=\"width=640\">";
		}else{
	  		$KEITAI_ENV{'MACHINE_TYPE'}='iPhone';
		}

	# 2012.09 iOS6以降検出
	if($HTTP_USER_AGENT=~ /iPhone|iPod|iPad/i){
		$MYCGI_ENV{'iOS'}='true';
		if($HTTP_USER_AGENT=~ /OS (\d)\_(\d)/i){
			$MYCGI_ENV{'iOS_VER'}=$1;
		}else{
			$MYCGI_ENV{'iOS_VER'}=3;
		}
	}else{
		$MYCGI_ENV{'iOS'}='false';
	}


	  $KEITAI_ENV{'CACHE_SIZE'}='300';

	  # 2012.10 修正
	  $qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	  $KEITAI_ENV{'DISPLAY_WIDTH'}		="640";
	  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="960";

	}elsif($HTTP_USER_AGENT=~ /Android/i){

#HT-03A Android 1.5
#$HTTP_USER_AGENT="Mozilla/5.0 (Linux; U; Android 1.5; ja-jp; HT-03A Build/CDB72) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1";
#HT-03A Android 1.6
#$HTTP_USER_AGENT="Mozilla/5.0 (Linux; U; Android 1.6; ja-jp; Docomo HT-03A Build/DRD08) AppleWebKit/528.5+(KHTML, like Gecko) Version/3.1.2 Mobile Safari/ 525.20.1";

	  	$keitai_flag="pc";
	  	$http_upload_ok_flag	="1";
	  	$file_attach_mail	="1";
	  	$handle_data_line	="jpeg-gif-png-bmp";

		$cookie_ok_flag	="1";# cookie ok	    

		# safariだから
	  	$top_html_header	="<meta name=\"viewport\" content=\"width=480\">";

	  	
	  	if($TMP_UA[0]=~ /Android (\d+)/i){
	  		$KEITAI_ENV{'MACHINE_TYPE'}="Android"."$1";
			if($TMP_UA[0]=~ /docomo/i){
		  		$KEITAI_ENV{'SERVICE_COMPANY'}='docomo';
			}
		}

	  $KEITAI_ENV{'CACHE_SIZE'}='300';

	  # 2008.06 修正
	  $qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	  $KEITAI_ENV{'DISPLAY_WIDTH'}		="480";
	  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="640";

	}else{
	  # PC
	  $keitai_flag="pc";
	  $http_upload_ok_flag	="1";
	  $file_attach_mail	="1";
	  $handle_data_line	="jpeg-gif-png-bmp";
	  $KEITAI_ENV{'CACHE_SIZE'}='100';
# 	  $KEITAI_ENV{'DISPLAY_WIDTH'}		="240";
#	  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="320";
	  $cookie_ok_flag	="1";# cookie ok	    

	  # スマートフォン対策 2010.04
	  $top_html_header	="<meta name=\"viewport\" content=\"width=480\">";

	  # 2008.06 修正
	  $qvga_flag	="6";	#(0=qvga以下;1=qvga;2=wqvga;5=vga;6=wvga; qvga以上)
	  $KEITAI_ENV{'DISPLAY_WIDTH'}		="480";
	  $KEITAI_ENV{'DISPLAY_HEIGHT'}		="854";
	  
	}

	# 環境変数を受けての処理
	if(($KEITAI_ENV{'DISPLAY_WIDTH'} >= 222)&&($qvga_flag == 0)){
	    $qvga_flag	="1";
	}

	# デバック用に携帯フラグを強制セットする
	# imode,J-PHONE,FOMAを指定、PCモード強制はpcを指定
	if($debug_mode >= 3){
	  if($PM{'keitai_force_set'} ne ""){
		if($PM{'keitai_force_set'} eq "FOMA"){
			$keitai_flag="imode";
			$KEITAI_ENV{'MACHINE_TYPE'}="P2101V";
		}else{
			$keitai_flag="$PM{'keitai_force_set'}";
		}
	  }
	  if($PM{'keitai_force_set'} eq "pc"){
		$keitai_flag="pc";
	  }
	}

#$keitai_flag="imode";
#$KEITAI_ENV{'MACHINE_TYPE'}="251i";


	# 各携帯別の変数を作っておく
	# imode
	if($keitai_flag eq "imode"){
		$form_method="POST";
		$accesskey_p1=qq|[0].|;
		$HR=qq|<HR>|;

	# J-PHONE
	}elsif($keitai_flag eq "J-PHONE"){
	   if($jstation_flag >= 3){
		# ステーション対応機,パケット対応機
		$form_method="POST";
	   }else{
		# Jスカイ対応機
		$form_method="GET";
	   }
	   $HR=qq|<HR>|;
	# PC
	}else{
		$form_method="POST";
		$accesskey_p1=qq|[0].|;
		$HR=qq|<HR>|;
		if($PM{'no_view_from_pc'}== 1){
			&error(" 警告 <BR> このURLは携帯からのアクセス専用です。パ\ソ\コ\ン等それ以外の手段からアクセスされる場合は、<a href=\"$PM{'cgi_hontai_name'}\">imgboard本体($PM{'cgi_hontai_name'})</a>のアクセスURLを管理者に教えてもらい、それを入力してアクセスしてください ");
		}
	}


}

#====================#
# プロバイダチェック
#====================#

sub check_ISP{

	if($SERVER_NAME=~ /bekkoame\./){
		&error(" CGI設定エラー。imgboardがサポート外サイトを検出しました。<BR>「$SERVER_NAME」は、CGIに関して特殊な制約があるため、残念ながらimgboardを利用することができません。他のプロバイダをご利用ください ");
	}

	if(($SERVER_NAME=~ /hi\-ho\.ne\.jp/)||($SERVER_NAME=~ /\.nifty\.com/)){
	# img_url設定が必要なサイトで設定が未設定の場合は警告を出す
		if($PM{'img_url'} eq 'http://あなたのプロバイダ/あなたのディレクトリ/img-box'){
			&error(" CGI設定にエラーがあります。<BR>あなたが設置しようとしているプロバイダ
			「 $SERVER_NAME 」では特殊な設定が必要になります。新FAQ掲示板を参照して、これを設定してください ");
		}
	}

	if($SERVER_NAME=~ /www5.\.biglobe/){
	# img_url設定が必要なサイトで設定が未設定の場合は警告を出す
		if($PM{'img_url'} eq 'http://あなたのプロバイダ/あなたのディレクトリ/img-box'){
			&error(" CGI設定にエラーがあります。<BR>あなたが設置しようとしているプロバイダ
			「 $SERVER_NAME 」では＄img_urlの設定が必要になります。これを設定してください。
			なお、設定方法がわからない場合はサポート掲示板の過去ログを参照してください ");
		}
	}

}


#====================#
# Apache1.3.x対策
#====================#

sub check_RH{
	if(($REMOTE_HOST eq "")||($REMOTE_HOST =~ /^null$/i)){
	    $REMOTE_HOST = "$ENV{'REMOTE_ADDR'}";
	}

	# 1.22 Rev4 イタズラ投稿防止策
	# リモートホストがない場合は登録させない。メッセージはダミー

	if(($REMOTE_HOST eq "")&&($PM{'no_upload_by_no_RH_user'}=='1')){
	    &error("CGIエラー No REMOTE_HOST <BR>現在、リモートホスト情報がない場合は、書き込みできない設定になっています。 ");
	}
}

#================================#
# 連続投稿制限 メイン(1.22 Rev4)
#================================#

sub limit_upload_times{
	if($PM{'limit_upload_times_flag'}==1){
		# 連続投稿カウンタを実行
		# $new_utc_setはクッキーに設定される。
		# 引数は設定部で設定。デフォルト値を持つので空でもいい。
		$new_utc_set=&count_upload_times("$PM{'upload_limit_type'}","$PM{'upload_limit_times'}");
	}
}
#
#================================#
# 連続投稿制限 サブ(1.22 Rev4)
#================================#

sub count_upload_times{

	# 連続投稿カウンタ
	# 引数は時刻レンジ、制限回数
	# 返値は新カウンタセット値,グローバル変数の$now_up_counterに現在の連続回数

	#初期化
	local($PM{'upload_limit_type'})	= $_[0];	# 時刻レンジを引数として取得
	local($PM{'upload_limit_times'})	= $_[1];	# 制限回数を引数として取得
	local($tmp_up_counter);

	# デフォルト値をセット
	$PM{'upload_limit_type'}="2min" if($PM{'upload_limit_type'} eq "");
	$PM{'upload_limit_times'}="5" if($PM{'upload_limit_times'} eq "");

	local(@NOWTIME)	= localtime(time);
	local($yday)		= $NOWTIME[7];

	# 時刻データからタイムベースナンバーを作る
	if($PM{'upload_limit_type'} eq "day"){		# 1日当たり？回で制限
		$up_base_num=35+$yday;
	}elsif($PM{'upload_limit_type'} eq "1hour"){	# 1時間当たり？回で制限
		$up_base_num=35+$yday+$hour;
	}elsif($PM{'upload_limit_type'} eq "10min"){	# 10分当たり？回で制限
		$up_base_num=35+$yday+(int(($min+1)/10));
	}elsif($PM{'upload_limit_type'} eq "2min"){	# 2分当たり？回で制限
		$up_base_num=35+$yday+(int(($min+1)/2));
	}elsif($PM{'upload_limit_type'} eq "1min"){	# 1分当たり？回で制限
		$up_base_num=35+$yday+(int(($min+1)/1));
	}else{						# デフォルトは2分	
		$up_base_num=35+$yday+(int(($min+1)/2));
	}

	if($COOKIE{'utc'} eq ""){
		# クッキーの値がない場合はセット
		$tmp_up_counter=$up_base_num;
		$now_up_counter=1;

		return($tmp_up_counter);
	}else{
		$tmp_up_counter=$COOKIE{'utc'};	# クッキーからカウンタ値を読む
	}		

	# エラーチェック
	if($tmp_up_counter=~ /^(\d+)$/){
		# なにもしない
	}else{
		# ０あるいは、数字以外の異常値になっている場合リセットする
		$tmp_up_counter=$up_base_num;
		# これをクッキーにセットする
		return($tmp_up_counter);
	}
	return(1) if($up_base_num==0);		# ０除算予防(通常はない)

#&error("up base $up_base_num yday $yday utc $COOKIE{'utc'} tmp_up $tmp_up_counter");

	# メイン処理
	if(($tmp_up_counter % $up_base_num)==0){
		# タイムベースが一致する場合はカウントアップする
		$tmp_up_counter+=$up_base_num;
		$now_up_counter=int($tmp_up_counter/$up_base_num);
		if($now_up_counter > $PM{'upload_limit_times'}){
			&error(" CGIエラー overtimes 掲示板管理者が設定した連続投稿
回数をオーバーしました。<BR>しばらく書き込みできません ");
			exit;
		}
	}else{
		# タイムベースが一致しない場合はカウンタをリセットし、新タイムペースを設定
		$tmp_up_counter=$up_base_num;
		$now_up_counter=1;
	}
	# これをクッキーにセットする
	return($tmp_up_counter);
}
#
#===================================#
# プロバイダのOSを判定する
#===================================#
# 引数なし、返値はＯＳの種類(win,mac)
# Perl for Winは新しいOS(NT SP4,Windows2000等)を検出
# できないバグがある そのため、いろんなヒントから、
# Windowsであることを検出するものとする。なお、強制
# 的に設定することもできるようにする。これらのフラグ
# はbinmode切替えやメール処理で用いる。
sub check_www_server_os{

	local($tmp_www_server_os)="";

	# 事前準備（エラーチェック）
	$tmp_www_server_os= $^O;

	# Win98 & NT4(SP4)対策
	if($tmp_www_server_os eq ""){
		$tmp_www_server_os= $ENV{'OS'};
	}

	# AnHTTPd /OmniHTTPd/IIS対策
	if($ENV{'SERVER_SOFTWARE'} =~ /AnWeb|Omni|IIS\//i){
		$tmp_www_server_os= 'win';
	}

	# Win Apache 対策
	if($ENV{'WINDIR'} ne ""){
		$tmp_www_server_os= 'win';
	}

	# Perlが新OSを検知できない場合,強制的に指定する
	if($force_www_server_os_to =~ /win/i){
		$tmp_www_server_os = 'win';
	}elsif($force_www_server_os_to =~ /mac/i){
		$tmp_www_server_os = 'mac';
	}
	return($tmp_www_server_os);
}
#
#==================================#
# 自動URLリンク機能 Ver0.99(R5 NEW)
#==================================#
#
sub set_auto_url_link{

	# 引数１は処理したいデータ;
	# 返値は処理後のデータ;
	local($tmp_data)=@_;
	local($tmp_yb_url)="";
	local($no_object_to_text_link)=0;# OBJECTをテキストリンクにするかどうか
	local($no_iframe_to_text_link)=0;# iframeをテキストリンクにするかどうか 2011.06
	local($tmp_youtube_snl_url)="";

	# アンカータグを書くユーザなら自動リンクをオフにする
	# ない場合のみ処理
	$PM{'auto_mail_find'}=1;

	 # youTube対策 2010.11.29修正
	 if($tmp_data=~ /<object(.|\n)*<embed(.|\n)*application\/x-shockwave-flash/i){
	  if($tmp_data=~ /<object(.|\n)*<embed(.|\n)*src=(\")?(http\:\/\/)([^\"]*)?(.|\n)*/i){
		$tmp_yb_url="$4"."$5";
		
		# Flashの再生できないiPhone等は、flash用の埋込みURLを置き換える必要がある
		if($HTTP_USER_AGENT=~ /iPhone|iPod|iPad|PSP|Android 1\./i){
					$tmp_yb_url=~ s/(http\:\/\/www\.ustream\.tv\/flash\/video\/)(\_a-f0-9)*/http\:\/\/www\.ustream\.tv\/recorded\/$2/ig;
		}

		# 2008.07 fix bug
		$tmp_data =~ s/\<(\s*)OBJECT(.|\n)*(\/OBJECT\>)(\s*)(\n?)/$tmp_yb_url /ig;

		# 余計なものをフィルタ
		$tmp_data =~ s/(https?\:\/\/www\.dailymotion\.com\/swf\/video\/)(\S*)(\?)(\S)*/$1$2/i;

		# 2008.04 youtube jp mobile対応
		# http://m.jp.youtube.com/details?v=589Mvlz6LWE
		# http://www.youtube.com/v/589Mvlz6LWE&hl=en
		# http://jp.youtube.com/watch?v=589Mvlz6LWE

		# 2008.05 番組IDに _ が含まれると視聴できなかった点を修正
		 if($tmp_data=~ /http\:\/\/www\.youtube\.com\/v\//i){
		    if(($KEITAI_ENV{'MACHINE_TYPE'} eq "iPod")||($KEITAI_ENV{'MACHINE_TYPE'} eq "iPhone")||($HTTP_USER_AGENT=~ /android/i)){
#			$tmp_data =~ s/http\:\/\/www\.youtube\.com\/v\/([\-_\.a-zA-Z0-9]+)(\&*)([\-_\a-zA-Z0-9\/\?\&\=\%\n]*)/http\:\/\/www\.youtubemp4\.com\/video\/$1\.mp4 <BR>/ig;
			$tmp_data =~ s/http\:\/\/www\.youtube\.com\/v\/([\-_\.a-zA-Z0-9]+)(\&*)([\-_\a-zA-Z0-9\/\?\&\=\%\n]*)/http\:\/\/m\.youtube\.com\/\?gl\=JP\&hl\=ja\#\/watch\?v\=$1<BR>/ig;
		    }elsif(($KEITAI_ENV{'MACHINE_TYPE'} eq "iPad")||($HTTP_USER_AGENT=~ /android/i)||($HTTP_USER_AGENT=~ /PSP/i)){
			$tmp_data =~ s/http\:\/\/www\.youtube\.com\/v\/([\-_\.a-zA-Z0-9]+)(\&*)([\-_\a-zA-Z0-9\/\?\&\=\%\n]*)/http\:\/\/m\.youtube\.com\/\?gl\=JP\&hl\=ja\#\/watch\?v\=$1\&client\=mv\-google<BR>/ig;
		    }else{
#2009.12 bug fix
			$tmp_data =~ s/http\:\/\/www\.youtube\.com\/v\/([\-_\.a-zA-Z0-9]+)(\&*)([\-_\a-zA-Z0-9\/\?\&\=\%\n]*)/http\:\/\/m\.jp\.youtube\.com\/details\?v\=$1 <BR>/ig;
		    }
		 }else{
			return($tmp_data);
		 }
	  }
	 }

	$tmp_data =~ s/<(\/?)iframe(.|\n)*iframe>(\s*)(\n?)/
	    \[携帯では表\示できないHTML記述です\]./ig;       # IFRAMEタグ    除去

	$tmp_data =~ s/<(\/?)script(.|\n)*script>(\s*)(\n?)/
	    \[携帯では表\示できないHTML記述です\]./ig;       # SCRIPTタグ    除去

	# クラウドサービスのURLを短くする 2009.12
	local($tmp_google_ap)="";
	if($tmp_data=~ /\"http\:\/\/www\.google\.co\.jp\/(\S*)\?/i){
	
	 # iframe埋込とかなので、何もしない
	 
	}elsif($tmp_data=~ /http\:\/\/www\.google\.co\.jp\/(\S*)\?/i){
	 $tmp_google_ap="$1";
	 if($tmp_google_ap=~ /maps/i){
		$tmp_data =~ s/(https?\:\/\/www\.google\.co\.jp\/maps)([\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\?\:\@\&\=\+\$\,\%\#]*)/<A HREF="$1$2" TARGET="_blank">[google地図\]<\/A>/ig;
	 }
	}elsif($tmp_data=~ /([^\"])http\:\/\/maps\.google\.co\.jp\/(\S*)\?/i){
		$tmp_data =~ s/(https?\:\/\/maps\.google\.co\.jp\/maps)([\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\?\:\@\&\=\+\$\,\%\#]*)/<A HREF="$1$2" TARGET="_blank">[google地図\]<\/A>/ig;
	}


	if($tmp_data!~ /<A(\s)(\n?)|<IMA?GE?(.*)/i){
	 $tmp_data =~ s/[\x80-\x9f\xe0-\xff]./$&\x01/g; # 2バイト文字

	   # 自動リンク(iモード/Jフォン用)

		local($tmp_youtube_snl_url)="";
		# 2010.02 update
		local($ttmp_youtube_target_sitei)=qq|TARGET="_blank"|;
  		if($HTTP_USER_AGENT=~ /ipod|iphone|android|PSP/i){
  				# ブラウザが複数起動すると閉じるのがしんどい
				$ttmp_youtube_target_sitei="";
  		}

		# 自動URLリンク
		# 日本語ドメインに対応するとこんなかんじ？？
		# 2001.04.12 改行対応
		# 2001.08.20 小修正（tripod等で認識ミスがあるので訂正）

		# クラウドに多い、長いURLエンコード部分を短くする（表示しても人間が判読できないし、safariでレイアウトも乱れるため）
		if($tmp_data =~ /(\=|\/)([\%a-zA-Z0-9]{42})/g){
		     $tmp_data =~ s/(https?\:\/\/[^\s|\:|\<]+)\.(\/?)([\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\?\:\@\&\=\+\$\,\%\#]*)(\=|\/)([\%a-zA-Z0-9]{36,330})([\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\?\:\@\&\=\+\$\,\%\#]*)/<A HREF="$1.$2$3$4$5$6" $ttmp_youtube_target_sitei>$1.$2$3$4%E6%F3.. $6<\/A>/ig;
		}else{
		     $tmp_data =~ s/(https?\:\/\/[^\s|\:|\<]+)\.(\/?)([\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\?\:\@\&\=\+\$\,\%\#]*)/<A HREF="$1.$2$3$4" $ttmp_youtube_target_sitei>$1.$2$3$4<\/A>/ig;
		}

		$tmp_data =~ s/(r?ftp\:\/\/[\-_\.\!\~\*\'\(\)a-zA-Z0-9\;\/\:]+)/<A HREF="$1" TARGET="_blank">$1<\/A>/g;
		# 自動mailリンク(mailtoルール)		
		$tmp_data =~ s/(mailto\:[\-_\.a-zA-Z0-9\@]+)/<A HREF="$1" TARGET="_blank">$1<\/A>/g;
		# 自動telリンク(telルール)		
		$tmp_data =~ s/(tel\:[\-\(\)0-9\+\#\*]+)/<A HREF="$1" TARGET="_blank">$1<\/A>/g;

		# 自動メルアドリンク(自動検出)
		if($PM{'auto_mail_find'}==1){
		  $tmp_data =~ s/([\-_\.a-zA-Z0-9]+)\@([\-a-zA-Z0-9]+)\.([\-a-zA-Z0-9\.]+)([^a-zA-Z0-9]+)/<A HREF="mailto\:$1\@$2\.$3">$1\@$2\.$3<\/A>$4/g;
		}
	 $tmp_data =~ tr/\x01//d;

$PM{'auto_japanese_address_find'}=1;

		# 自動住所リンク（Google Map & ストリートビュー） 2013.01 Google API仕様変更を反映
		if($PM{'auto_japanese_address_find'}==1){
# 20100425 update
		    if(($tmp_data=~ /東京|区|市|郡|府|県|北海道|字|町|番地/i)&&($tmp_data!~ /https?\:\/\//i)){

		     # 東京都千代田区内幸町1-1-1
		     if($tmp_data=~ /(東京都|大阪府|京都府|[^\s\>\d]+県|北海道)([^\s\>\d]+)(市|区)([^\s\>\d]+)([0-9０-９]+)(\-|－|丁目|の|ノ)([0-9０-９]+)([\-|－|の|ノ]?)([0-9０-９]?)/ig){
		       $ttmp_jp_geoname="$1$2$3$4$5$6$7$8$9";
		       # iPhoneでおかしくなるのでURLエンコード
		       $ttmp_jp_geoname=~ s/(\W)/'%'.unpack("H2", $1)/ego;
		       $tmp_data =~ s/(東京都|大阪府|京都府|[^\s\>\d]+県|北海道)([^\s\>\d]+)(市|区)([^\s\>\d]+)([0-9０-９]+)(\-|－|丁目|の|ノ)([0-9０-９]+)([\-|－|の|ノ]?)([0-9０-９]?)/<A HREF\=\"http\:\/\/maps.google.co.jp\/maps\?q=$ttmp_jp_geoname&hl=ja\" TARGET=\"_blank\">$1$2$3$4$5$6$7$8$9 <\/A>/ig;
		     }elsif($tmp_data=~ /([^\s\>\d]{1,10})(区|市)([^\s\>\d]{1,10})([0-9０-９]+)(\-|－|丁目|の|ノ)([0-9０-９]+)([\-|－|の|ノ])([0-9０-９]+)/ig){
		       $ttmp_jp_geoname="$1$2$3$4$5$6$7$8";
		       # iPhoneでおかしくなるのでURLエンコード
		       $ttmp_jp_geoname=~ s/(\W)/'%'.unpack("H2", $1)/ego;
		       $tmp_data =~ s/([^\s\>\d]{1,10})(区|市)([^\s\>\d]{1,10})([0-9０-９]+)(\-|－|丁目|の|ノ)([0-9０-９]+)([\-|－|の|ノ])([0-9０-９]+)/<A HREF\=\"https\:\/\/maps.google.co.jp\/maps\?q=$ttmp_jp_geoname&hl=ja\" TARGET=\"_blank\">$1$2$3$4$5$6$7$8$9 <\/A>/ig;
		     }
		    }


		}
		
	}
	return($tmp_data);
}

#=========================#
# Content-type のチェック
#========================
#
sub content_type_check{

	local($content_type) = @_;

# 画像
	$ext{'image/jpg'}	= 'jpg'; 
	$ext{'image/jpeg'}	= 'jpg'; 	# for NN
	$ext{'image/pjpg'}	= 'jpg';
	$ext{'image/pjpeg'}	= 'jpg';	# for IE
	$ext{'image/gif'}	= 'gif';	# for NN&IE
	$ext{'image/png'}	= 'png';	# for PNG  file
	$ext{'image/x-png'}	= 'png';	# for PNG  file


	# フラッシュに対応
	$ext{'x-shockwave-flash'}= 'swf';	# for Shockwave_flash

	# gif,jpeg以外に以下のタイプのデータも投稿できるようにするには
	# 初期設定にて$PM{'allow_other_multimedia_data'}を1にしてください．
	if($PM{'allow_other_multimedia_data'} ==1){
		&additional_content_types;
	}

	# imgタグで埋め込み可能なタイプ
	foreach(keys %ext){
		if($content_type =~ /$_/ig){
			return $ext{$_};
		}
	}
	# imgタグで埋め込むと危険なタイプ
	foreach(keys %ext2){
		if($content_type =~ /$_/ig){
			return $ext2{$_};
		}
	}
        # これでも駄目なら拡張子から判断
	 if($fname=~ /\.gif$/i){return 'gif';}
	 if($fname=~ /\.jpe?g$/i){return 'jpg';}


	# 携帯（EZ new）
# 2010.01 update
#	# Document
	 if($fname=~ /\.epub$/i){return 'epub';}# 電子書籍ファイル 2009.12

	 # 2009.10 iPhone対応追加
	 if($fname=~ /\.mp4$/i){return 'mp4';}	# iモーション動画(ISMA-MPEG4:MP)
	 if($fname=~ /\.m4a$/i){return 'm4a';}	# MPEG-4 AAC Audio(iTunes)
	 if($fname=~ /\.m4v$/i){return 'm4v';}	# M4vファイル
	 if($fname=~ /\.aa$/i) {return 'aa';}	# audible.com spoken word
	 if($fname=~ /\.aax$/i){return 'aax';}	# audible.com spoken word Enhanced
	 if($fname=~ /\.aiff?$/i){return 'aif';}# AIFF


	 # スマートフォンやimgboard FLV Playerとの互換性対策
	 if($ENV{'CONTENT_LENGTH'} < 10000*1024){
	  # 10MB以下の場合
	  if($fname=~ /\.3gp$/i){return '3gp';}	# MP4データ(iモーション)
	  if($fname=~ /\.3gpp$/i){return '3gpp';}# MP4データ(iモーション)
	  if($fname=~ /\.3gp4$/i){return '3gp4';}# MP4データ(iモーション)
	 }else{
	  # どうせiムービーでは再生できない。
	  # よって、スマートフォンやFlv Playerと互換性の良いmp4にする
	  if($fname=~ /\.3gp$/i){return 'mp4';}	# MP4データ
	  if($fname=~ /\.3gpp$/i){return 'mp4';}# MP4データ
	  if($fname=~ /\.3gp4$/i){return 'mp4';}# MP4データ
	 }

	 # 2010.08 iPhoneの動画対応
 	 if($fname=~ /\.mov$/i){return 'mp4';}

	 if($fname=~ /\.swf$/i){return 'swf';}	# Flashデータ
	 # 2006.12.13 Flash Movie追加
	 if($fname=~ /\.flv$/i){return 'flv';}	# Flash Video

	# 2010.06.08 imgboard FLV Player用に追加
	 if($fname=~ /\.f4v$/i){return 'mp4';}	# Flash Video
	 if($fname=~ /\.f4a$/i){return 'mp4';}	# Flash Video

	 # 2008.06.02 906iシリーズの2MBアップロード対応
	 if($fname=~ /\.wmv$/i){return 'wmv';}	# Windows Media
	 if($fname=~ /\.wma$/i){return 'wma';}	# Windows Media
	 if($fname=~ /\.asf$/i){return 'asf';}
	 if($fname=~ /\.pdf$/i){return 'pdf';}	# PDFファイル

	 if($fname=~ /\.png$/i){return 'png';}
#	 if($fname=~ /\.bmp$/i){return 'bmp';}

	 
	# gif,jpeg以外に以下のタイプのデータも投稿できるようにするには
	# 初期設定にて$PM{'allow_other_multimedia_data'}を1にしてください．
        if($PM{'allow_other_multimedia_data'} ==1){
         # (自分でリストをさらに追加する場合の注意)
         # cgi,asp,pl,sh,exe.shtml,js,jse,vbs,vbe,hta,wsh,xlm等の拡張子はセキ
	 # ュリティ上危険なので絶対追加しないこと（特にWindowsユーザ）

	# Other Audio


	 # その他細かい物は削除。今はこの機能は要らんでしょう。Google ドライブ等ご利用ください。

	 if($fname=~ /\.mpg$/i){return 'mpg';}
	 if($fname=~ /\.asf$/i){return 'asf';}
	 if($fname=~ /\.txt$/i){return 'txt';}
	 if($fname=~ /\.html?$/i){return 'html';}
        }

	$unknown_data_exit=1;

# データタイプ不明の場合の最終判断
	if($unknown_data_exit==1){
		&error(" このタイプのデ\ータはアップロードできません．");
	}else{
		return 'dat';
	}
}

#=========================#
# Content-type の追加
#=========================#
sub additional_content_types{

# おまけ機能(~_~)
# gif,jpeg以外に以下のタイプのデータも投稿できるようにできます。
# 投稿させたくないデータタイプには#を先頭につけてコメントアウトして下さい。
#
# 2002.04 携帯でｱｯﾌﾟﾛｰﾄﾞしないものを削った
#
# <ご注意！>
# なお投稿許可させたくない場合は#を先頭につけてコメントアウトして下さい。

# 画像系（その他）
	$ext{'image/png'}	= 'png';	# for PNG  file
	$ext{'image/x-png'}	= 'png';	# for PNG  file
#	$ext{'image/bmp'}	= 'bmp';	# for BMP  file
	$ext2{'director'}= 'dcr';		# for Director
	$ext2{'x-shockwave-flash'}= 'swf';	# for Shockwave_flash
	$ext2{'application/pdf'}= 'pdf';	# for PDF  file

# アーカイブ系
	$ext2{'application/zip'}= 'zip';	# for ZIP   (Win)
	$ext2{'x-zip'}= 'zip';			# for ZIP   (Win)


# 3D & ビデオ系
	$ext2{'video/(.*)-asf'}= 'asf';		# for NetShow file

	 # スマートフォンやimgboard FLV Playerとの互換性対策
	if($ENV{'CONTENT_LENGTH'} < 10000*1024){
	 # 10MB以下の場合
	 $ext2{'video/3gpp'}	= '3gp';	# for i-Motion file
	 $ext2{'video/3gp'}	= '3gp';	# for i-Motion file
	 $ext2{'audio/3gpp'}	= '3gp';	# for i-Motion file
	 # 10MB以上だと、どうせ再生できないので、
	 # FLVPlayerやiPhone/iPadと互換性の高いmp4拡張子にする
	}else{
	 $ext2{'video/3gpp'}	= 'mp4';	# for i-Motion file
	 $ext2{'video/3gp'}	= 'mp4';	# for i-Motion file
	 $ext2{'video/3gpp2'}	= 'mp4';	# EZムービーデータ
	 $ext2{'audio/3gpp'}	= 'mp4';	# for i-Motion file
	 $ext2{'audio/3gpp2'}	= 'mp4';	# EZムービーデータ
	}

	# TODO movも小さいときは3GPにリネームした方がいいかも
	$ext2{'video/quicktime'}	= 'mov';# for QuickTime  file
	$ext2{'video/x-flv'}	= 'flv';	# Flash Videoデータ
# 2009.10 追加
	$ext2{'video/x-m4v'}	= 'm4v';	# M4v データ
	$ext2{'video/x-ms-wmv'}= 'wmv';    # Windows Media オーディオ/ビデオ ファイル

# 各社携帯データ
	$ext2{'application/x-smaf'}= 'mmf';	# for MMF

# 会社で仕事に役立ち系
	$ext2{'text/html'}= 'html';	 	# HTMLテキスト
	$ext2{'text/plain'}= 'txt'; 		# テキスト

# 音楽系
	$ext2{'audio/mpeg'}= 'mp3';			# for MPEG Audio
	$ext2{'audio/x-mpegurl'}= 'm3u';		# for MPEG Audio
	$ext2{'audio/x-ms-wma'}= 'wma';		# for WMA file
	$ext2{'audio/mp4'}	= 'm4a';		# for MPEG Audio AAC
	$ext2{'audio/x-m4a'}= 'm4a';			# for MPEG Audio AAC
	$ext2{'audio/x-m4p'}= 'm4p';			# for MPEG Audio AAC

}
#
#====================================================#
# imgtitleから、$IMG_PARAMETERS{name}情報を抜き出す
#====================================================#
# 引数はコメントアウト付きの$tmp_imgtitle
# 返値はコメントアウトなしの$tmp_imgtitleと連想配列 $IMG_PARAMETERS{$name}
sub parse_img_param{

	local($ttmp_imgtitle)= $_[0];	# 引数として取得

	# imgtitleの中にsize,height,width等のパラメータを格納
	# 書式<!--パラメータ名=値;パラメータ名2=値2・・・-->
	# <!--と-->を除きパラメータ部を抽出
	if($ttmp_imgtitle ne ''){
		($ttmp_imgtitle,$img_parameters)=split(/<\!--/,$ttmp_imgtitle);
		$img_parameters=~ s/-->//g;
	}

	# パラメータ$img_parametersが追加されている場合．
	if($img_parameters ne ''){
		foreach ( split(/;/,$img_parameters)){
			local($name,$value) = split(/\=/);
			$IMG_PARAMETERS{$name} = $value;
		}
	}
	return($ttmp_imgtitle);
}
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
#========================#
# CGI名をとりだす
#========================#
#
sub get_script_name {

	local($file_name) = $0;
	local($path_name);
	local($script_name);

	# パスがある場合は削る
	if ($file_name =~ /\\|\//) {
	  if ($file_name =~ /^(.*)\\([^\\]*)$/) {
		$path_name	=$1;
		$script_name	=$2;
	  }elsif($file_name =~ /^(.*)\/([^\/]*)$/) {
		$path_name	=$1;
		$script_name	=$2;
	  }else{
		$script_name	="$file_name";
	  }
	}else{
	  $script_name="$file_name";
	}

	$script_path_name="$path_name"; # グローバル変数(パス)

	# 2003.12 Perl2exe対策
	if($script_name=~ /im\.exe$/i){
		$PM{'cgi_hontai_name'}	= 'imgboard.exe';	# imgboard本体の名前
	}

	return("$script_name");
}

#===================================#
# 記事登録時に管理者にメール
#===================================#

sub send_mail{

	local($tmp_mail_prog)="";		# sendmail以外のプログラム名
	local($tmp_mail_data)= "./$$\.dat";	# 一時ファイル名

	if ($PM{'use_email'}==1){

		# OSの種別を判別
		$www_server_os =&check_www_server_os;

		# メールプログラムの種別を判別
		if($PM{'mail_prog'} =~ /blat/i){
			$tmp_mail_prog="blatj";
		}elsif($PM{'mail_prog'} =~ /sendmane/i){# 2004.08
			$tmp_mail_prog="sendmane";
		}

		# OSをチェック、Windows,Macの初心者ユーザには警告を出す
		# ただし、BlackJumboDog等の利用者には警告を出さない。
		if(($www_server_os=~ /win/i)&&($tmp_mail_prog eq "")){
			&error("管理者設定にエラーがあります<BR>メール通知\機\能\はWindowsサーバでは「blatj」か「sendまねーる」しか使用できません。これらのソフトがない場合はメール通知をオフにしてください。");
			return;
		}elsif($www_server_os=~ /mac/i){
			&error("管理者設定にエラーがあります<BR>メール通知\機\能\はMacサーバでは使用できません。オフにしてください。");
			return;
		}

		# パラメータチェック（セキュリティチェック）
		# メール除外
		if($use_no_email_sitei ==1){	
		 # メール除外アドレス設定
		 foreach (@NOMAIL_LIST){
	    		# 正規表現をPerlパターンマッチへ変換
	    		$w_pattern=&change_pattern_match($_);
			if($email=~ /$w_pattern/i){
#				&error(" $email-@NOMAIL_LIST");
				return;
			}
		 }
		 foreach (@NOMAIL_DOMAIN){
	    		# 正規表現をPerlパターンマッチへ変換
	    		$w_pattern=&change_pattern_match($_);
			if($REMOTE_HOST=~ /$w_pattern/i){
#				&error(" $REMOTE_HOST-@NOMAIL_DOMAIN");
				return;
			}
		 }
		}

		if($email=~ /.*\@.*\..*/){
			$eemail_address=$email;
		}else{
			$eemail_address="dummy\@dummy.co.jp";
		}

		$eemail_name="$name";
		$eemail_subject	="$subject";
		$eemail_imgtitle="$imgtitle";

		# 本文が長すぎる場合はカットする。メール爆弾系のイタズラ対策。
		$eemail_body=$body;
		$eemail_body=~ s/\<BR\>/\n/gi;
		$eemail_body=~ s/\<\!-- user/\n\<\!-- user/i;

		if($PM{'mail_body_limit'}){
			$tmp_mail_body_limit="$PM{'mail_body_limit'}";
		}else{
			$tmp_mail_body_limit='360';
		}

		# 初期設定で設定した制限より長い場合 
		if(length($body) >$tmp_mail_body_limit){
 			# 先頭から指定バイトまでのみ残す
			$eemail_body =substr("$body",0,"$tmp_mail_body_limit");
			$eemail_body .=" \/\/長過ぎますので、以後はカットしました.<!-- user： $REMOTE_HOST --> - $HTTP_REFERER ";
		}

  		# URLが指定されている場合はフルURL表記にする。
		# 外部画像対応修正
		$eemail_img_location="$img_location";
		if(($PM{'cgi_link_url'}!~ /http:\/\/yourprovider\/yourname\/imgboard/)&&($img_location!~ /^http:\/\//i)){
			$eemail_img_location="$PM{'cgi_link_url'}".""."$img_location";
			$eemail_img_location=~ s/(\/?)\.\//\//g; # 相対パスを修正
		}

		# セキュリティ対策のため、問題のある文字をフィルタ
		$eemail_address		=~ s/\,|\;|\://g;
		$eemail_name		=~ s/\,|\;|\://g;
		$eemail_subject		=~ s/\,|\;|\://g;
		$eemail_imgtitle	=~ s/\,|\;|\://g;

		# メール文面ここから
$m_hmes .= "MIME-Version: 1.0\n";
$m_hmes .= "Reply-to: $eemail_address\n";
$m_hmes .= "From: $eemail_address\n";
$m_hmes .= "Subject: \[imgboard\]New article is added (via imode)\n";
$m_hmes .= "Content-Type: text/plain; charset=iso-2022-jp\n";
$m_hmes .= "Content-Transfer-Encoding: 7bit\n\n";

$m_mes .= "携帯アクセスに($keitai_flag-$KEITAI_ENV{'MACHINE_TYPE'})経由で記事が投稿されました。\n";
$m_mes .= "[URL] \n";
$m_mes .= "$PM{'cgi_link_url'}"."\/"."$cgi_name \n";
$m_mes .= "[DATE] $date_data\n";
$m_mes .= "[NAME] $eemail_name\n" 		if($eemail_name ne "");
$m_mes .= "[e-mail] $eemail_address\n"	if($eemail_address ne "");
$m_mes .= "---------------------------------------\n";
$m_mes .= "[TITLE] $eemail_subject\n" 		if($eemail_subject ne "");
$m_mes .= "[MES] \n $eemail_body\n"		if($eemail_body ne "");
$m_mes .= "[File Title]  $eemail_img_title\n" 	if($eemail_img_title ne "");
$m_mes .= "[File URL  ]  $img_data_size\n" 	if($img_location ne "");
$m_mes .= "        $eemail_img_location \n"	if($img_location ne "");
$m_mes .= "[AGENT] $HTTP_USER_AGENT\n"		if($HTTP_USER_AGENT);
$m_mes .= "[連続] $now_up_counter 回 [リミッタ現在設定] $PM{'upload_limit_times'} / $PM{'upload_limit_type'} \n"	if($PM{'limit_upload_times_flag'}==1);
$m_mes .= " 以上 \n";

	# メール文面ここまで
	# blatjの時
	 if($tmp_mail_prog eq "blatj"){

	  # 一時ファイルに書き出す
	   open  (OUT,">$tmp_mail_data") || &error("Write Error : $tmp_mail_data");
	   print  OUT $m_mes;
	   close (OUT);


	  # 添付ファイルがあるときはアタッチオプションを付ける
	  if(($new_snl_fname ne "")&&($img_location ne "")){
	   if(-e "$img_dir/$new_snl_fname"){
#		$attach_option_mes="-attach $img_dir/$new_snl_fname";
#		$attach_option_mes="-attach ./img-box/snl20020820173554.jpg";
	   }
	  }

	  # メールを送出
	  open  (MAIL,"| $PM{'mail_prog'} $tmp_mail_data -t $PM{'recipient'} $attach_option_mes -q -s imgboard_New_article") || &error(" 管理者設定にエラーがあります<BR>メールプログラム$PM{'mail_prog'}が見つかりません。メールプログラムのパスを再確認してください。<BR>またWebサーバとメールサーバが別のサーバの場合使用できません。\n");
   	  close (MAIL);

	  # 一時ファイル削除
	  unlink($tmp_mail_data);

	# 普通のsendmailの時
	 }else{

	  # メールヘッダと本文を結合する
	  $m_mes="$m_hmes"."$m_mes";

	  # メールで標準の形態、漢字コードJIS、改行コードLFに変換する。
	  $m_mes=~ s/\r\n/\n/g;		# 改行コードを変換
	  $m_mes=~ s/\r/\n/g;			#改行コードを変換
	  &jcode'convert(*m_mes, 'jis');	# 漢字コードをJISに(修正99.11)

	  # メールを送出
	  open (MAIL, "|$PM{'mail_prog'} $PM{'recipient'}") || &error(" 管理者設定にエラーがあります<BR>メールプログラム$PM{'mail_prog'}が見つかりません。メールプログラムのパスを再確認してください。<BR>またWebサーバとメールサーバが別のサーバの場合使用できません。\n");
		print MAIL "$m_mes";	
   	  close (MAIL);
	}
      }
}
#
#=====================================#
#     <ＨＴＭＬ--管理メニュー>        #
#=====================================#
#
#  管理メニュー用のＨＴＭＬです．カスタマイズの必要はありません。
#
sub output_admin_menu_HTML{
print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(2010携帯ｱｸｾｽ)<BR>
<BR>
管理MENU<BR>
</CENTER>
$HR
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=remove_select"> 記事削除 </a><BR>
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=show_howto"> 使い方 </a><BR>
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=whats_imgboard"> imgboard?</a><BR>
$HR
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=user_01"> 携帯リンク! </a><BR>
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=user_02"> ---- </a><BR>
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=user_03"> 友達に教える </a><BR>
$HR
DBG<BR>
・<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=check_env">機種情報 </a><BR>
<BR>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}" accesskey=0>戻る</a><BR>
$HR

</BODY>
</HTML>
HTML_END
}
#
#
#
sub show_select_typeA{
    # 引数 NAME,デフォルト値、項目名、1に説明、0に説明
    local($value_name)=$_[0];
    local($selected_value)=$_[1];
    local($p_name)=$_[2];
    local($mes_p1)=$_[3];
    local($mes_p0)=$_[4];
print<<HTML_END;
<SELECT NAME="$value_name">
<OPTION SELECTED>$selected_value
<OPTION VALUE="1">1$mes_p1
<OPTION VALUE="0">0$mes_p0
</SELECT>$p_name<BR>
HTML_END
}
#
sub show_text_input_typeA{
    # 引数 NAME,デフォルト値、項目名,サイズ、最大サイズ
    local($value_name)	=$_[0];
    local($def_value)	=$_[1];
    local($p_name)	=$_[2];
    local($t_size)	=$_[3];
    local($max_length)	=$_[4];
    local($other_para)	=$_[5];
print<<HTML_END;
<INPUT TYPE=TEXT SIZE="$t_size" MAXLENGTH="$max_length" NAME="$value_name" VALUE="$def_value" $other_para>
$p_name<BR>
HTML_END
}
#
#===============================================#
#     <ＨＴＭＬ--管理メニュー(使い方)>          #
#===============================================#
#
#  管理用メニュー(使い方解説)用のＨＴＭＬです．
#
sub output_show_howto_HTML{

	local($mes_p1);

print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(携帯ｱｸｾｽ)<BR>
<BR>
使い方<BR>
</CENTER>
$HR
(閲覧)<BR>
初期画面。
記事を読むモード。
ページ変更は「次？(件)」
「前？(件)」「(先)頭」リ
ンクを使う。<BR>
<BR>
(書)<BR>
記事を書き込むモード。
パスワド設定時は板の
運営者から会員パスを聞
いてから、投稿画面へ。
投稿画面では、各項目を埋め
送信ボタン。

<P>
(画像/動画投稿)<BR>
ドコモの906i/706i以降シリーズの動画・画像2MBアップ対応\機\と
SoftBank 3G\機\の場合、PC同様に、記事投稿時に添付ﾌｧｲﾙを選び
直接Web経由で画像アップ可。
<BR><BR>
古いFOMAやiｼｮｯﾄ機、写メール機,au等のｶﾒﾗ付き携帯の場合、
テキストしか投稿できません。
<BR><BR>
(検索)<BR>
記事の全文検索可。複数ワードで複合検索
する場合は、半角スペースで区切り入力。
<p>
(削除)<BR>
管理人は管理メニューから削除メニューを出し、消したい
記事の左肩にチェックをつけて、管理パスワドを最下欄に
記入し、削除ボタンを押して削除可。
(携帯からの削除は、管理人のみ可)<p>

(その他)<BR>
現在、ガラパゴス携帯(i-mode、SoftBank、EZweb)主要機種全対応。


2015.11現在
主な対応状況です。
<BR>
<BR>
ドコモ<BR>
全FOMA<BR>
<BR>
SoftBank<BR>
3G機 <BR>
<BR>
au(EZweb)<BR>
3G機 <BR>


<BR>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR
</BODY>
</HTML>
HTML_END
}
#
#===============================================#
#     <ＨＴＭＬ--管理メニュー(ワード検索)>            #
#===============================================#
#
#  管理メニュー(ワード検索メニュー)用のＨＴＭＬです．
#
sub output_search_menu_HTML{

	local($mes_p1);
	local($mes_p2);

	if($FORM{'MatchMode'} eq "OR"){
		$mes_p1="selected";
	}else{
		$mes_p2="selected";
	}

print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(携帯ｱｸｾｽ)<BR>
<BR>
ワード検索<BR>
</CENTER>
$HR
<FORM METHOD=GET action="$cgi_name">
<INPUT TYPE=HIDDEN NAME="mode" VALUE="search_menu">
<INPUT TYPE=HIDDEN NAME="viewpass" VALUE="$FORM{'viewpass'}">
<INPUT TYPE=TEXT SIZE=15 NAME="SearchWords" MAXLENGTH=40 istyle=1 MODE=hiragana VALUE="$FORM{'SearchWords'}">
<BR>
<SELECT NAME="MatchMode">
<OPTION VALUE="AND" $mes_p2>AND 検索
<OPTION VALUE="OR" $mes_p1>OR 検索
</SELECT>
<BR><BR>
<CENTER>
<INPUT TYPE=SUBMIT VALUE="検索実行">
</FORM>
<BR>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}" accesskey=0>検索終了</a><BR>
</CENTER>
HTML_END
}
#
sub output_search_menu_HTML2{
print<<HTML_END;
</BODY>
</HTML>
HTML_END
}
#
#===============================================#
#     <ＨＴＭＬ--管理メニュー(imgboardとは)>    #
#===============================================#
#
#  管理メニュー(imgboard解説)用のＨＴＭＬです．
#
sub output_whats_imgboard_HTML{
print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
(携帯ｱｸｾｽ)<BR>
<BR>
imgboardとは<BR>
</CENTER>
$HR
画像をｱｯﾌﾟﾛｰﾄﾞできる機能\を持ったFreeの掲示板です。設定が簡単で設置しやすく、高速\表\示・高機\能\なのが特徴で、現在設置数は数千、ﾕｰｻﾞは数万人おり、ﾌｧｲﾙｱｯﾌﾟﾛｰﾄﾞ機能\持った掲示板の定番として、多くの皆様からご愛顧いただいております。<BR>
<BR>
<CENTER>
携帯ｱｸｾｽCGIとは
</CENTER>
<BR>
画像Upload掲示板(ガラパゴス系携帯ｱｸｾｽ用 CGI)は、imgboard運用者やそのお友達のﾕｰｻﾞ方々からご要望に応えて、開発した追加ｽｸﾘﾌﾟﾄです。なお、このｽｸﾘﾌﾟﾄは、基本的には単体では動きませんので、imgboardと組合わせてご使用ください。
<BR>

$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR
</BODY>
</HTML>
HTML_END
}
#
#=============================================#
#     <ＨＴＭＬ--画像を添付するか確認>        #
#=============================================#
#
#  R7NEW
#
sub output_attach_confirm_HTML{

	local($mes_p1);
	local($mes_p2);
	local($mes_p8);
	local($mes_p9);

	# iOS6最適化
	if($MYCGI_ENV{'iOS_VER'} > 5 ){
	 $mes_p2 =qq|(ｶﾒﾗ携帯/au)|;
	}else{
	 $mes_p2 =qq|(ｶﾒﾗ携帯/au)|;
	}

	if($keitai_flag eq "imode"){
	  if($imode_ver >= 2){
		$mes_p2 =qq|(旧型ｶﾒﾗ携帯/au)|;
	  }
	}elsif($keitai_flag eq "J-PHONE"){
	  if($jstation_flag >= 6){
		$mes_p2 =qq|(旧型ｶﾒﾗ携帯/au)|;
	  }elsif($jstation_flag >= 4){
		$mes_p2 =qq|(ｶﾒﾗ携帯/au)|;
	  }else{
	  }
	}
	if($FORM{'parent'} ne ""){
	   if($PM{'use_rep'} == 1 ){
		$mes_p8 =qq|記事$FORM{'parent'}に返信<BR>|;
	   }
	}

	if($PM{'use_post_password'} == 1 ){ 
		$mes_p9 ='disp_member_check';
	}else{
		$mes_p9 ='disp_input_menu';
	}


print<<HTML_END;
$HR
<CENTER>
$mes_p8
事前確認<BR>
</CENTER>
$HR
1.(画像つき)<BR>
<BR>
HTML_END

	# 返信に画像を添付できないときは出さない
	if(($FORM{'parent'} eq "")||($PM{'allow_res_upload'} eq "1")){


if($keitai_flag eq "J-PHONE"){
print<<HTML_END;
<FORM ACTION="$cgi_name" METHOD="$form_method">
<INPUT TYPE="hidden" NAME="mode" VALUE="$mes_p9">
<INPUT TYPE="hidden" NAME="up" VALUE="file_tag">
<INPUT TYPE="hidden" NAME="blood" VALUE="$FORM{'blood'}">
<INPUT TYPE="hidden" NAME="parent" VALUE="$FORM{'parent'}">
1.1ﾊﾟｿｺﾝと同じFILEタグによる直接投稿式(911T以降のsoftbank3G*)
<BR>
<INPUT TYPE="submit" VALUE=" GO ">
</FORM>
(注)*ﾑｰﾋﾞ写ﾒｰﾙはFILEタグで投稿できないので、ﾒｰﾙ添付で投稿してください。
<BR>
<BR>
HTML_END
}elsif(($keitai_flag eq "imode")||($HTTP_USER_AGENT=~ /android/i)){

 if($http_upload_ok_flag == 1){
print<<HTML_END;
<FORM ACTION="$cgi_name" METHOD="$form_method">
<INPUT TYPE="hidden" NAME="mode" VALUE="$mes_p9">
<INPUT TYPE="hidden" NAME="up" VALUE="file_tag">
<INPUT TYPE="hidden" NAME="blood" VALUE="$FORM{'blood'}">
<INPUT TYPE="hidden" NAME="parent" VALUE="$FORM{'parent'}">
1.1ﾊﾟｿｺﾝと同じFILEタグによる直接投稿式(FOMA906i/706i以降のドコモ携帯)
<BR>
<INPUT TYPE="submit" VALUE=" GO ">
</FORM>
(注)*パケ代注意。パケホダブル必須。カメラ設定はFINEでなく、ノーマルを設定。ファイルサイズを500KB以下にすること。
<BR>
HTML_END

 # 2010.09 フルブラウザへ誘導するべき機種を検出
 }else{
 if($http_upload_fullb_only_flag == 1){
print<<HTML_END;
FOMA904/905/704/705/706のimodeブラウザでは画像投稿ができませんが、
フルブラウザでPC用の掲示板にアクセスして、ファイル添付する形で投稿可能です。
<a href="$PM{'cgi_hontai_name'}" ifb="$PM{'cgi_hontai_name'}">フルブラウザ </a> をご利用ください。
<BR>
(注)*パケ代注意。パケホダブル必須。カメラ設定はFINEでなく、ノーマルを設定。ファイルサイズを500KB以下にすること。
<BR>
HTML_END
 }
 }

}elsif($keitai_flag eq "pc"){


}

# 画像を添付できない場合
}else{

print<<HTML_END;
＊返信時、画像添付NGです<BR>
<BR>
HTML_END

}

print<<HTML_END;
<FORM ACTION="$cgi_name" METHOD="$form_method">
<INPUT TYPE="hidden" NAME="mode" VALUE="$mes_p9">
<INPUT TYPE="hidden" NAME="up" VALUE="text_only">
<INPUT TYPE="hidden" NAME="blood" VALUE="$FORM{'blood'}">
<INPUT TYPE="hidden" NAME="parent" VALUE="$FORM{'parent'}">
<INPUT TYPE="hidden" NAME="page" VALUE="$FORM{'page'}">
$HR
2.(文字だけ)
<BR>
<INPUT TYPE="submit" VALUE=" GO ">
</FORM>


<BR>
<CENTER>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}" accesskey=0>戻る</a>
</CENTER>
$HR
</BODY>
</HTML>
HTML_END
}
#
#=====================================#
#     <ＨＴＭＬ--メンバー確認>        #
#=====================================#
#
#  メンバー確認用のＨＴＭＬです．
#
sub output_member_check_HTML{

	local($mes_p1);
	local($mes_p2);
	local($mes_p3);
	local($mes_p4);
	local($mes_p5);
	local($mes_p8);
	local($mes_p9);
	local($mes_p10);

	if($keitai_flag eq "imode"){
		$mes_p1=qq|istyle="4"|;
		$mes_p2=qq|istyle="4"|;
	}elsif($keitai_flag eq "J-PHONE"){
		$mes_p1=qq|MODE=numeric|;
		$mes_p2=qq|MODE=numeric|;
	  if($jstation_flag >= 4){
		$mes_p4=qq|新型|;
	  }else{
		$mes_p4=qq|旧型|;
	  }
	}
	if($FORM{'parent'} ne ""){
	   if($PM{'use_rep'} == 1 ){
		$mes_p8 =qq|記事$FORM{'parent'}に返信<BR>|;
	   }
	}
	if($FORM{'up'} eq "m2w"){
		if($FORM{'eURL'}=~ /httpEnc_cln/i){
			$mes_p9=qq| ﾒｰﾙ添付型投稿の続きを行います |;
		}
	}

if($PM{'use_post_password'}==1){

print<<HTML_END;
$HR
<CENTER>
$mes_p8
メンバー確認<BR>
</CENTER>
$HR
$mes_p9
<FORM ACTION="$cgi_name" METHOD="$form_method">
会員ﾊﾟｽﾜﾄﾞ\*<BR>
<INPUT TYPE="password" NAME="entrypass" SIZE=4 VALUE="$COOKIE{'entrypass'}" MAXLENGTH="8" $mes_p1><BR><BR>
HTML_END

$mes_p5=qq|*板運営者から通知されたﾊﾟｽﾜﾄﾞを入力|;

}else{

print<<HTML_END;
$HR
<CENTER>
$mes_p9<BR>
$mes_p8<BR>
$mes_p10 「次へ」を押してください<BR>
</CENTER>
$HR
<FORM ACTION="$cgi_name" METHOD="$form_method">
<INPUT TYPE="hidden" NAME="entrypass" VALUE="$PM{'post_passwd'}" MAXLENGTH="8">
HTML_END
}

print<<HTML_END;
<INPUT TYPE="hidden" NAME="mode" VALUE="disp_input_menu">
<INPUT TYPE="hidden" NAME="up" VALUE="$FORM{'up'}">
<INPUT TYPE="hidden" NAME="blood" VALUE="$FORM{'blood'}">
<INPUT TYPE="hidden" NAME="parent" VALUE="$FORM{'parent'}">
<INPUT TYPE="hidden" NAME="page" VALUE="$FORM{'page'}">
<INPUT TYPE="hidden" NAME="eURL" VALUE="$FORM{'eURL'}">
<INPUT TYPE="hidden" NAME="sqid" VALUE="$FORM{'sqid'}">
<INPUT TYPE="submit" VALUE="次へ">
</FORM>

 $mes_p5
<BR>
<BR>
<CENTER>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}" accesskey=0>中止</a>
</CENTER>
$HR
</BODY>
</HTML>
HTML_END
}
#
#=====================================#
#     <ＨＴＭＬ--メンバー確認>        #
#=====================================#
#
#  メンバー確認用のＨＴＭＬです．
#
sub output_view_member_check_HTML{

	local($mes_p1);
	local($mes_p2);
	local($mes_p3);
	local($mes_p4);
	local($mes_p5);
	local($mes_p8);

	if($keitai_flag eq "imode"){
		$mes_p1=qq|istyle="4"|;
	}elsif($keitai_flag eq "J-PHONE"){
		$mes_p1=qq|MODE=numeric|;
	}


print<<HTML_END;
$HR
<CENTER>
$mes_p8
メンバー確認<BR>
</CENTER>
$HR

<FORM ACTION="$cgi_name" METHOD="$form_method">
閲覧ﾊﾟｽﾜﾄﾞ\*<BR>
<INPUT TYPE="password" NAME="viewpass" SIZE=4 VALUE="$COOKIE{'viewpass'}" MAXLENGTH="8" $mes_p1><BR><BR>
<INPUT TYPE="hidden" NAME="mode" VALUE="">
<INPUT TYPE="hidden" NAME="eURL" VALUE="$FORM{'eURL'}">
<INPUT TYPE="submit" VALUE="次へ">
</FORM>

(注１)この板には閲覧ﾊﾟｽが設定されています。運営者ﾊﾟｽﾜﾄﾞを教えてもらってください 。
<BR>
$HR
</BODY>
</HTML>
HTML_END
}
#
#=============================#
#     <ＨＴＭＬ--デバック>    #
#=============================#
#
#  デバック用のＨＴＭＬです．
#
sub output_check_env_HTML{
print<<HTML_END;
<HTML>
<HEAD>$top_html_header</HEAD>
<BODY BGCOLOR=PINK>
画像Upload掲示板<BR>
<CENTER>
<BR>
</CENTER>

デバック用<BR>
$HR
<BR>
Debug<BR>
<BR>
HTTP_USER_AGENT<BR>
$HTTP_USER_AGENT<BR>
<BR>
REMOTE_HOST<BR>
$REMOTE_HOST<BR>
<BR>
REMOTE_ADDR<BR>
$ENV{'REMOTE_ADDR'}<BR>
<BR>
SERVER_ADDR<BR>
$ENV{'SERVER_ADDR'}<BR>
<BR>
HTTP_REFERER<BR>
$HTTP_REFERER<BR>
<BR>
TOKEN<BR>
$uniq_token<BR>
<BR>
SERVICE_COMPANY<BR>
$KEITAI_ENV{'SERVICE_COMPANY'}<BR>
<BR>
HTTP_VERSION<BR>
$KEITAI_ENV{'HTTP_VERSION'}<BR>
<BR>
MACHINE_TYPE<BR>
$KEITAI_ENV{'MACHINE_TYPE'}<BR>
<BR>
DISPLAY-WH<BR>
$KEITAI_ENV{'DISPLAY_WIDTH'} x $KEITAI_ENV{'DISPLAY_HEIGHT'}<BR>
<BR>
CACHE_SIZE<BR>
$KEITAI_ENV{'CACHE_SIZE'} KB<BR>
<BR>
STREAM_SPEED<BR>
$KEITAI_ENV{'STREAM_SPEED'}<BR>
<BR>
OTHER_PARAM<BR>
$KEITAI_ENV{'OTHER_PARAM'}<BR>
<BR>
keitai_f $keitai_flag <BR>
jstation $jstation_flag<BR>
au_3G $au_3G_flag<BR>
http_upload $http_upload_ok_flag<BR>
wmv_play $wmv_play_flag<BR>
cookie $cookie_ok_flag<BR>
imode_ver $imode_ver<BR>
<BR>
$accesskey_p1<a href="$cgi_name?page=$FORM{'page'}&viewpass=$FORM{'viewpass'}&mode=disp_admin_menu" accesskey=0>戻る</a><BR>
$HR
</BODY>
</HTML>
HTML_END
}
#=====================================#
#     <絵文字削除>                    #
#=====================================#
#
sub remove_emoji_i{
 local($target_str)	= $_[0];# 引数でもらう
 $target_str =~ s/\G((?:[\x80-\x9F\xE0-\xEF\xFA-\xFC][\x40-\x7E\x80-\xFC]|[\x00-\x7F]|[\xA1-\xDF])*)(?:[\xf8\xf9][\x40-\xff]|[\xf0-\xf4][\x40-\xff])/$1/go;
 return("$target_str");
}
#
#===================#
# アクセスカウント
#===================#
# 引数なし
# 
sub count_bbs{

	undef @COUNT_FIGS;
	undef @TMP_NO_COUNT_DOMAIN;
	undef %ACCESS_COUNTER;
	undef %COUNTER_HTML;
	local($tmp_countup_flag)=1;
	local($tmp_remote_host)	=0;
	local($line_data)="";

	@TMP_NO_COUNT_DOMAIN=@NO_COUNT_DOMAIN;

	if(@DEF_NO_COUNT_DOMAIN > 0){
	 push(@TMP_NO_COUNT_DOMAIN,@DEF_NO_COUNT_DOMAIN);
	}

	unless(-e "$PM{'count_data_file'}"){

	  if($PM{'auto_make_count_file'}==1){
		# カウントファイルを自動作成
		open(OUT,">>$PM{'count_data_file'}");
		print OUT "last_count_day=$mday\;yesterday=0\;today=1\;total=100\;remote_host=$REMOTE_HOST";
		close(OUT);
	  }else{
		return;
	  }

	}


	# カウントファイルを読みこみ
	open(IN,"$PM{'count_data_file'}") || &error(" 設定エラー。カウントファイル $PM{'count_data_file'}が見つかりません。");
	eval "flock(IN,1);" if($PM{'flock'} == 1 );
	$line_data = <IN>;
	eval "flock(IN,8);" if($PM{'flock'} == 1 );
	close(IN);

	# 前回アクセス者のIPアドレスをチェック
	@pairs = split(/\;/,$line_data);
	foreach $pair(@pairs){
		local($name,$value) = split(/\=/,$pair);
		$name 	=~ s/ //g;
		$ACCESS_COUNTER{$name} = $value;
	}

	# カウントアップすべきかどうか判断する
	if (($REMOTE_HOST ne "")&&($REMOTE_HOST eq "$ACCESS_COUNTER{'remote_host'}")&&($PM{'counter_check_same_ip'}==1)) {
	 $tmp_countup_flag=0;
	}
	if ($ACCESS_COUNTER{'today'} eq "") {
#	 $tmp_countup_flag=0;
	}
	# ページ変更はカウントアップしない。ただし、１ページ目のリロードはテスト的な意味が
	# あると思われるため、カウントアップしておく。
	if (($FORM{'bbsaction'} eq "page_change")&&($FORM{'page'} != 1 )) {
	 $tmp_countup_flag=0;
	}
	if ($FORM{'amode'} ne "") {
	 $tmp_countup_flag=0;
	}
	# mailにカウンタを記載するために追加
	if ($FORM{'bbsaction'} eq "post") {
	 $tmp_countup_flag=0;
	}

	# カウント除外アドレス設定
	foreach (@TMP_NO_COUNT_DOMAIN){
		next if($_ eq "");
	    	# 正規表現をPerlパターンマッチへ変換
	    	$w_pattern=&change_pattern_match($_);

		if($_=~ /^[\d|\.]+$/){  # IPアドレスの場合
		  if($ENV{'REMOTE_ADDR'}=~ /$w_pattern/i){
#			&error(" $ENV{'REMOTE_ADDR'}-@TMP_NO_COUNT_DOMAIN");
			$tmp_countup_flag=0;
		  }
		}else{
		  if($REMOTE_HOST=~ /$w_pattern/i){
#			&error(" $REMOTE_HOST-@TMP_NO_COUNT_DOMAIN");
			$tmp_countup_flag=0;
		  }
		}
	}

	if($ENV{'HTTP_REFERER'}=~ /link_admin/){
			$tmp_countup_flag=0;
	}

	# カウントアップ
	if (($tmp_countup_flag==1)&&($mday > 0)) {

		$ACCESS_COUNTER{'total'}++;
		if($mday eq "$ACCESS_COUNTER{'last_count_day'}"){
			$ACCESS_COUNTER{'today'}++;
		}else{# 日の変わり目
			$ACCESS_COUNTER{'yesterday'}=$ACCESS_COUNTER{'today'};
			$ACCESS_COUNTER{'today'}=1;
		}


		open(OUT,"+< $PM{'count_data_file'}") ||  &error(" 設定エラー。カウントファイル $PM{'count_data_file'}が見つかりません。");
		eval "flock(OUT,2);" if($PM{'flock'} == 1 );
		truncate(OUT, 0);
		seek(OUT, 0, 0);
		print OUT "last_count_day=$mday\;yesterday=$ACCESS_COUNTER{'yesterday'}\;today=$ACCESS_COUNTER{'today'}\;total=$ACCESS_COUNTER{'total'}\;remote_host=$REMOTE_HOST";
		eval "flock(OUT,8);" if($PM{'flock'} == 1 );
		close(OUT);

		# アクセスログを記録
		&write_access_log;
	}


	# テキストの表示桁数を調整する

	$COUNTER_FIG{'total'}		=6 if($COUNTER_FIG{'total'} eq "");
	$COUNTER_FIG{'today'}		=4 if($COUNTER_FIG{'today'} eq "");
	$COUNTER_FIG{'yesterday'}	=4 if($COUNTER_FIG{'yesterday'} eq "");

	foreach(keys %ACCESS_COUNTER){
		$ACCESS_COUNTER{$_}=~ s/\s//g;
		while(length($ACCESS_COUNTER{$_}) < $COUNTER_FIG{$_}) {
	 		$ACCESS_COUNTER{$_} = '0' . $ACCESS_COUNTER{$_};
		}
	}

	foreach(keys %ACCESS_COUNTER){

		# カウンタ表示用配列を作る
		@COUNT_FIGS = split(//, $ACCESS_COUNTER{$_});


		# HTML部品を作る(HTMLソース中に、「$COUNTER_HTML{total}」「$COUNTER_HTML{yesterday}」「$COUNTER_HTML{today}」等を
		# 埋め込めば、そこに表示されます)

		# GIF表示
		if (($PM{'use_count'} == 2)&&($keitai_flag eq "pc")) {
			foreach (@COUNT_FIGS) {
				$COUNTER_HTML{$_} .=qq|<img src=\"$img_dir/$_\.gif\" alt=\"$_\.gif\" width="$PM{'counter_fig_width'}" height="$PM{'counter_fig_height'}">|;
			}
		# テキスト表示
		}elsif($PM{'use_count'} == 1){

			$COUNTER_HTML{$_}=qq|<font color=\"$PM{'counter_text_color'}\" face=\"IMPACT\">$ACCESS_COUNTER{$_}</font>\n|;

		# 不明時はテキスト表示（将来の互換性確保のため）
		}else{
			$COUNTER_HTML{$_}=qq|<font color=\"$PM{'counter_text_color'}\" face=\"IMPACT\">$ACCESS_COUNTER{$_}</font>\n|;
		}
	}

}
#
#=========================================#
# アクセスカウントの初期値をセットする
#=========================================#
# 引数1,セットするパラメータ、
# 引数2,セットする値
#
# 
sub counter_set{

	local($tmp_param,$tmp_value)=@_;

	undef %ACCESS_COUNTER;
	local($tmp_remote_host)	=0;
	local($line_data)="";

	unless(-e "$PM{'count_data_file'}"){
		return;
	}

	# パラメータチェック
	if($tmp_value!~ /^\d+$/){
		&error(" 入力パラメータエラー。半角数字以外の文字を入力しないでください。");
	}

	# カウントファイルを読みこみ
	open(IN,"$PM{'count_data_file'}") || &error(" 設定エラー。カウントファイル $PM{'count_data_file'}が見つかりません。");
	eval "flock(IN,1);" if($PM{'flock'} == 1 );
	$line_data = <IN>;
	eval "flock(IN,8);" if($PM{'flock'} == 1 );
	close(IN);

	@pairs = split(/\;/,$line_data);
	foreach $pair(@pairs){
		local($name,$value) = split(/\=/,$pair);
		$name 	=~ s/ //g;
		$ACCESS_COUNTER{$name} = $value;
	}

	# 上書きする
	$ACCESS_COUNTER{"$tmp_param"} = "$tmp_value";

	# 壊れていたら修復する
	$ACCESS_COUNTER{last_count_day} = "$mday" if($ACCESS_COUNTER{last_count_day} eq "");
	$ACCESS_COUNTER{yesterday}      = "1" if($ACCESS_COUNTER{yesterday} eq "");
	$ACCESS_COUNTER{today}          = "1" if($ACCESS_COUNTER{today} eq "");
	$ACCESS_COUNTER{total}          = "100" if($ACCESS_COUNTER{total} eq "");

	open(OUT,"+< $PM{'count_data_file'}") ||  &error(" 設定エラー。カウントファイル $PM{'count_data_file'}が見つかりません。");
		eval "flock(OUT,2);" if($PM{'flock'} == 1 );
		truncate(OUT, 0);
		seek(OUT, 0, 0);
		print OUT "last_count_day=$ACCESS_COUNTER{'last_count_day'}\;yesterday=$ACCESS_COUNTER{'yesterday'}\;today=$ACCESS_COUNTER{'today'}\;total=$ACCESS_COUNTER{'total'}\;remote_host=$ACCESS_COUNTER{'remote_host'}";
		eval "flock(OUT,8);" if($PM{'flock'} == 1 );
	close(OUT);

}
#
#=======================#
# アクセスログを記録
#=======================#
# 引数なし
# 
sub write_access_log{

	undef @UA_LIST;
	undef @TMP_NO_COUNT_DOMAIN;
	local($tmp_ua_list)=0;
	local($tmp_crypt_RH)=$ENV{'REMOTE_HOST'};
	local($tmp_HTTP_USER_AGENT)=$HTTP_USER_AGENT;

	@TMP_NO_COUNT_DOMAIN=@NO_COUNT_DOMAIN;

	if(@DEF_NO_COUNT_DOMAIN > 0){
	 push(@TMP_NO_COUNT_DOMAIN,@DEF_NO_COUNT_DOMAIN);
	}

	unless(-e "$PM{'access_log_file'}"){
	  if($PM{'auto_make_access_log_file'}==1){
		# アクセスログファイルを自動作成
		open(OUT,">>$PM{'access_log_file'}");
		print OUT "\n";
		close(OUT);
	  }else{
		return;
	  }
	}

	if($ENV{'HTTP_REFERER'}=~ /link_admin/){
		return;
	}

	# ログ除外アドレス設定
	foreach (@TMP_NO_COUNT_DOMAIN){
	    	# 正規表現をPerlパターンマッチへ変換
	    	$w_pattern=&change_pattern_match($_);
		if($REMOTE_HOST=~ /$w_pattern/i){
#			&error(" $REMOTE_HOST-@TMP_NO_COUNT_DOMAIN");
			return;
		}
	}

	$tmp_crypt_RH=&tiny_encode("$tmp_crypt_RH");

	# アクセスログファイルを読みこみ
	open(IN,"$PM{'access_log_file'}") || &error(" 設定エラー。アクセスログファイル $PM{'access_log_file'}が見つかりません。");
	eval "flock(IN,1);" if($PM{'flock'} == 1 );
	while(<IN>){
		if($_ =~/\w/){
			push(@UA_LIST,$_);
		}
		last if($UA_LIST > 100);
	}
	eval "flock(IN,8);" if($PM{'flock'} == 1 );
	close(IN);

	$tmp_HTTP_USER_AGENT=&Enc_EQ("$tmp_HTTP_USER_AGENT");

	# アクセスログファイルを書き込み
	open(OUT,">$PM{'access_log_file'}") || &error(" 設定エラー。アクセスログファイル $PM{'access_log_file'}が見つかりません。");
	eval "flock(IN,2);" if($PM{'flock'} == 1 );
	print OUT "total=$ACCESS_COUNTER{'total'}\;date=$year/$month/$mday,$hour:$min:$sec\;remote_host=$tmp_crypt_RH\;user_agent=$tmp_HTTP_USER_AGENT\;\n";

	foreach(@UA_LIST){
		$tmp_ua_list++;
		if($_ =~/\w/){
			print OUT "$_";
		}
		last if($tmp_ua_list >= 99);
	}
	eval "flock(OUT,8);" if($PM{'flock'} == 1 );
	close(OUT);
}
#
#=============================================================#
# CGI負荷100%の高ロードサイト用に、BBS先頭ページをHTML化する
#==============================================================#
sub make_top_html_for_high_load_svr{

	  if($PM{'make_bbs_html_top'}==1){

	      open  (COMMAND,"| $PM{'perl_prog'} $cgi_name make_html_top>index.html");
	      close (COMMAND);
	  }
}
#
#========================#
# perl -wc 警告対策 
#========================#
#
sub disp_unused_parameters{

  print "Content-type: text/html"."\n\n";
print<<HTML_END;
<PRE>
script_path_name $script_path_name
permit $permit
other $other
dummy $dummy
yyday $yyday
viewmode $viewmode
ext_config_ver $ext_config_ver
attach_option_mes $attach_option_mes
read_file_counter $read_file_counter
tag_siyou_tyuui $tag_siyou_tyuui
full_fname $full_fname
PC用とコード共用のため、残す
$no_iframe_to_text_link
$no_object_to_text_link
動作確認＆切り分け用
$ttmp_uniq_char
</PRE>
HTML_END

}
#
#
# スクリプト終端です

