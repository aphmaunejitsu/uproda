                                                                                                                                                                                                              
# uproda
http://minus-k.com/nejitsu/upload.html の管理人さん今までありがとうだよ  

## インストール
- apacheの設定とか
FUEL_ENVは環境によって変更する。  
uproda.confとでもして、apacheが読むところにおいちゃう
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
```
- PathToDocrootdirは、clone後の「uproda/public」までをフルパスで指す
- PathTologdirは、apacheのログディレクトリを指す
