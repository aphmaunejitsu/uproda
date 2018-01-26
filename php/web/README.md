# uproda
http://minus-k.com/nejitsu/upload.html の管理人さん今までありがとうだよ

## apacheの設定とか
- FUEL_ENVは環境によって変更する。  
- uproda.confとでもして、apacheが読むところにおいちゃう
- PathToDocrootdirは、clone後の「uproda/public」までをフルパス指定
- PathTologdirは、apacheのログディレクトリをフルパス指定
- pathtossldirは、crt,keyを保管するディレクトリをフルパス指定
- SSLオンリーでも80は作っておく（80 -> 443リダイレクト用)
```
<VirtualHost *:80>
  ServerAdmin Admin@server.com
    DocumentRoot "/PathToDocrootdir"
    ServerName xxxx.com
    SetEnv FUEL_ENV "development"
    ErrorLog "/PathTologdir/xxxx.com-error_log"
    CustomLog "/PathTologdir/xxxx.com-access_log" common
    <Directory "/pathToDocrootdir">
        AllowOverride All
        DirectoryIndex index.php
        Require all denied
        Require all granted
    </Directory>
</VirtualHost>
<VirtualHost *:443>
    ServerAdmin Admin@server.com
    DocumentRoot "/PathToDocrootdir"
    ServerName xxxx.com:443
    SetEnv FUEL_ENV "development"
    ErrorLog "/PathTologdir/xxxx.com-SSL-error_log"
    CustomLog "/PathTologdir/xxxx.com-SSL-access_log" common
    SSLEngine             on
    SSLCipherSuite        ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP:+eNULL
    SSLCertificateFile    /pathtossldir/xxxx.com.crt
    SSLCertificateKeyFile /pathtossldir/xxxx.com.key
    <Files ~ "\.(cgi|shtml|phtml|php3?)$">
      SSLOptions +StdEnvVars
    </Files>
    SetEnvIf  User-Agent ".*MSIE.*" nokeepalive ssl-unclean-shutdown downgrade-1.0 force-response-1.0
    SetOutputFilter DEFLATE
    AddOutputFilterByType DEFLATE text/xml text/css text/javascript text/js application/json
    <Directory "/PathToDocrootdir">
        AllowOverride All
        DirectoryIndex index.php
        Require all denied
        Require all granted
    </Directory>
</VirtualHost>
```
    
## DBへTableのインストールとか
FUEL_ENV=production php oil r migrate  
失敗した場合は、DB定義でactiveの定義をmaintenanceに変更して実行   

## データの初期投入
- TOR: FUEL_ENV=production php oil r tor
- NGWord: FUEL_ENV=production php oil r ngwords
- NGHash: FUEL_ENV=production php oil r hashes

## 管理ユーザの初期投入
利用する人数分投入 
FUEL_ENV=production php oil console 
\Auth::create_user('admin', 'password', 'aphmau_nejitsu@icloud.com', 100);

## 管理ページ
- https://yourdomain.com/admin/


