#!/usr/bin/env bash
set -e
echo "remove old files from /Jobs/Backup"
find /Jobs/Backup -type f -mtime +15 -delete
echo "All old files deleted from /Jobs/Backup"