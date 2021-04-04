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

    FOREIGN=`mysqldump --defaults-extra-file=$CONF --no-data -d --column-statistics=0 $DATABASE $TABLE|grep CONSTRAINT`
    echo '## Foreign Key' >> ${OUTPUT}/$TABLE.md
    echo 'column|references|delete|update' >> $OUTPUT/$TABLE.md
    echo '----|----|----|----' >> $OUTPUT/$TABLE.md
    if [ -n "$FOREIGN" ]; then
        TEMPLINE=`echo ${FOREIGN}|sed -e 's/ /@/g'`
        LINES=(${TEMPLINE//,/ })
        for LINE in "${LINES[@]}"; do
            v=`echo ${LINE/@CONSTRAINT/CONSTRAINT/}`
            key=`echo ${v}|awk -F'@' '{print $5}'|sed -e 's/[\`\(\)]//g'`
            ref=`echo ${v}|awk -F'@' '{print $7}'|sed -e 's/[\`\(\)]//g'`
            outer_key=`echo ${v}|awk -F'@' '{print $8}'|sed -e 's/[\`\(\)]//g'`
            link="[$ref.$outer_key](./$ref.md)"

            if [ -n `echo $v|grep "@DELETE"` ]; then
                delete=`echo $v|awk -F'@' '{print $11}'`
            else
                delete=""
            fi

            if [ -n "$delete" ]; then
                if [ -n `echo $v|grep "@UPDATE"` ]; then
                    update=`echo $v|awk -F'@' '{print $14}'`
                else
                    update=""
                fi
            else
                if [ -n `echo $v|grep "@UPDATE"` ]; then
                    update=`echo $v|awk -F'@' '{print $11}'`
                else
                    update=""
                fi
            fi


            echo "$key|$link|$delete|$update" >> $OUTPUT/$TABLE.md

        done;
    fi

    NAME=`mysql --defaults-extra-file=${CONF} $DATABASE -e "show create table $TABLE \G"|grep -oe "COMMENT='\(.*\)'"|sed "s/COMMENT='\(.*\)'/\1/"`
    echo "1. [${TABLE} ${NAME}](./${TABLE}.md)" >> ${OUTPUT}/${README}
  fi
done;

if [ -d ${TMP} ] ; then
  rm -rf ${TMP}
fi
