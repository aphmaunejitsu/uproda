# uproda
## Docker
- git clone git@github.com:aphmaunejitsu/uproda.git
- docker-compose build
- docker-compose up -d
- http://localhost:1080/
    

## データの初期投入
- TOR: FUEL_ENV=production php oil r tor
- NGWord: FUEL_ENV=production php oil r ngwords
- NGHash: FUEL_ENV=production php oil r hashes

## 管理ユーザの初期投入
利用する人数分投入
FUEL_ENV=production php oil console
\Auth::create_user('admin', 'password', 'email@example.com', 100);

## 管理ページ
- https://yourdomain.com/admin/


