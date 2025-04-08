#!/bin/sh
TargetDIR=/code/Autoapp
FILE=/Jobs/Backup/code/Autoapp-`date +"%Y%m%d"`.zip
keep_day=60
zip -q -T -r  $FILE $TargetDIR
find /Jobs/Backup/code/ -maxdepth 1 -mtime +$keep_day -delete
/usr/sbin/gdrive files upload /Jobs/Backup/code/Autoapp-`date +"%Y%m%d"`.zip