#!/bin/sh

Backupdir=/Jobs/Backup/sql
FILE=$Backupdir/Mechanic_Portal.sql.`date +"%Y%m%d"`
DATABASE=Mechanic_Portal
keep_day=60

unalias rm     2> /dev/null
rm ${FILE}     2> /dev/null
rm ${FILE}.gz  2> /dev/null

mysqldump --defaults-extra-file=/Jobs/config.cnf ${DATABASE} > ${FILE}
gzip $FILE
echo "Backup Done in: "$(date '+%Y-%m-%d')
find $Backupdir -mtime +$keep_day -delete
/usr/sbin/gdrive files upload ${FILE}.gz
# /usr/sbin/gdrive files upload /Jobs/Backup/sql/Yazd.sql.`date +"%Y%m%d"`.gz