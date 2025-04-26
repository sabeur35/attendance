# Hostinger Deployment Checklist

Use this checklist to ensure you've completed all necessary steps for deploying to Hostinger.

## Pre-Deployment

- [ ] Run `prepare_for_hostinger.bat` (Windows) or `prepare_for_hostinger.sh` (Linux/Mac)
- [ ] Update `.env.production` with your Hostinger credentials
- [ ] Test the application locally with production settings
- [ ] Commit all changes to your repository (if using version control)

## Hostinger Setup

- [ ] Create a new database in Hostinger control panel
- [ ] Note database credentials (name, username, password)
- [ ] Set up a domain or subdomain for your application

## Uploading

- [ ] Upload all files to Hostinger via FTP or Git
- [ ] Rename `.env.production` to `.env` on the server
- [ ] Set proper file permissions:
  - [ ] Directories: 755
  - [ ] Files: 644
  - [ ] Storage directory: 775
  - [ ] Bootstrap/cache directory: 775

## Post-Upload

- [ ] Run migrations on the server: `php artisan migrate --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Clear and rebuild caches: `php artisan optimize`
- [ ] Test the application on your Hostinger domain
- [ ] Check for any errors in `storage/logs/laravel.log`

## Final Checks

- [ ] Verify all features are working correctly
- [ ] Ensure APP_DEBUG is set to false
- [ ] Confirm HTTPS is working (if configured)
- [ ] Test user registration and login
- [ ] Test the attendance system functionality

## Notes

Use this space to note any specific configurations or issues encountered during deployment:

```
_______________________________
_______________________________
_______________________________
```
