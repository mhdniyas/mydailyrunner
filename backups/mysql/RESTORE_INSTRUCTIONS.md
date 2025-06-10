# MySQL Backup Restoration Instructions

## Backup Details
- **Backup Date**: $(date)
- **Backup Location**: /var/www/ard5/backups/mysql/20250610_070956/
- **Full Backup File**: full_backup_all_databases.sql

## To Restore All Databases:
```bash
mysql < /var/www/ard5/backups/mysql/20250610_070956/full_backup_all_databases.sql
```

## To Restore Individual Database:
```bash
# Example for ard5_db:
mysql ard5_db < /var/www/ard5/backups/mysql/20250610_070956/ard5_db_backup.sql
```

## Available Individual Backups:
- ard5_db_backup.sql
- equipnow_backup.sql
- equipnow_db_backup.sql
- fenladb_backup.sql
- fenlainteriors_backup.sql
- kapcwayanad_db_backup.sql
- kapcwayanad_test_backup.sql
- kapcwayanad_test_new_backup.sql
- league_manager_backup.sql

## Backup Verification:
To verify backup integrity:
```bash
mysql < backup_file.sql --force --verbose
```
