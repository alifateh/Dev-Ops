# AutoApp Backup Script

[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

This Bash script automates the process of backing up a local code repository (`/code/Autoapp`) and uploading it to Google Drive. This provides a free cloud-based backup solution for managing and storing your code.

## Purpose

The primary goal of this script is to:

* **Create a daily ZIP archive** of the `/code/Autoapp` directory.
* **Store these backups in a local directory** (`/Jobs/Backup/code/`).
* **Automatically delete backups older than a specified number of days** (default: 60 days) to manage storage.
* **Upload the latest backup to Google Drive** using `gdrive`.

This setup allows for:

* A free cloud version of the controller.
* Easy management of backup storage.
* Potential for future enhancements, such as log rotation and differential backups.

## Script Breakdown

```bash
#!/bin/sh
TargetDIR=/code/Autoapp
FILE=/Jobs/Backup/code/Autoapp-`date +"%Y%m%d"`.zip
keep_day=60
zip -q -T -r  $FILE $TargetDIR
find /Jobs/Backup/code/ -maxdepth 1 -mtime +$keep_day -delete
/usr/sbin/gdrive files upload /Jobs/Backup/code/Autoapp-`date +"%Y%m%d"`.zip
```
#!/bin/sh: Specifies the shell interpreter (Bash).
TargetDIR=/code/Autoapp: Defines the source directory to be backed up.
FILE=/Jobs/Backup/code/Autoapp-date +"%Y%m%d".zip: Creates a filename for the ZIP archive, including the current date in YYYYMMDD format.
keep_day=60: Sets the number of days to retain backups before deletion.
zip -q -T -r $FILE $TargetDIR: Creates a ZIP archive of the TargetDIR.
-q: Quiet mode (suppresses output).
-T: Test the archive after creation.
-r: Recursive (includes subdirectories).
find /Jobs/Backup/code/ -maxdepth 1 -mtime +$keep_day -delete: Finds and deletes files in the backup directory older than keep_day days.
-maxdepth 1: Limits the search to the specified directory (no subdirectories).
-mtime +$keep_day: Finds files modified more than keep_day days ago.
-delete: Deletes the found files.
/usr/sbin/gdrive files upload /Jobs/Backup/code/Autoapp-date +"%Y%m%d".zip: Uploads the created ZIP archive to Google Drive using the gdrive command-line tool.
Prerequisites
zip: The zip command-line utility must be installed.
find: The find command-line utility is typically pre-installed on Linux/macOS.
gdrive: The gdrive command-line tool must be installed and configured for Google Drive access. You can find installation instructions here: https://github.com/prasmussen/gdrive
Proper permissions for the directories involved.
Configuration
TargetDIR: Modify this variable to specify the directory you want to back up.
FILE: Adjust the backup file path and naming convention as needed.
keep_day: Change this value to adjust the backup retention period.
gdrive path: Verify that the path to your gdrive executable is correct.
Installation
Clone this repository to your server.
Install the prerequisites listed above.
Configure the script by modifying the variables as needed.
Make the script executable: chmod +x <script_name>.sh
Schedule the script to run automatically using cron. For Example:
Bash

crontab -e
0 2 * * * /path/to/<script_name>.sh > /path/to/backup.log 2>&1
(This example runs the script at 2:00 AM daily and logs the output.)
Future Enhancements
Log Rotation: Implement log rotation to manage application log files.
Differential Backups: Modify the script to compare files with the existing backups and upload only new or modified files, improving efficiency.
Error Handling: Add robust error handling to the script.
Notifications: Implement notifications (e.g., email or Slack) to report backup status.
Compression level: Add compression level options to the zip command.
Contributing
Contributions are welcome! Please feel free to submit pull requests or open issues for bug fixes or feature requests.

License
This project is licensed under the MIT License - see the LICENSE file for details. 1  
