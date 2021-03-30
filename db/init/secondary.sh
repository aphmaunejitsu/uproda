#!/bin/sh 
export MYSQL_PWD=$MYSQL_ROOT_PASSWORD
echo "wating for boot ${DB_MASTER}"

while ! mysqladmin ping -uroot -h $DB_MASTER --silent; do
  echo 'not boot'
  sleep 1
done
echo "done wating for boot ${DB_MASTER}"

echo "lock master ${DB_MASTER}"
mysql -uroot -e "RESET MASTER;"
mysql -uroot -e "FLUSH TABLES WITH READ LOCK;"

echo "dump ${DB_MASTER}"
mysqldump -uroot -h ${DB_MASTER} --all-databases --master-data --single-transaction --flush-logs --events > /tmp/master_dump.sql

mysql -uroot -e "STOP SLAVE;"
mysql -uroot < /tmp/master_dump.sql

log_file=`mysql -u root -h ${DB_MASTER} -e "SHOW MASTER STATUS\G" | grep File: | awk '{print $2}'`
position=`mysql -u root -h ${DB_MASTER} -e "SHOW MASTER STATUS\G" | grep Position: | awk '{print $2}'`

echo "log file: ${log_file}"
echo "position: ${position}"

mysql -uroot -e "RESET SLAVE;"
mysql -uroot -e "CHANGE MASTER TO MASTER_HOST='${DB_MASTER}', MASTER_USER='${MYSQL_ROOT_USER}', MASTER_PASSWORD='${MYSQL_PWD}', MASTER_LOG_FILE='${log_file}', MASTER_LOG_POS=${position};"
mysql -uroot -e "START SLAVE;"

# masterをunlockする
echo "unlock master ${DB_MASTER}"
mysql -uroot -h$DB_MASTER -e "UNLOCK TABLES;"
