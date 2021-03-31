#!/bin/bash

OUTPUT='def'
DATABASE='uproda'
TMP='tmp'
README='README.md'
CONF='cnf/def.cnf'
TEMPLE='cnf/def.xsl'

if [ ! -d ${TMP} ] ; then
  mkdir ${TMP}
fi

if [ -f ${OUTPUT}/${README} ] ; then
  rm -rf ${OUTPUT}/*
fi

# テーブルごとに出力
for TABLE in `mysql --defaults-extra-file=${CONF} -N -s -e "show tables in ${DATABASE};"`; do
  if [ "migrations" != $TABLE ]; then
    echo $TABLE
    mysqldump --defaults-extra-file=${CONF} --no-data --xml --column-statistics=0 $DATABASE $TABLE > ${TMP}/$TABLE.xml
    xsltproc -o ${OUTPUT}/$TABLE.md ${TEMPLE} ${TMP}/$TABLE.xml
    NAME=`mysql --defaults-extra-file=${CONF} $DATABASE -e "show create table $TABLE \G"|grep -oe "COMMENT='\(.*\)'"|sed "s/COMMENT='\(.*\)'/\1/"`
    echo "1. [${TABLE} ${NAME}](./${TABLE}.md)" >> ${OUTPUT}/${README}
  fi
done;

if [ -d ${TMP} ] ; then
  rm -rf ${TMP}
fi
